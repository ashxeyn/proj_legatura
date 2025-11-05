<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\authService;
use App\Services\psgcApiService;
use App\Models\accounts\accountClass;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class authController extends Controller
{
    protected $authService;
    protected $accountClass;
    protected $psgcService;

    public function __construct()
    {
        $this->authService = new authService();
        $this->accountClass = new accountClass();
        $this->psgcService = new psgcApiService();
    }

    // Validate ID Number Format based on ID Type
    private function validateIdNumberFormat($idType, $idNumber)
    {
        switch ($idType) {
            case 9: // National ID (PhilSys)
                if (!preg_match('/^\d{12}$/', $idNumber)) {
                    return 'National ID must be exactly 12 digits (numeric).';
                }
                break;
            case 4: // PhilHealth ID
                if (!preg_match('/^\d{12}$/', $idNumber)) {
                    return 'PhilHealth ID must be exactly 12 digits (numeric).';
                }
                break;
            case 3: // SSS ID/UMID
                if (!preg_match('/^\d{12}$/', $idNumber)) {
                    return 'SSS ID/UMID must be exactly 12 digits (numeric).';
                }
                break;
            case 2: // Driver's License
                if (!preg_match('/^[A-Za-z0-9]{12,15}$/', $idNumber)) {
                    return 'Driver\'s License must be 12-15 alphanumeric characters.';
                }
                break;
            case 1: // Philippine Passport
                if (!preg_match('/^[A-Za-z0-9]{7,9}$/', $idNumber)) {
                    return 'Philippine Passport must be 7-9 alphanumeric characters.';
                }
                break;
            default: // All Others
                if (!preg_match('/^[A-Za-z0-9]{8,15}$/', $idNumber)) {
                    return 'Valid ID must be 8-15 alphanumeric characters.';
                }
                break;
        }
        return 'success';
    }

    // Show login form
    public function showLoginForm()
    {
        return view('accounts.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $result = $this->authService->login($request->username, $request->password);

        if ($result['success']) {
            // Store user info in session
            Session::put('user', $result['user']);
            Session::put('userType', $result['userType']);

            // Redirect based on user type
            if ($result['userType'] === 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Welcome Admin!');
            } else {
                return redirect('/dashboard')->with('success', 'Welcome back!');
            }
        } else {
            return back()->withErrors(['login' => $result['message']])->withInput();
        }
    }

    // Show signup form
    public function showSignupForm()
    {
        // Get data for dropdowns
        $contractorTypes = $this->accountClass->getContractorTypes();
        $occupations = $this->accountClass->getOccupations();
        $validIds = $this->accountClass->getValidIds();
        $provinces = $this->psgcService->getProvinces();
        $picabCategories = $this->accountClass->getPicabCategories();

        return view('accounts.signup', compact('contractorTypes', 'occupations', 'validIds', 'provinces', 'picabCategories'));
    }

    // Handle role selection (Step 0)
    public function selectRole(Request $request)
    {
        $request->validate([
            'user_type' => 'required|in:contractor,property_owner'
        ]);

        Session::put('signup_user_type', $request->user_type);
        Session::put('signup_step', 1);

        return response()->json(['success' => true, 'user_type' => $request->user_type]);
    }

    // Handle Contractor Step 1: Company Info
    public function contractorStep1(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:200',
            'company_phone' => 'required|regex:/^09[0-9]{9}$/|size:11',
            'years_of_experience' => 'required|numeric|min:0',
            'contractor_type_id' => 'required|exists:contractor_types,type_id',
            'services_offered' => 'required|string',
            'business_address_street' => 'required|string',
            'business_address_barangay' => 'required|string',
            'business_address_city' => 'required|string',
            'business_address_province' => 'required|string',
            'business_address_postal' => 'required|string',
            'company_website' => 'nullable|string|max:255',
            'company_social_media' => 'nullable|string|max:255',
            'contractor_type_other_text' => 'nullable|string|max:200'
        ]);

        // Combine address fields
        $businessAddress = $request->business_address_street . ', ' .
                          $request->business_address_barangay . ', ' .
                          $request->business_address_city . ', ' .
                          $request->business_address_province . ' ' .
                          $request->business_address_postal;

        // Store in session
        Session::put('contractor_step1', [
            'company_name' => $request->company_name,
            'company_phone' => $request->company_phone,
            'years_of_experience' => $request->years_of_experience,
            'type_id' => $request->contractor_type_id,
            'contractor_type_other' => $request->contractor_type_other_text,
            'services_offered' => $request->services_offered,
            'business_address' => $businessAddress,
            'company_website' => $request->company_website,
            'company_social_media' => $request->company_social_media
        ]);

        Session::put('signup_step', 2);

        return response()->json(['success' => true, 'step' => 2]);
    }

    // Handle Contractor Step 2: Account Setup
    public function contractorStep2(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:50',
            'company_email' => 'required|email|max:100',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        // Check if username exists
        if ($this->accountClass->usernameExists($request->username)) {
            return response()->json([
                'success' => false,
                'errors' => ['username' => ['Username already exists']]
            ], 422);
        }

        // Check if email exists
        if ($this->accountClass->emailExists($request->company_email)) {
            return response()->json([
                'success' => false,
                'errors' => ['company_email' => ['Email already exists']]
            ], 422);
        }

        // Check if company email exists
        if ($this->accountClass->companyEmailExists($request->company_email)) {
            return response()->json([
                'success' => false,
                'errors' => ['company_email' => ['Company email already exists']]
            ], 422);
        }

        // Validate password strength
        $passwordValidation = $this->authService->validatePasswordStrength($request->password);
        if (!$passwordValidation['valid']) {
            return response()->json([
                'success' => false,
                'errors' => ['password' => [$passwordValidation['message']]]
            ], 422);
        }

        // Generate and send OTP
        $otp = $this->authService->generateOtp();
        $otpHash = $this->authService->hashOtp($otp);
        $this->authService->sendOtpEmail($request->company_email, $otp);

        // Store in session
        Session::put('contractor_step2', [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'company_email' => $request->company_email,
            'password' => $request->password,
            'otp_hash' => $otpHash
        ]);

        Session::put('signup_step', 3);

        return response()->json(['success' => true, 'step' => 3, 'message' => 'OTP sent to email']);
    }

    // Handle Contractor Step 3: Verification
    // Contractor Step 3: Verify OTP Only
    public function contractorVerifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $step2Data = Session::get('contractor_step2');

        // Verify OTP
        if (!$this->authService->verifyOtp($request->otp, $step2Data['otp_hash'])) {
            return response()->json([
                'success' => false,
                'errors' => ['otp' => ['Invalid OTP']]
            ], 422);
        }

        Session::put('signup_step', 4);

        return response()->json(['success' => true, 'step' => 4]);
    }

    // Contractor Step 4: Upload Verification Documents
    public function contractorStep4(Request $request)
    {
        $request->validate([
            'picab_number' => 'required|string|max:100|unique:contractors,picab_number',
            'picab_category' => 'required|string|max:100',
            'picab_expiration_date' => 'required|date|after:today',
            'business_permit_number' => 'required|string|max:100|unique:contractors,business_permit_number',
            'business_permit_city' => 'required|string|max:100',
            'business_permit_expiration' => 'required|date|after:today',
            'tin_business_reg_number' => 'required|string|max:100|unique:contractors,tin_business_reg_number',
            'dti_sec_registration_photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        // Handle file upload
        $dtiSecPath = $request->file('dti_sec_registration_photo')->store('DTI_SEC', 'public');

        // Store in session
        Session::put('contractor_step4', [
            'picab_number' => $request->picab_number,
            'picab_category' => $request->picab_category,
            'picab_expiration_date' => $request->picab_expiration_date,
            'business_permit_number' => $request->business_permit_number,
            'business_permit_city' => $request->business_permit_city,
            'business_permit_expiration' => $request->business_permit_expiration,
            'tin_business_reg_number' => $request->tin_business_reg_number,
            'dti_sec_registration_photo' => $dtiSecPath
        ]);

        Session::put('signup_step', 5);

        return response()->json(['success' => true, 'step' => 5]);
    }

    // Handle Contractor Final Step: Profile Picture
    public function contractorFinalStep(Request $request)
    {
        $request->validate([
            'profile_pic' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        $profilePicPath = null;
        if ($request->hasFile('profile_pic')) {
            $profilePicPath = $request->file('profile_pic')->store('profiles', 'public');
        }

        // Get all session data
        $step1 = Session::get('contractor_step1');
        $step2 = Session::get('contractor_step2');
        $step4 = Session::get('contractor_step4');

        // Create user
        $userId = $this->accountClass->createUser([
            'profile_pic' => $profilePicPath,
            'username' => $step2['username'],
            'email' => $step2['company_email'],
            'password_hash' => $this->authService->hashPassword($step2['password']),
            'OTP_hash' => $step2['otp_hash'],
            'user_type' => 'contractor'
        ]);

        // Create contractor
        $contractorId = $this->accountClass->createContractor([
            'user_id' => $userId,
            'company_name' => $step1['company_name'],
            'years_of_experience' => $step1['years_of_experience'],
            'type_id' => $step1['type_id'],
            'contractor_type_other' => $step1['contractor_type_other'] ?? null,
            'services_offered' => $step1['services_offered'],
            'business_address' => $step1['business_address'],
            'company_email' => $step2['company_email'],
            'company_phone' => $step1['company_phone'],
            'company_website' => $step1['company_website'],
            'company_social_media' => $step1['company_social_media'],
            'picab_number' => $step4['picab_number'],
            'picab_category' => $step4['picab_category'],
            'picab_expiration_date' => $step4['picab_expiration_date'],
            'business_permit_number' => $step4['business_permit_number'],
            'business_permit_city' => $step4['business_permit_city'],
            'business_permit_expiration' => $step4['business_permit_expiration'],
            'tin_business_reg_number' => $step4['tin_business_reg_number'],
            'dti_sec_registration_photo' => $step4['dti_sec_registration_photo']
        ]);

        // Create contractor user
        $this->accountClass->createContractorUser([
            'contractor_id' => $contractorId,
            'user_id' => $userId,
            'first_name' => $step2['first_name'],
            'middle_name' => $step2['middle_name'],
            'last_name' => $step2['last_name']
        ]);

        // Clear session
        Session::forget(['signup_user_type', 'signup_step', 'contractor_step1', 'contractor_step2', 'contractor_step4']);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Please wait for admin approval.'
        ]);
    }

    // Handle Property Owner Step 1: Personal Info
    public function propertyOwnerStep1(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'occupation_id' => 'required|exists:occupations,id',
            'date_of_birth' => 'required|date|before:today',
            'phone_number' => 'required|regex:/^09[0-9]{9}$/|size:11',
            'owner_address_street' => 'required|string|max:255',
            'owner_address_province' => 'required|string|max:100',
            'owner_address_city' => 'required|string|max:100',
            'owner_address_barangay' => 'required|string|max:100',
            'owner_address_postal' => 'required|string|max:10',
            'occupation_other_text' => 'nullable|string|max:200'
        ]);

        // Calculate age
        $age = $this->authService->calculateAge($request->date_of_birth);

        // Combine address into single string
        $address = $request->owner_address_street . ', ' .
                   $request->owner_address_barangay . ', ' .
                   $request->owner_address_city . ', ' .
                   $request->owner_address_province . ', ' .
                   $request->owner_address_postal;

        // Store in session
        Session::put('owner_step1', [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'occupation_id' => $request->occupation_id,
            'occupation_other' => $request->occupation_other_text,
            'date_of_birth' => $request->date_of_birth,
            'phone_number' => $request->phone_number,
            'age' => $age,
            'address' => $address
        ]);

        Session::put('signup_step', 2);

        return response()->json(['success' => true, 'step' => 2]);
    }

    // Handle Property Owner Step 2: Account Setup
    public function propertyOwnerStep2(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        // Check if username exists
        if ($this->accountClass->usernameExists($request->username)) {
            return response()->json([
                'success' => false,
                'errors' => ['username' => ['Username already exists']]
            ], 422);
        }

        // Check if email exists
        if ($this->accountClass->emailExists($request->email)) {
            return response()->json([
                'success' => false,
                'errors' => ['email' => ['Email already exists']]
            ], 422);
        }

        // Validate password strength
        $passwordValidation = $this->authService->validatePasswordStrength($request->password);
        if (!$passwordValidation['valid']) {
            return response()->json([
                'success' => false,
                'errors' => ['password' => [$passwordValidation['message']]]
            ], 422);
        }

        // Generate and send OTP
        $otp = $this->authService->generateOtp();
        $otpHash = $this->authService->hashOtp($otp);
        $this->authService->sendOtpEmail($request->email, $otp);

        // Store in session
        Session::put('owner_step2', [
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'otp_hash' => $otpHash
        ]);

        Session::put('signup_step', 3);

        return response()->json(['success' => true, 'step' => 3, 'message' => 'OTP sent to email']);
    }

    // Property Owner Step 3: Verify OTP Only
    public function propertyOwnerVerifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $step2Data = Session::get('owner_step2');

        // Verify OTP
        if (!$this->authService->verifyOtp($request->otp, $step2Data['otp_hash'])) {
            return response()->json([
                'success' => false,
                'errors' => ['otp' => ['Invalid OTP']]
            ], 422);
        }

        Session::put('signup_step', 4);

        return response()->json(['success' => true, 'step' => 4]);
    }

    // Property Owner Step 4: Upload Verification Documents
    public function propertyOwnerStep4(Request $request)
    {
        $request->validate([
            'valid_id_id' => 'required|exists:valid_ids,id',
            'valid_id_number' => 'required|string|max:100',
            'valid_id_photo' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'police_clearance' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        // Validate ID number format based on ID type
        $validationResult = $this->validateIdNumberFormat($request->valid_id_id, $request->valid_id_number);
        if ($validationResult !== 'success') {
            return response()->json([
                'success' => false,
                'errors' => ['valid_id_number' => [$validationResult]]
            ], 422);
        }

        // Handle file uploads
        $validIdPath = $request->file('valid_id_photo')->store('validID', 'public');
        $policeClearancePath = $request->file('police_clearance')->store('policeClearance', 'public');

        // Store in session
        Session::put('owner_step4', [
            'valid_id_id' => $request->valid_id_id,
            'valid_id_number' => $request->valid_id_number,
            'valid_id_photo' => $validIdPath,
            'police_clearance' => $policeClearancePath
        ]);

        Session::put('signup_step', 5);

        return response()->json(['success' => true, 'step' => 5]);
    }

    // Handle Property Owner Final Step: Profile Picture
    public function propertyOwnerFinalStep(Request $request)
    {
        $request->validate([
            'profile_pic' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        $profilePicPath = null;
        if ($request->hasFile('profile_pic')) {
            $profilePicPath = $request->file('profile_pic')->store('profiles', 'public');
        }

        // Get all session data
        $step1 = Session::get('owner_step1');
        $step2 = Session::get('owner_step2');
        $step4 = Session::get('owner_step4');

        // Create user
        $userId = $this->accountClass->createUser([
            'profile_pic' => $profilePicPath,
            'username' => $step2['username'],
            'email' => $step2['email'],
            'password_hash' => $this->authService->hashPassword($step2['password']),
            'OTP_hash' => $step2['otp_hash'],
            'user_type' => 'property_owner'
        ]);

        // Create property owner
        $this->accountClass->createPropertyOwner([
            'user_id' => $userId,
            'first_name' => $step1['first_name'],
            'middle_name' => $step1['middle_name'],
            'last_name' => $step1['last_name'],
            'phone_number' => $step1['phone_number'],
            'valid_id_id' => $step4['valid_id_id'],
            'valid_id_number' => $step4['valid_id_number'],
            'valid_id_photo' => $step4['valid_id_photo'],
            'police_clearance' => $step4['police_clearance'],
            'date_of_birth' => $step1['date_of_birth'],
            'age' => $step1['age'],
            'occupation_id' => $step1['occupation_id'],
            'occupation_other' => $step1['occupation_other'] ?? null,
            'address' => $step1['address']
        ]);

        // Clear session
        Session::forget(['signup_user_type', 'signup_step', 'owner_step1', 'owner_step2', 'owner_step4']);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! You can now login.'
        ]);
    }

    // Logout
    public function logout()
    {
        Session::flush();
        return redirect('/accounts/login')->with('success', 'Logged out successfully');
    }

    // PSGC API Endpoints

    // Get all provinces
    public function getProvinces()
    {
        $provinces = $this->psgcService->getProvinces();
        return response()->json($provinces);
    }

    // Get cities by province code
    public function getCitiesByProvince($provinceCode)
    {
        $cities = $this->psgcService->getCitiesByProvince($provinceCode);
        return response()->json($cities);
    }

    // Get barangays by city code
    public function getBarangaysByCity($cityCode)
    {
        $barangays = $this->psgcService->getBarangaysByCity($cityCode);
        return response()->json($barangays);
    }
}
