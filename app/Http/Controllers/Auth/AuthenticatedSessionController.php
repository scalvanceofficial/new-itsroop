<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('Admin.Auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            // Check if user is admin or employee (has access to admin portal)
            if ($user->type == 'admin' || $user->type == 'employee') {
                $otp = rand(100000, 999999);
                $user->update([
                    'otp' => $otp,
                    'otp_expires_at' => now()->addMinutes(5),
                ]);

                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($otp));

                // Store email in session for verification
                session(['admin_login_email' => $user->email]);

                return redirect()->route('admin.otp.verify');
            }
        }

        // Fallback to default authentication or show error
        $request->authenticate();
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function showOtpVerify()
    {
        if (!session('admin_login_email')) {
            return redirect()->route('login');
        }
        return view('Admin.Auth.otp-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $email = session('admin_login_email');
        $user = \App\Models\User::where('email', $email)->first();

        if ($user && $user->otp == $request->otp && now()->lt($user->otp_expires_at)) {
            $user->update(['otp' => null, 'otp_expires_at' => null]);
            Auth::login($user);
            $request->session()->forget('admin_login_email');
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
