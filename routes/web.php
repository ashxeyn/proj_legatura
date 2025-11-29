<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\contractor\cprocessController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisputeAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\ContractorVerificationController;
use App\Http\Controllers\Admin\ProjectModerationController;
use App\Http\Controllers\Admin\BidModerationController;
use App\Http\Controllers\Admin\DisputeActionController;

Route::get('/', function () {
    return view('startPoint');
});

// Authentication Routes
Route::get('/accounts/login', [authController::class, 'showLoginForm']);
Route::post('/accounts/login', [authController::class, 'login']);
Route::get('/accounts/signup', [authController::class, 'showSignupForm']);
Route::post('/accounts/logout', [authController::class, 'logout']);
Route::get('/accounts/logout', [authController::class, 'logout']);

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

// Role Switch Routes
Route::get('/accounts/switch', [authController::class, 'showSwitchForm']);
Route::post('/accounts/switch/contractor/step1', [authController::class, 'switchContractorStep1']);
Route::post('/accounts/switch/contractor/step2', [authController::class, 'switchContractorStep2']);
Route::post('/accounts/switch/contractor/final', [authController::class, 'switchContractorFinal']);
Route::post('/accounts/switch/owner/step1', [authController::class, 'switchOwnerStep1']);
Route::post('/accounts/switch/owner/step2', [authController::class, 'switchOwnerStep2']);
Route::post('/accounts/switch/owner/final', [authController::class, 'switchOwnerFinal']);

// PSGC API Routes
Route::get('/api/psgc/provinces', [authController::class, 'getProvinces']);
Route::get('/api/psgc/provinces/{provinceCode}/cities', [authController::class, 'getCitiesByProvince']);
Route::get('/api/psgc/cities/{cityCode}/barangays', [authController::class, 'getBarangaysByCity']);

// Dashboard Routes
Route::get('/admin/dashboard', function() {
    return view('admin.dashboard');
});

Route::get('/dashboard', function() {
    return view('both.dashboard');
});

// Contractor Milestone Setup Routes
Route::get('/contractor/milestone/setup', [cprocessController::class, 'showMilestoneSetupForm']);
Route::post('/contractor/milestone/setup/step1', [cprocessController::class, 'milestoneStepOne']);
Route::post('/contractor/milestone/setup/step2', [cprocessController::class, 'milestoneStepTwo']);
Route::post('/contractor/milestone/setup/submit', [cprocessController::class, 'submitMilestone']);


Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Disputes
    Route::get('/disputes', [DisputeAdminController::class, 'index'])->name('admin.disputes.index');
    Route::get('/disputes/{id}', [DisputeAdminController::class, 'show'])->name('admin.disputes.show');
    Route::post('/disputes/{id}/resolve', [DisputeAdminController::class, 'resolve'])->name('admin.disputes.resolve');
});


// Users
Route::get('/users', [UserAdminController::class,'index'])->name('admin.users.index');
Route::get('/users/{id}', [UserAdminController::class,'show'])->name('admin.users.show');
Route::post('/users/{id}/verify', [UserAdminController::class,'verifyRegistration'])->name('admin.users.verify');
Route::post('/users/{id}/flag', [UserAdminController::class,'flag'])->name('admin.users.flag');
Route::post('/users/{id}/change-status', [UserAdminController::class,'changeStatus'])->name('admin.users.change_status');
Route::post('/users/{id}/reset-password', [UserAdminController::class,'resetPassword'])->name('admin.users.reset_password');

// Contractor verification
Route::get('/contractors', [ContractorVerificationController::class,'index'])->name('admin.contractors.index');
Route::get('/contractors/{id}', [ContractorVerificationController::class,'show'])->name('admin.contractors.show');
Route::post('/contractors/{id}/approve', [ContractorVerificationController::class,'approve'])->name('admin.contractors.approve');
Route::post('/contractors/{id}/reject', [ContractorVerificationController::class,'reject'])->name('admin.contractors.reject');

// Projects
Route::get('/projects', [ProjectModerationController::class,'index'])->name('admin.projects.index');
Route::get('/projects/{project}/edit', [ProjectModerationController::class,'edit'])->name('admin.projects.edit');
Route::post('/projects/{project}/update', [ProjectModerationController::class,'update'])->name('admin.projects.update');
Route::post('/projects/{project}/delete', [ProjectModerationController::class,'delete'])->name('admin.projects.delete');
Route::post('/projects/{project}/approve', [ProjectModerationController::class,'approve'])->name('admin.projects.approve');
Route::post('/projects/{project}/reject', [ProjectModerationController::class,'reject'])->name('admin.projects.reject');
Route::post('/projects/{project}/freeze', [ProjectModerationController::class,'freeze'])->name('admin.projects.freeze');
Route::post('/projects/{project}/unfreeze', [ProjectModerationController::class,'unfreeze'])->name('admin.projects.unfreeze');
Route::get('/projects/{project}/view', [ProjectModerationController::class,'view'])->name('admin.projects.view');


// Bids
Route::get('/bids', [BidModerationController::class,'index'])->name('admin.bids.index');
Route::get('/bids/{bid}', [BidModerationController::class,'show'])->name('admin.bids.show');
Route::post('/bids/{bid}/delete', [BidModerationController::class,'delete'])->name('admin.bids.delete');
// Edit bid form
Route::get('/bids/{bid}/edit', [BidModerationController::class,'edit'])->name('admin.bids.edit');

// Update bid
Route::post('/bids/{bid}/update', [BidModerationController::class,'update'])->name('admin.bids.update');

// Force assign bid to project
Route::post('/bids/{bid}/assign', [BidModerationController::class,'forceAssign'])->name('admin.bids.assign');



// Disputes actions
Route::post('/disputes/{dispute}/request-evidence', [DisputeActionController::class,'requestEvidence'])->name('admin.disputes.request_evidence');
Route::post('/disputes/{dispute}/issue-ruling', [DisputeActionController::class,'issueRuling'])->name('admin.disputes.issue_ruling');
Route::post('/disputes/{dispute}/freeze-project', [DisputeActionController::class,'freezeProject'])->name('admin.disputes.freeze_project');
Route::post('/disputes/{dispute}/unfreeze-project', [DisputeActionController::class,'unfreezeProject'])->name('admin.disputes.unfreeze_project');
