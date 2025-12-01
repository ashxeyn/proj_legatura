<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = DB::table('projects')
            ->leftJoin('property_owners', 'projects.owner_id', '=', 'property_owners.owner_id')
            ->select('projects.*', 'property_owners.first_name', 'property_owners.last_name')
            ->orderBy('projects.created_at', 'desc')
            ->paginate(20);

        return view('admin.projects.index', compact('q'));
    }

    public function show($projectId)
    {
        $project = DB::table('projects')->where('project_id', $projectId)->first();
        if (!$project) return redirect()->route('admin.projects.index')->with('error','Project not found');

        // fetch bids (assumes bids table exists)
        $bids = DB::table('bids')
            ->where('project_id', $projectId)
            ->leftJoin('contractors', 'bids.contractor_id', '=', 'contractors.contractor_id')
            ->select('bids.*','contractors.company_name','contractors.years_of_experience')
            ->orderBy('bids.submitted_at','desc')
            ->get();

        // fetch milestones and payment plan
        $milestones = DB::table('milestones')->where('project_id', $projectId)->get();

        return view('admin.projects.show', compact('project','bids','milestones'));
    }

    public function approve($projectId)
    {
        DB::table('projects')->where('project_id', $projectId)->update([
            'project_status' => 'open',
            'updated_at' => now()
        ]);
        // create audit log
        DB::table('admin_audit_logs')->insert([
            'admin_user_id' => $user->admin_user_id ?? $user->id ?? null,
            'action' => "Approved project {$projectId}",
            'meta' => json_encode(['project_id'=>$projectId]),
            'created_at'=>now()
        ]);
        return back()->with('success','Project approved.');
    }

    public function reject($projectId, Request $request)
    {
        $reason = $request->input('reason', 'Rejected by admin');
        DB::table('projects')->where('project_id', $projectId)->update([
            'project_status' => 'rejected',
            'updated_at' => now()
        ]);
        DB::table('admin_audit_logs')->insert([
           'admin_user_id' => $user->admin_user_id ?? $user->id ?? null,
            'action' => "Rejected project {$projectId}",
            'meta' => json_encode(['project_id'=>$projectId,'reason'=>$reason]),
            'created_at'=>now()
        ]);
        // Optionally notify owner via email/notification
        return back()->with('success','Project rejected.');
    }

    public function assignContractor($projectId, Request $request)
    {
        $contractorId = $request->input('contractor_id');
        // validation
        $contractor = DB::table('contractors')->where('contractor_id', $contractorId)->first();
        if (!$contractor) return back()->with('error','Contractor not found');

        $user = session('user');

        DB::table('projects')->where('project_id', $projectId)->update([
            'selected_contractor_id' => $contractorId,
            'project_status' => 'in_progress', // mark as started when assigned
            'updated_at' => now()
        ]);
        DB::table('admin_audit_logs')->insert([
            'admin_user_id' => $user->admin_user_id ?? $user->id ?? null,
            'action' => "Assigned contractor {$contractorId} to project {$projectId}",
            'meta' => json_encode(['project_id'=>$projectId,'contractor_id'=>$contractorId]),
            'created_at'=>now()
        ]);
        return back()->with('success','Contractor assigned.');
    }

    public function subscriptions()
    {
        return view('admin.projectManagement.subscriptions');
    }

    public function listOfProjects()
    {
        return view('admin.projectManagement.listOfprojects');
    }

    public function disputesReports()
    {
        // Mock data for analytics - replace with real DB queries
        $projectsAnalytics = [
            'data' => [
                ['label' => 'Completed', 'count' => 45],
                ['label' => 'In Progress', 'count' => 32],
                ['label' => 'Pending', 'count' => 18],
                ['label' => 'Cancelled', 'count' => 5],
            ]
        ];

        $projectSuccessRate = [
            'data' => [
                ['label' => 'Residential', 'count' => 65, 'color' => '#10b981'],
                ['label' => 'Commercial', 'count' => 25, 'color' => '#3b82f6'],
                ['label' => 'Industrial', 'count' => 10, 'color' => '#f59e0b'],
            ]
        ];

        $projectsTimeline = [
            'dateRange' => 'Jan 2025 - Nov 2025',
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov'],
            'newProjects' => [12, 15, 18, 20, 22, 25, 28, 30, 35, 38, 40],
            'completedProjects' => [8, 10, 12, 15, 18, 20, 22, 25, 28, 30, 32],
        ];

        return view('admin.projectManagement.disputesReports', compact('projectsAnalytics', 'projectSuccessRate', 'projectsTimeline'));
    }

    public function messages()
    {
        return view('admin.projectManagement.messages');
    }
}
