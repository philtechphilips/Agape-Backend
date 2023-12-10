<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class MobileAuth extends Controller
{
    function store (Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        Log::info($request->password);
        Log::info($user->password);
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'email' => ['The provided credentials are incorrect.'],
            ], 422);
        }

        return ['user' => $user, 'token' => $user->createToken($request->device_name)->plainTextToken];
    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json();
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return ['user' => $user, 'token' => $user->createToken($request->device_name)->plainTextToken];
    }
}


