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
        if ($user->otp == $request->otp) {
            if ($user->otp_expires_at->isFuture()) {
                $user->otp = null;
                $user->otp_expires_at = null;
                $user->save();

                return redirect()->route('dashboard');
            } else {
                return back()->withErrors(['otp' => __('login.otpExpired')]);
            }
        } else {
            return back()->withErrors(['otp' => __('login.invalidOtp')]);
        }
    }
}
