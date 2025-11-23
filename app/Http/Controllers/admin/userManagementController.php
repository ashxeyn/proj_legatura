<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\authController;
use Illuminate\Http\Request;

class userManagementController extends authController
{
    public function propertyOwners()
    {
        return view('admin.userManagement.propertyOwner');
    }

    public function contractors()
    {
        return view('admin.userManagement.contractor');
    }

    public function viewPropertyOwner($id)
    {
        // In the future, fetch data from database using $id
        return view('admin.userManagement.propertyOwner_Views');
    }

    public function viewContractor()
    {
        // In the future, fetch data from database using contractor id
        return view('admin.userManagement.contractor_Views');
    }

    public function verificationRequest()
    {
        return view('admin.userManagement.verificationRequest');
    }

    public function suspendedAccounts()
    {
        return view('admin.userManagement.suspendedAccounts');
    }

    public function reactivateSuspendedAccount(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string',
            'entityType' => 'required|in:contractor,owner',
            'mode' => 'required|in:keep,edit',
        ]);

        // TODO: Persist reactivation in DB based on $validated['id'] and entity type
        // For now, simulate success response
        return response()->json([
            'success' => true,
            'message' => 'Account reactivated successfully.',
            'data' => $validated,
        ]);
    }
}
