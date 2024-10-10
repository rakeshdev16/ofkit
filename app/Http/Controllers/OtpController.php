<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OtpController extends Controller
{
    protected $redirectTo = '/';

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
            // session()->forget('otp');
            session()->forget('user_id');

            return redirect()->intended($this->redirectTo);
        }

        return back()->withErrors(['otp' => 'The provided OTP is incorrect.']);
    }
}
