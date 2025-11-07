<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;

// Auth: issue token (for React Native)
Route::post('/auth/token', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'device_name' => ['required', 'string'],
    ]);

    $user = \App\Models\User::where('email', $request->string('email'))->first();

    if (! $user || ! \Illuminate\Support\Facades\Hash::check($request->string('password'), $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 422);
    }

    $token = $user->createToken($request->string('device_name'))->plainTextToken;
    return response()->json(['token' => $token]);
});

// Projects CRUD (protected)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class);
});


