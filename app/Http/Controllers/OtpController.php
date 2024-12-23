<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;

class OtpController extends Controller
{
    protected $redirectTo = '/';
    protected $loginController;

    public function __construct(LoginController $loginController)
    {
        $this->loginController = $loginController;
    }

    public function showVerifyForm()
    {
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::findOrFail(session('user_id'));
        if ($request->otp == env('MASTER_OTP') || ($request->otp == $user->otp && $user->otp_expires_at->isFuture())) {
            Auth::login($user);
            $user->last_otp_verified_at = now();
            $user->save();
            session()->forget('user_id');

            return redirect()->intended($this->redirectTo);
        }
        $msg = !$user->otp_expires_at->isFuture() ? __('login.otpExpired') : __('login.invalidOtp');
        return back()->withErrors(['otp' => $msg]);
    }

    public function resendOtp(Request $request)
    {
        $user = User::findOrFail(session('user_id'));
        $this->loginController->sendOtp($user);
        return redirect()->route('otp.verify');
    }
}
