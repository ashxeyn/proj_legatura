<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisputeActionController extends Controller
{
    // Request more evidence (adds entry to dispute_messages)
    public function requestEvidence($disputeId, Request $request)
    {
        $note = $request->input('note','Please upload additional evidence');

        DB::table('dispute_messages')->insert([
            'dispute_id' => $disputeId,
            'sender_type' => 'admin',
            'sender_id' => session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'message' => $note,
            'created_at' => now()
        ]);

        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Requested more evidence for dispute {$disputeId}",
            'meta'=>json_encode(['dispute_id'=>$disputeId,'note'=>$note]),
            'created_at'=>now()
        ]);

        // notify parties
        $dispute = DB::table('disputes')->where('id',$disputeId)->first();
        if ($dispute) {
            DB::table('notifications')->insert([
                ['user_type'=>'owner','user_id'=>$dispute->owner_id,'title'=>'More evidence requested','message'=>$note,'is_read'=>0,'created_at'=>now()],
                ['user_type'=>'contractor','user_id'=>$dispute->contractor_id,'title'=>'More evidence requested','message'=>$note,'is_read'=>0,'created_at'=>now()]
            ]);
        }

        return back()->with('success','Evidence request sent.');
    }

    // Freeze / unfreeze handled in ProjectModerationController, but we keep wrappers for convenience
    public function freezeProject($projectId, Request $request)
    {
        return app(ProjectModerationController::class)->freeze($projectId, $request);
    }

    public function unfreezeProject($projectId)
    {
        return app(ProjectModerationController::class)->unfreeze($projectId);
    }

    // Issue final ruling: owner | contractor | split
    public function issueRuling($disputeId, Request $request)
    {
        $ruling = $request->input('ruling');
        $notes = $request->input('notes', null);
        $dispute = DB::table('disputes')->where('id',$disputeId)->first();
        if (!$dispute) return back()->with('error','Dispute not found');

        DB::table('disputes')->where('id',$disputeId)->update([
            'dispute_status' => 'resolved',
            'resolution' => $ruling,
            'resolution_notes' => $notes,
            'resolved_by' => session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'resolved_at' => now()
        ]);

        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Issued ruling {$ruling} for dispute {$disputeId}",
            'meta'=>json_encode(['dispute_id'=>$disputeId,'ruling'=>$ruling]),
            'created_at'=>now()
        ]);

        // notify both parties
        DB::table('notifications')->insert([
            ['user_type'=>'owner','user_id'=>$dispute->owner_id,'title'=>'Dispute resolved','message'=>"Ruling: {$ruling}. Notes: {$notes}",'is_read'=>0,'created_at'=>now()],
            ['user_type'=>'contractor','user_id'=>$dispute->contractor_id,'title'=>'Dispute resolved','message'=>"Ruling: {$ruling}. Notes: {$notes}",'is_read'=>0,'created_at'=>now()]
        ]);

        return back()->with('success','Ruling issued.');
    }
}
