<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Web\ProjectWebController;

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

// Project Posting Routes (Property Owner)
Route::get('/property-owner/post-project', [ProjectWebController::class, 'ownerForm'])->name('projects.create');
Route::post('/property-owner/post-project', [ProjectWebController::class, 'storeOwner'])->name('projects.storeOwner');
Route::get('/projects/{id}', [ProjectWebController::class, 'show'])->name('projects.show');

// Project Posting Routes (Contractor)
Route::get('/contractor/post-project', [ProjectWebController::class, 'contractorForm'])->name('contractorProjects.create');
Route::post('/contractor/post-project', [ProjectWebController::class, 'storeContractor'])->name('contractorProjects.store');
Route::get('/contractor/projects/{id}', [ProjectWebController::class, 'showContractor'])->name('contractorProjects.show');

