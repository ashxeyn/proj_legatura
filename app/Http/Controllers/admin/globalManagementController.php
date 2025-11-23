<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class globalManagementController extends Controller
{
    /**
     * Display the bid management page
     */
    public function bidManagement()
    {
        return view('admin.globalManagement.bidManagement');
    }

    /**
     * Display the proof of payments page
     */
    public function proofOfPayments()
    {
        return view('admin.globalManagement.proofOfpayments');
    }

    /**
     * Display the AI management page
     */
    public function aiManagement()
    {
        return view('admin.globalManagement.aiManagement');
    }
}
