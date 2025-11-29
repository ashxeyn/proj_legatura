<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectModerationController extends Controller
{
    public function index(Request $request)
    {
        $q = DB::table('projects')
            ->leftJoin('property_owners','projects.owner_id','=','property_owners.owner_id')
            ->select('projects.*','property_owners.first_name','property_owners.last_name');
            $q->where('projects.is_deleted', 0);

        if ($request->filled('status')) $q->where('projects.project_status', $request->status);
        if ($request->filled('q')) $q->where('projects.project_title','like','%'.$request->q.'%');

        $projects = $q->orderBy('projects.created_at','desc')->paginate(25);
        return view('admin.projects.index', compact('projects'));


    }

    public function edit($projectId)
    {
        $project = DB::table('projects')->where('project_id',$projectId)->first();
        if (!$project) return back()->with('error','Project not found');

        $milestones = DB::table('milestones')->where('project_id',$projectId)->orderBy('milestone_id', 'asc')->get();
        $files = DB::table('project_files')->where('project_id',$projectId)->get();

        return view('admin.projects.edit', compact('project','milestones','files'));
    }

    public function update($projectId, Request $request)
    {
        $data = [
            'project_title' => $request->input('project_title'),
            'project_description' => $request->input('project_description'),
            'budget_range_min' => $request->input('budget_min'),
    'budget_range_max' => $request->input('budget_max'),
            'project_location' => $request->input('project_location'),
            'updated_at' => now()
        ];
        DB::table('projects')->where('project_id',$projectId)->update($data);

        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Edited project {$projectId}",
            'meta'=>json_encode(['project_id'=>$projectId]),
            'created_at'=>now()
        ]);

        return back()->with('success','Project updated.');
    }

    public function delete($projectId)
{
    DB::table('projects')
        ->where('project_id', $projectId)
        ->update([
            'is_deleted' => 1,
            'updated_at' => now()
        ]);

    DB::table('admin_audit_logs')->insert([
        'admin_user_id' => session('user')->admin_user_id ?? session('user')->user_id ?? null,
        'action' => "Archived project {$projectId}",
        'meta' => json_encode(['project_id' => $projectId]),
        'created_at' => now()
    ]);

    return redirect()->route('admin.projects.index')
        ->with('success', 'Project archived.');
}


    public function approve($projectId)
    {
        DB::table('projects')->where('project_id',$projectId)->update(['project_status'=>'open','updated_at'=>now()]);
        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Approved project {$projectId}",
            'meta'=>json_encode(['project_id'=>$projectId]),
            'created_at'=>now()
        ]);
        return back()->with('success','Project approved.');
    }

    public function reject($projectId, Request $request)
    {
        $reason = $request->input('reason','Rejected by admin');
        DB::table('projects')->where('project_id',$projectId)->update(['project_status'=>'rejected','rejection_reason'=>$reason,'updated_at'=>now()]);
        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Rejected project {$projectId}",
            'meta'=>json_encode(['project_id'=>$projectId,'reason'=>$reason]),
            'created_at'=>now()
        ]);
        return back()->with('success','Project rejected.');
    }

    public function freeze($projectId, Request $request)
    {
        $reason = $request->input('reason','Frozen by admin');
        DB::table('projects')->where('project_id',$projectId)->update(['is_frozen'=>1,'frozen_reason'=>$reason,'updated_at'=>now()]);
        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Froze project {$projectId}",
            'meta'=>json_encode(['project_id'=>$projectId,'reason'=>$reason]),
            'created_at'=>now()
        ]);
        return back()->with('success','Project frozen.');
    }

    public function unfreeze($projectId)
    {
        DB::table('projects')->where('project_id',$projectId)->update(['is_frozen'=>0,'frozen_reason'=>null,'updated_at'=>now()]);
        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Unfroze project {$projectId}",
            'meta'=>json_encode(['project_id'=>$projectId]),
            'created_at'=>now()
        ]);
        return back()->with('success','Project unfrozen.');
    }

    public function view($projectId)
{
    $project = DB::table('projects')
        ->leftJoin('property_owners','projects.owner_id','=','property_owners.owner_id')
        ->select('projects.*','property_owners.first_name','property_owners.last_name')
        ->where('projects.project_id',$projectId)
        ->first();

    if (!$project) return back()->with('error','Project not found');

    $files = DB::table('project_files')->where('project_id',$projectId)->get();
    $milestones = DB::table('milestones')->where('project_id',$projectId)->get();
    $updates = DB::table('progress_files')
        ->join('milestone_items','progress_files.item_id','=','milestone_items.item_id')
        ->join('milestones','milestone_items.milestone_id','=','milestones.milestone_id')
        ->where('milestones.project_id',$projectId)
        ->get();
    
    $bids = DB::table('bids')
    ->leftJoin('contractors','bids.contractor_id','=','contractors.contractor_id')
    ->select(
        'bids.*',
        'contractors.company_name'
    )
    ->where('bids.project_id', $projectId)
    ->orderBy('bids.submitted_at','desc')
    ->get();


    return view('admin.projects.view', compact('project','files','milestones','updates','bids'));
}


}



