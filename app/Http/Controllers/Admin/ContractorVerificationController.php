<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractorVerificationController extends Controller
{
    public function index(Request $request)
    {
        $q = DB::table('contractors');
        if ($request->filled('status')) $q->where('verification_status', $request->status);
        if ($request->filled('q')) $q->where('company_name','like','%'.$request->q.'%');
        $contractors = $q->orderBy('created_at','desc')->paginate(25);
        return view('admin.contractors.index', compact('contractors'));
    }

    public function show($id)
    {
        $contractor = DB::table('contractors')->where('contractor_id',$id)->first();
        if (!$contractor) return back()->with('error','Contractor not found');

        $docs = DB::table('user_documents')->where('user_id',$contractor->contractor_id)->get();
        return view('admin.contractors.show', compact('contractor','docs'));
    }

    public function approve($id, Request $request)
    {
        DB::table('contractors')->where('contractor_id',$id)->update(['verification_status'=>'verified','verified_at'=>now(),'updated_at'=>now()]);
        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Approved contractor {$id}",
            'meta'=>json_encode(['contractor_id'=>$id]),
            'created_at'=>now()
        ]);
        DB::table('notifications')->insert([
            'user_type'=>'contractor','user_id'=>$id,'title'=>'Verification Approved','message'=>'Your contractor documents are approved.','is_read'=>0,'created_at'=>now()
        ]);
        return back()->with('success','Contractor approved.');
    }

    public function reject($id, Request $request)
    {
        $reason = $request->input('reason','Documents incomplete');
        DB::table('contractors')->where('contractor_id',$id)->update(['verification_status'=>'rejected','rejection_reason'=>$reason,'updated_at'=>now()]);
        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Rejected contractor {$id}",
            'meta'=>json_encode(['contractor_id'=>$id,'reason'=>$reason]),
            'created_at'=>now()
        ]);
        DB::table('notifications')->insert([
            'user_type'=>'contractor','user_id'=>$id,'title'=>'Verification Rejected','message'=>"Your documents were rejected: {$reason}",'is_read'=>0,'created_at'=>now()
        ]);
        return back()->with('success','Contractor rejected.');
    }
}
