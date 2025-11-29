<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAdminController extends Controller
{
    // List owners and contractors
    public function index(Request $request)
    {
        $type = $request->input('type','all'); // owner | contractor | all
        $q = DB::table('accounts');

        if ($type !== 'all') {
            $q->where('role', $type); // role: owner|contractor|admin
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $key = $request->q;
            $q->where(function($sub) use ($key) {
                $sub->where('email','like','%'.$key.'%')
                    ->orWhere('first_name','like','%'.$key.'%')
                    ->orWhere('last_name','like','%'.$key.'%');
            });
        }

        $users = $q->orderBy('created_at','desc')->paginate(25);
        return view('admin.users.index', compact('users','type'));
    }

    // Show user and documents
    public function show($id)
    {
        $user = DB::table('accounts')->where('id', $id)->first();
        if (!$user) return back()->with('error','User not found');

        $docs = DB::table('user_documents')->where('user_id', $id)->get();
        return view('admin.users.show', compact('user','docs'));
    }

    // Approve or reject registration
    public function verifyRegistration($id, Request $request)
    {
        $action = $request->input('action'); // approve | reject
        $notes = $request->input('notes', null);

        $user = DB::table('accounts')->where('id',$id)->first();
        if (!$user) return back()->with('error','User not found');

        $newStatus = $action === 'approve' ? 'active' : 'rejected';
        DB::table('accounts')->where('id',$id)->update(['status'=>$newStatus,'updated_at'=>now()]);

        DB::table('admin_audit_logs')->insert([
            'admin_user_id' => session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action' => ucfirst($action).' registration for user '.$id,
            'meta' => json_encode(['user_id'=>$id,'notes'=>$notes]),
            'created_at' => now()
        ]);

        DB::table('notifications')->insert([
            'user_type' => $user->role ?? 'owner',
            'user_id' => $id,
            'title' => $action === 'approve' ? 'Registration Approved' : 'Registration Rejected',
            'message' => $action === 'approve' ? 'Your account has been approved by the admin.' : ('Your registration was rejected. Reason: '.$notes),
            'is_read' => 0,
            'created_at' => now()
        ]);

        return back()->with('success','User '.$action.'d.');
    }

    // Flag a user
    public function flag($id, Request $request)
    {
        $reason = $request->input('reason','Flagged by admin');
        DB::table('accounts')->where('id',$id)->update(['flagged'=>1,'flag_reason'=>$reason,'updated_at'=>now()]);

        DB::table('admin_audit_logs')->insert([
            'admin_user_id' => session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action' => "Flagged user {$id}",
            'meta' => json_encode(['user_id'=>$id,'reason'=>$reason]),
            'created_at' => now()
        ]);

        return back()->with('success','User flagged.');
    }

    // Change status: active | suspended | banned | deactivated
    public function changeStatus($id, Request $request)
    {
        $status = $request->input('status');
        if (!in_array($status, ['active','suspended','banned','deactivated'])) {
            return back()->with('error','Invalid status.');
        }

        DB::table('accounts')->where('id',$id)->update(['status'=>$status,'updated_at'=>now()]);
        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Changed status of user {$id} to {$status}",
            'meta'=>json_encode(['user_id'=>$id,'status'=>$status]),
            'created_at'=>now()
        ]);

        DB::table('notifications')->insert([
            'user_type' => DB::table('accounts')->where('id',$id)->value('role') ?? 'owner',
            'user_id' => $id,
            'title' => 'Account status changed',
            'message' => "Your account status has been changed to {$status} by admin.",
            'is_read' => 0,
            'created_at' => now()
        ]);

        return back()->with('success','Status updated.');
    }

    // Admin reset password (temporary)
    public function resetPassword($id)
    {
        $user = DB::table('accounts')->where('id',$id)->first();
        if (!$user) return back()->with('error','User not found');

        $temp = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'),0,8);
        $hashed = password_hash($temp, PASSWORD_DEFAULT);

        DB::table('accounts')->where('id',$id)->update(['password'=>$hashed,'updated_at'=>now()]);

        DB::table('admin_audit_logs')->insert([
            'admin_user_id'=>session('user')->admin_user_id ?? session('user')->user_id ?? null,
            'action'=>"Reset password for user {$id}",
            'meta'=>json_encode(['user_id'=>$id]),
            'created_at'=>now()
        ]);

        DB::table('notifications')->insert([
            'user_type' => $user->role ?? 'owner',
            'user_id' => $id,
            'title' => 'Password Reset by Admin',
            'message' => "Your password has been reset by admin. Temporary password: {$temp}",
            'is_read'=>0,
            'created_at'=>now()
        ]);

        return back()->with('success','Temporary password generated and notified.');
    }
}
