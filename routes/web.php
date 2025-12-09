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
use App\Http\Controllers\admin\ProjectAdminController;

Route::get('/', function () {
    return view('startPoint');
});

// Authentication Routes
Route::get('/accounts/login', [authController::class, 'showLoginForm'])->name('accounts.login');
Route::post('/accounts/login', [authController::class, 'login'])->name('accounts.login.post');
Route::get('/accounts/signup', [authController::class, 'showSignupForm'])->name('accounts.signup');
Route::post('/accounts/logout', [authController::class, 'logout'])->name('accounts.logout');
Route::get('/accounts/logout', [authController::class, 'logout'])->name('accounts.logout.get');

// Admin Authentication Routes
Route::get('/admin/login', function() {
    return view('admin.logIn_signUp.logIn');
})->name('admin.login');
Route::post('/admin/login', [authController::class, 'adminLogin'])->name('admin.login.post');

Route::get('/admin/signup', function() {
    return view('admin.logIn_signUp.signUp');
})->name('admin.signup');
Route::post('/admin/signup', [authController::class, 'adminSignup'])->name('admin.signup.post');

Route::post('/admin/logout', [authController::class, 'adminLogout'])->name('admin.logout');

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
Route::get('/admin/analytics/user-activity', [analyticsController::class, 'userActivityAnalytics'])->name('admin.analytics.userActivity');
Route::get('/admin/analytics/project-performance', [analyticsController::class, 'projectPerformanceAnalytics'])->name('admin.analytics.projectPerformance');
Route::get('/admin/analytics/bid-completion', [analyticsController::class, 'bidCompletionAnalytics'])->name('admin.analytics.bidCompletion');
Route::get('/admin/analytics/reports', [analyticsController::class, 'reportsAnalytics'])->name('admin.analytics.reports');

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
Route::get('/admin/global-management/posting-management', [globalManagementController::class, 'postingManagement'])->name('admin.globalManagement.postingManagement');

// Project Management Routes
Route::get('/admin/project-management/list-of-projects', [ProjectAdminController::class, 'listOfProjects'])->name('admin.projectManagement.listOfprojects');
Route::get('/admin/project-management/subscriptions', [ProjectAdminController::class, 'subscriptions'])->name('admin.projectManagement.subscriptions');
Route::get('/admin/project-management/disputes-reports', [ProjectAdminController::class, 'disputesReports'])->name('admin.projectManagement.disputesReports');
Route::get('/admin/project-management/messages', [ProjectAdminController::class, 'messages'])->name('admin.projectManagement.messages');

// Settings Routes
Route::get('/admin/settings/notifications', function() {
    return view('admin.settings.notifications');
})->name('admin.settings.notifications');
Route::get('/admin/settings/security', function() {
    return view('admin.settings.security');
})->name('admin.settings.security');

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


// =============================================
// ADMIN API ROUTES (AJAX Endpoints)
// =============================================

// User Management API
Route::prefix('/api/admin/users')->group(function () {
    // Property Owners
    Route::get('/property-owners', [userManagementController::class, 'getPropertyOwnersApi'])->name('api.admin.propertyOwners');
    Route::get('/property-owners/{id}', [userManagementController::class, 'getPropertyOwnerApi'])->name('api.admin.propertyOwner');
    Route::post('/property-owners/{id}/verify', [userManagementController::class, 'verifyPropertyOwner'])->name('api.admin.propertyOwner.verify');
    Route::post('/property-owners/{id}/suspend', [userManagementController::class, 'suspendPropertyOwner'])->name('api.admin.propertyOwner.suspend');
    
    // Contractors
    Route::get('/contractors', [userManagementController::class, 'getContractorsApi'])->name('api.admin.contractors');
    Route::get('/contractors/{id}', [userManagementController::class, 'getContractorApi'])->name('api.admin.contractor');
    Route::post('/contractors/{id}/verify', [userManagementController::class, 'verifyContractor'])->name('api.admin.contractor.verify');
    Route::post('/contractors/{id}/suspend', [userManagementController::class, 'suspendContractor'])->name('api.admin.contractor.suspend');
    
    // Verification Requests
    Route::get('/verification-requests', [userManagementController::class, 'getVerificationRequestsApi'])->name('api.admin.verificationRequests');
    Route::post('/verification-requests/{id}/approve', [userManagementController::class, 'approveVerification'])->name('api.admin.verificationRequest.approve');
    Route::post('/verification-requests/{id}/reject', [userManagementController::class, 'rejectVerification'])->name('api.admin.verificationRequest.reject');
    
    // Suspended Accounts
    Route::get('/suspended', [userManagementController::class, 'getSuspendedAccountsApi'])->name('api.admin.suspendedAccounts');
    Route::post('/suspended/{id}/reactivate', [userManagementController::class, 'reactivateSuspendedAccount'])->name('api.admin.suspendedAccount.reactivate');
});

// Also expose shorter contractor routes under /api/admin for convenience
Route::prefix('/api/admin')->group(function () {
    Route::get('/contractors', [userManagementController::class, 'getContractorsApi'])->name('api.admin.contractors.short');
    Route::get('/contractors/{id}', [userManagementController::class, 'getContractorApi'])->name('api.admin.contractor.short');
    Route::post('/contractors/{id}/verify', [userManagementController::class, 'verifyContractor'])->name('api.admin.contractor.verify.short');
    Route::post('/contractors/{id}/suspend', [userManagementController::class, 'suspendContractor'])->name('api.admin.contractor.suspend.short');
});

// Global Management API
Route::prefix('/api/admin/management')->group(function () {
    // Bid Management
    Route::get('/bids', [globalManagementController::class, 'getBidsApi'])->name('api.admin.bids');
    Route::post('/bids/{id}/approve', [globalManagementController::class, 'approveBid'])->name('api.admin.bid.approve');
    Route::post('/bids/{id}/reject', [globalManagementController::class, 'rejectBid'])->name('api.admin.bid.reject');
    
    // Proof of Payments
    Route::get('/payments', [globalManagementController::class, 'getPaymentsApi'])->name('api.admin.payments');
    Route::post('/payments/{id}/verify', [globalManagementController::class, 'verifyPayment'])->name('api.admin.payment.verify');
    Route::post('/payments/{id}/reject', [globalManagementController::class, 'rejectPayment'])->name('api.admin.payment.reject');
    
    // Posting Management
    Route::get('/postings', [globalManagementController::class, 'getPostingsApi'])->name('api.admin.postings');
    Route::post('/postings/{id}/approve', [globalManagementController::class, 'approvePosting'])->name('api.admin.posting.approve');
    Route::post('/postings/{id}/reject', [globalManagementController::class, 'rejectPosting'])->name('api.admin.posting.reject');
    
    // AI Management
    Route::get('/ai-stats', [globalManagementController::class, 'getAiStatsApi'])->name('api.admin.aiStats');
});

// Analytics API
Route::prefix('/api/admin/analytics')->group(function () {
    Route::get('/projects', [analyticsController::class, 'getProjectsAnalyticsApi'])->name('api.admin.analytics.projects');
    Route::get('/timeline', [analyticsController::class, 'getProjectsTimelineData'])->name('api.admin.analytics.timeline');
    Route::get('/subscription', [analyticsController::class, 'subscriptionAnalytics'])->name('api.admin.analytics.subscription');
    Route::get('/subscription/revenue', [analyticsController::class, 'subscriptionRevenue'])->name('api.admin.analytics.subscriptionRevenue');
    Route::get('/user-activity', [analyticsController::class, 'userActivityAnalytics'])->name('api.admin.analytics.userActivity');
    Route::get('/project-performance', [analyticsController::class, 'projectPerformanceAnalytics'])->name('api.admin.analytics.projectPerformance');
    Route::get('/bid-completion', [analyticsController::class, 'bidCompletionAnalytics'])->name('api.admin.analytics.bidCompletion');
});

// Project Management API
Route::prefix('/api/admin/projects')->group(function () {
    Route::get('/', [ProjectAdminController::class, 'getProjectsApi'])->name('api.admin.projects');
    Route::post('/{id}/assign-contractor', [ProjectAdminController::class, 'assignContractor'])->name('api.admin.project.assignContractor');
    Route::post('/{id}/approve', [ProjectAdminController::class, 'approve'])->name('api.admin.project.approve');
    Route::post('/{id}/reject', [ProjectAdminController::class, 'reject'])->name('api.admin.project.reject');
    
    Route::get('/subscriptions', [ProjectAdminController::class, 'getSubscriptionsApi'])->name('api.admin.subscriptions');
    Route::get('/messages', [ProjectAdminController::class, 'getMessagesApi'])->name('api.admin.messages');
    Route::get('/disputes', [ProjectAdminController::class, 'getDisputesApi'])->name('api.admin.disputes');
});

// New admin resource API routes (CRUD)
Route::prefix('/api/admin')->group(function () {
    Route::apiResource('projects', App\Http\Controllers\Admin\ProjectController::class);
    Route::apiResource('bids', App\Http\Controllers\Admin\BidController::class);
    Route::apiResource('milestones', App\Http\Controllers\Admin\MilestoneController::class);
    Route::apiResource('payments', App\Http\Controllers\Admin\PaymentController::class);
});

// File serving route (fallback for Windows/XAMPP when symlinks don't work)
Route::get('/storage/{path}', [authController::class, 'serve'])->where('path', '.*')->name('storage.serve');
