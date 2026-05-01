<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signIn(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $otp = rand(100000, 999999);
        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(5),
            ]
        );

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($otp));

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to your email successfully.',
            'email' => $user->email
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 400);
        }

        if ($user->otp == $request->otp && now()->lt($user->otp_expires_at)) {
            // Clear OTP after successful verification
            $user->update(['otp' => null, 'otp_expires_at' => null]);

            if ($user->is_registered == 1) {
                Auth::login($user);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully.',
                'is_registered' => $user->is_registered,
                'user_id' => $user->id
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid or expired OTP'
        ], 400);
    }

    public function signUp(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email'
        ]);

        $user = User::find($request->user_id);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'is_registered' => 1
        ]);

        $data = [
            'subject' => 'Welcome to ' . config('app.name'),
            'name' => $user->full_name,
            'email' => $user->email,
        ];

        EmailService::sendEmail($user->email, 'emails.registration', $data);

        Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Registered successfully.',
        ]);
    }
}
