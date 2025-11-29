<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidModerationController extends Controller
{
    // view bids with filters
    public function index(Request $request)
    {
        $q = DB::table('bids')
            ->leftJoin('projects','bids.project_id','=','projects.project_id')
            ->leftJoin('contractors','bids.contractor_id','=','contractors.contractor_id')
            ->select('bids.*','projects.project_title','contractors.company_name')
            ->whereNull('bids.deleted_at');

        if ($request->filled('project_id')) $q->where('bids.project_id',$request->project_id);
        if ($request->filled('contractor_id')) $q->where('bids.contractor_id',$request->contractor_id);
        if ($request->filled('status')) $q->where('bids.bid_status',$request->status);
        if ($request->filled('q')) {
            $q->where('projects.project_title','like','%'.$request->q.'%');
        }

        $bids = $q->orderBy('bids.submitted_at','desc')->paginate(25);
        return view('admin.bids.index', compact('bids'));
    }

    // view a single bid with attachments
    public function show($bidId)
    {
        $bid = DB::table('bids')->where('bid_id',$bidId)->whereNull('deleted_at')->first();
if (!$bid) return redirect()->route('admin.bids.index')->with('error','Bid not found');


        $project = DB::table('projects')->where('project_id',$bid->project_id)->first();
        $contractor = DB::table('contractors')->where('contractor_id',$bid->contractor_id)->first();
        $attachments = DB::table('bid_attachments')->where('bid_id',$bidId)->get();

        return view('admin.bids.show', compact('bid','project','contractor','attachments'));
    }

    // delete fraudulent / abusive bid
    public function delete($bidId)
{
    $bid = DB::table('bids')->where('bid_id',$bidId)->whereNull('deleted_at')->first();
if (!$bid) return redirect()->route('admin.bids.index')->with('error','Bid not found');


    DB::table('bids')->where('bid_id', $bidId)->update([
        'deleted_at' => now()
    ]);

    // Optional: log action
    $user = session('user');
    DB::table('admin_audit_logs')->insert([
        'admin_user_id' => $user->admin_user_id ?? $user->user_id ?? null,
        'action' => "Soft-deleted bid {$bidId}",
        'meta' => json_encode(['bid_id' => $bidId, 'project_id' => $bid->project_id]),
        'created_at' => now()
    ]);

    return redirect()->route('admin.bids.index')->with('success','Bid soft-deleted.');
}


    // show edit form
public function edit($bidId)
{
    $bid = DB::table('bids')->where('bid_id',$bidId)->whereNull('deleted_at')->first();
if (!$bid) return redirect()->route('admin.bids.index')->with('error','Bid not found');


    $project = DB::table('projects')->where('project_id', $bid->project_id)->first();
    $contractor = DB::table('contractors')->where('contractor_id', $bid->contractor_id)->first();

    return view('admin.bids.edit', compact('bid','project','contractor'));
}


// update bid details
public function update(Request $request, $bidId)
{
   $bid = DB::table('bids')->where('bid_id',$bidId)->whereNull('deleted_at')->first();
if (!$bid) return redirect()->route('admin.bids.index')->with('error','Bid not found');


    DB::table('bids')->where('bid_id', $bidId)->update([
        'proposed_cost' => $request->proposed_cost,
        'estimated_timeline' => $request->estimated_timeline,
        'bid_status' => $request->status,
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.bids.show', $bidId)->with('success','Bid updated.');
}

// force assign bid to project
public function forceAssign(Request $request, $bidId)
{
    $bid = DB::table('bids')->where('bid_id',$bidId)->whereNull('deleted_at')->first();
if (!$bid) return redirect()->route('admin.bids.index')->with('error','Bid not found');


    DB::table('projects')->where('project_id', $bid->project_id)->update([
        'assigned_bid_id' => $bidId,
    ]);

    return back()->with('success','Bid force-assigned to project.');
}

}
