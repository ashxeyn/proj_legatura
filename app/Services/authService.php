<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class authService
{
    // Generate a 6-digit OTP
    public function generateOtp()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    // Hash the OTP for storage
    public function hashOtp($otp)
    {
        return Hash::make($otp);
    }

    // Verify OTP
    public function verifyOtp($inputOtp, $hashedOtp)
    {
        return Hash::check($inputOtp, $hashedOtp);
    }

    // Hash password
    public function hashPassword($password)
    {
        return Hash::make($password);
    }

    // Verify password
    public function verifyPassword($inputPassword, $hashedPassword)
    {
        return Hash::check($inputPassword, $hashedPassword);
    }

    // Send OTP via email (simple implementation)
    public function sendOtpEmail($email, $otp)
    {
        // Log OTP for debugging
        \Log::info("OTP for {$email}: {$otp}");

        // Send email
        try {
            \Mail::raw("Your OTP code is: {$otp}\n\nThis code will expire soon. Please do not share this code with anyone.", function($message) use ($email) {
                $message->to($email)
                        ->subject('Legatura - Your OTP Code');
            });
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email to {$email}: " . $e->getMessage());
            return false;
        }
    }

    // Attempt login for regular users (contractors/property_owners)
    public function attemptUserLogin($username, $password)
    {
        $user = DB::table('users')
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if ($user && $this->verifyPassword($password, $user->password_hash)) {
            if ($user->is_active) {
                return [
                    'success' => true,
                    'user' => $user,
                    'userType' => 'user'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Account is inactive'
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Invalid credentials'
        ];
    }

    // Attempt login for admin users
    public function attemptAdminLogin($username, $password)
    {
        $admin = DB::table('admin_users')
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if ($admin && $this->verifyPassword($password, $admin->password_hash)) {
            if ($admin->is_active) {
                return [
                    'success' => true,
                    'user' => $admin,
                    'userType' => 'admin'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Admin account is inactive'
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Invalid credentials'
        ];
    }

    // Unified login attempt
    public function login($username, $password)
    {
        // Try user login first
        $userLogin = $this->attemptUserLogin($username, $password);
        if ($userLogin['success']) {
            return $userLogin;
        }

        // Try admin login
        $adminLogin = $this->attemptAdminLogin($username, $password);
        if ($adminLogin['success']) {
            return $adminLogin;
        }

        return [
            'success' => false,
            'message' => 'Invalid username or password'
        ];
    }

    // Validate password strength
    public function validatePasswordStrength($password)
    {
        // Min 8 chars, at least 1 uppercase, 1 number, 1 special character
        if (strlen($password) < 8) {
            return [
                'valid' => false,
                'message' => 'Password must be at least 8 characters'
            ];
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Password must contain at least one uppercase letter'
            ];
        }

        if (!preg_match('/[0-9]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Password must contain at least one number'
            ];
        }

        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return [
                'valid' => false,
                'message' => 'Password must contain at least one special character'
            ];
        }

        return ['valid' => true];
    }

    // Calculate age from date of birth
    public function calculateAge($dateOfBirth)
    {
        $dob = new \DateTime($dateOfBirth);
        $now = new \DateTime();
        $age = $now->diff($dob)->y;
        return $age;
    }
}
