<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProjects = DB::table('projects')->count();
        $openProjects = DB::table('projects')->where('project_status', 'open')->count();
        $pendingVerifications = DB::table('contractors')->where('verification_status', 'pending')->count();
        $openDisputes = DB::table('disputes')->where('dispute_status', 'open')->count();

        return view('admin.dashboard', compact('totalProjects','openProjects','pendingVerifications','openDisputes'));
    }
}
