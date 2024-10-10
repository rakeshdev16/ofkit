<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Services\TextMeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LoginController extends Controller
{

    protected $textMeService;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TextMeService $textMeService)
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');

        $this->textMeService = $textMeService;
    }

    protected function authenticated(Request $request, $user)
    {
        if (!Auth::user()->hasRole('admin')) {
            session(['user_id' => $user->id]);
            $this->sendOtp($user);
            Auth::logout();
            return redirect()->route('otp.verify');
        }

        return redirect($this->redirectTo);
    }


    public function sendOtp($user)
    {
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);  // OTP valid for 10 minutes
        $user->save();
        Log::info($otp);
        $mobileNumber = $user->telephone;
        $message = "לכניסה למערכת אופקית קוד האימות שלך הוא: $otp נא לא לשתף את הקוד עם אחרים.";
        // session(['otp' => $otp]);
        $response = $this->textMeService->sendMessage($mobileNumber, $message);
        return $response;
    }
}
