<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\authController;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\projectPosting\projectPostingController;
use App\Http\Controllers\applyBid\applyBidController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\analyticsController;
use App\Http\Controllers\admin\userManagementController;
use App\Http\Controllers\admin\globalManagementController;

Route::get('/', function () {
    return view('startPoint');
});

// Authentication Routes
Route::get('/accounts/login', [authController::class, 'showLoginForm'])->name('accounts.login');
Route::post('/accounts/login', [authController::class, 'login'])->name('accounts.login.post');
Route::get('/accounts/signup', [authController::class, 'showSignupForm'])->name('accounts.signup');
Route::post('/accounts/logout', [authController::class, 'logout'])->name('accounts.logout');
Route::get('/accounts/logout', [authController::class, 'logout'])->name('accounts.logout.get');

// Contractor Signup Routes
Route::post('/accounts/signup/contractor/step1', [authController::class, 'contractorStep1']);
Route::post('/accounts/signup/contractor/step2', [authController::class, 'contractorStep2']);
Route::post('/accounts/signup/contractor/step3/verify-otp', [authController::class, 'contractorVerifyOtp']);
Route::post('/accounts/signup/contractor/step4', [authController::class, 'contractorStep4']);
Route::post('/accounts/signup/contractor/final', [authController::class, 'contractorFinalStep']);

// Property Owner Signup Routes
Route::post('/accounts/signup/owner/step1', [authController::class, 'propertyOwnerStep1']);
Route::post('/accounts/signup/owner/step2', [authController::class, 'propertyOwnerStep2']);
Route::post('/accounts/signup/owner/step3/verify-otp', [authController::class, 'propertyOwnerVerifyOtp']);
Route::post('/accounts/signup/owner/step4', [authController::class, 'propertyOwnerStep4']);
Route::post('/accounts/signup/owner/final', [authController::class, 'propertyOwnerFinalStep']);

// PSGC API Routes
Route::get('/api/psgc/provinces', [authController::class, 'getProvinces']);
Route::get('/api/psgc/provinces/{provinceCode}/cities', [authController::class, 'getCitiesByProvince']);
Route::get('/api/psgc/cities/{cityCode}/barangays', [authController::class, 'getBarangaysByCity']);

// Dashboard Routes
Route::get('/admin/dashboard', [dashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard/earnings', [dashboardController::class, 'getEarnings'])->name('admin.dashboard.earnings');

// Analytics Routes
Route::get('/admin/analytics', [analyticsController::class, 'analytics'])->name('admin.analytics');
Route::get('/admin/analytics/timeline', [analyticsController::class, 'getProjectsTimelineData'])->name('admin.analytics.timeline');
Route::get('/admin/analytics/subscription', [analyticsController::class, 'subscriptionAnalytics'])->name('admin.analytics.subscription');
Route::get('/admin/analytics/subscription/revenue', [analyticsController::class, 'subscriptionRevenue'])->name('admin.analytics.subscription.revenue');

// User Management Routes
Route::get('/admin/user-management/property-owners', [userManagementController::class, 'propertyOwners'])->name('admin.userManagement.propertyOwner');
Route::get('/admin/user-management/property-owners/{id}', [userManagementController::class, 'viewPropertyOwner'])->name('admin.userManagement.propertyOwner.view');
Route::get('/admin/user-management/contractors', [userManagementController::class, 'contractors'])->name('admin.userManagement.contractor');
Route::get('/admin/user-management/contractor/view', [userManagementController::class, 'viewContractor'])->name('admin.userManagement.contractor.view');
Route::get('/admin/user-management/verification-requests', [userManagementController::class, 'verificationRequest'])->name('admin.userManagement.verificationRequest');
Route::get('/admin/user-management/suspended-accounts', [userManagementController::class, 'suspendedAccounts'])->name('admin.userManagement.suspendedAccounts');
Route::post('/admin/user-management/suspended-accounts/reactivate', [userManagementController::class, 'reactivateSuspendedAccount'])->name('admin.userManagement.suspendedAccounts.reactivate');

// Global Management Routes
Route::get('/admin/global-management/bid-management', [globalManagementController::class, 'bidManagement'])->name('admin.globalManagement.bidManagement');
Route::get('/admin/global-management/proof-of-payments', [globalManagementController::class, 'proofOfPayments'])->name('admin.globalManagement.proofOfpayments');
Route::get('/admin/global-management/ai-management', [globalManagementController::class, 'aiManagement'])->name('admin.globalManagement.aiManagement');

Route::get('/dashboard', function() {
    return view('both.dashboard');
});

// Project Posting Routes (Property Owner)
Route::get('/property-owner/post-project', [projectPostingController::class, 'ownerForm'])->name('projects.create');
Route::post('/property-owner/post-project', [projectPostingController::class, 'storeOwner'])->name('projects.storeOwner');
Route::get('/projects/{id}', [projectPostingController::class, 'show'])->name('projects.show');

// Project Posting Routes (Contractor)
Route::get('/contractor/post-project', [projectPostingController::class, 'contractorForm'])->name('contractorProjects.create');
Route::post('/contractor/post-project', [projectPostingController::class, 'storeContractor'])->name('contractorProjects.store');

// Contractor's own projects
Route::get('/contractor/projects/{id}', [projectPostingController::class, 'showContractor'])->name('contractorProjects.show');

// Apply Bid Routes - Contractor browse property owner projects
Route::get('/contractor/browse-projects', [applyBidController::class, 'browseProjects'])->name('contractor.browse.projects');
Route::get('/contractor/project/{id}', [applyBidController::class, 'showProjectDetails'])->name('contractor.project.details');

// Apply Bid Routes - Bid Application
Route::get('/contractor/project/{id}/apply-bid', [applyBidController::class, 'showBidForm'])->name('contractor.bid.form');
Route::post('/contractor/project/{id}/apply-bid', [applyBidController::class, 'submitBid'])->name('contractor.bid.submit');

// Debug route to check projects in database (remove after debugging)
Route::get('/debug/check-projects', function() {
    $projects = DB::table('projects')
        ->where('project_status', 'open')
        ->whereNotNull('owner_id')
        ->select('project_id', 'project_title', 'project_status', 'owner_id', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get();
    
    return response()->json([
        'count' => $projects->count(),
        'projects' => $projects
    ]);
})->name('debug.check.projects');


// File serving route (fallback for Windows/XAMPP when symlinks don't work)
Route::get('/storage/{path}', [authController::class, 'serve'])->where('path', '.*')->name('storage.serve');
