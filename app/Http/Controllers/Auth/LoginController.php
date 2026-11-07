<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\AdminLoginOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Generate 6-digit OTP code valid for 10 minutes
        $otp = $user->generateTwoFactorCode();

        // Store intermediate authentication state in session
        $request->session()->put('auth.2fa', [
            'user_id' => $user->id,
            'remember' => $request->boolean('remember'),
            'ip' => $request->ip(),
        ]);

        // Dispatch OTP via Laravel SMTP
        $mailSuccess = false;
        try {
            Mail::to($user->email)->send(new AdminLoginOtpMail($user, $otp, $request->ip()));
            $mailSuccess = true;
            Log::info("Two-Step Verification OTP successfully sent to {$user->email} for user ID {$user->id}");
        } catch (\Throwable $e) {
            Log::error("Failed sending Two-Step Verification OTP to {$user->email}: " . $e->getMessage(), [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        $statusMsg = $mailSuccess 
            ? 'A 6-digit security code has been sent to your email.'
            : 'Security code generated, but there was an issue delivering the email (SMTP error). Please check your mail settings or click "Resend Code".';

        return redirect()->route('login.otp')->with($mailSuccess ? 'status' : 'error', $statusMsg);
    }

    public function showOtpForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        $twoFactorData = $request->session()->get('auth.2fa');
        if (! $twoFactorData || empty($twoFactorData['user_id'])) {
            return redirect()->route('login');
        }

        $user = User::find($twoFactorData['user_id']);
        if (! $user) {
            $request->session()->forget('auth.2fa');
            return redirect()->route('login');
        }

        return view('auth.verify_otp', [
            'user' => $user,
            'maskedEmail' => $user->masked_email,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.size' => 'The verification code must be exactly 6 digits.',
        ]);

        $twoFactorData = $request->session()->get('auth.2fa');
        if (! $twoFactorData || empty($twoFactorData['user_id'])) {
            return redirect()->route('login')->with('error', 'Your verification session has expired. Please log in again.');
        }

        $user = User::find($twoFactorData['user_id']);
        if (! $user) {
            $request->session()->forget('auth.2fa');
            return redirect()->route('login');
        }

        if (! $user->isTwoFactorCodeValid($request->code)) {
            $isExpired = $user->two_factor_expires_at && now()->gt($user->two_factor_expires_at);
            $msg = $isExpired 
                ? 'Your verification code has expired. Please click "Resend Code" to receive a new one.'
                : 'Invalid verification code. Please check your inbox and try again.';

            return back()->withInput()->withErrors(['code' => $msg]);
        }

        // Code is valid! Clear OTP and intermediate 2FA session
        $user->resetTwoFactorCode();
        $remember = (bool) ($twoFactorData['remember'] ?? false);
        $request->session()->forget('auth.2fa');

        // Log the user in
        Auth::login($user, $remember);
        $request->session()->regenerate();

        // When "Remember Me" is checked: preserve session for 7 days (10,080 minutes)
        if ($remember) {
            $recallerName = Auth::getRecallerName();
            $recallerValue = $user->getAuthIdentifier() . '|' . $user->getRememberToken() . '|' . $user->getAuthPassword();
            // 7 days = 10080 minutes
            Cookie::queue($recallerName, $recallerValue, 10080);
        }

        Log::info("User {$user->email} (ID: {$user->id}) successfully completed Two-Step Verification (Remember: " . ($remember ? 'Yes' : 'No') . ")");

        return redirect()->intended(route('admin.dashboard'));
    }

    public function resendOtp(Request $request)
    {
        $twoFactorData = $request->session()->get('auth.2fa');
        if (! $twoFactorData || empty($twoFactorData['user_id'])) {
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        $user = User::find($twoFactorData['user_id']);
        if (! $user) {
            return redirect()->route('login');
        }

        $otp = $user->generateTwoFactorCode();

        try {
            Mail::to($user->email)->send(new AdminLoginOtpMail($user, $otp, $request->ip()));
            Log::info("Resent Two-Step Verification OTP to {$user->email}");
            return back()->with('status', 'A fresh 6-digit verification code has been sent to your email.');
        } catch (\Throwable $e) {
            Log::error("Failed resending 2FA OTP to {$user->email}: " . $e->getMessage());
            return back()->with('error', 'Unable to send verification code. Please try again in a few moments.');
        }
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been signed out successfully.');
    }
}
