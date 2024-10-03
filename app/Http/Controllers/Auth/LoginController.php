<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Services\TextMeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        // Assuming there's a 'role' field, or adapt this to your role-checking logic
        if (!Auth::user()->hasRole('admin')) {
            // For non-admin users, send OTP and logout to wait for OTP verification
            session(['user_id' => $user->id]);  // Store user ID in session
            $this->sendOtp($user);  // Send OTP to the user's phone
            Auth::logout();  // Log out the user temporarily
            return redirect()->route('otp.verify');  // Redirect to OTP verification page
        }

        // For admin users, just allow the login as usual
        return redirect($this->redirectTo);
    }


    public function sendOtp($user)
    {
        $otp = rand(100000, 999999);
        Log::info($otp);
        $mobileNumber = $user->telephone;
        $message = "לכניסה למערכת אופקית קוד האימות שלך הוא: $otp נא לא לשתף את הקוד עם אחרים.";
        session(['otp' => $otp]);
        $response = $this->textMeService->sendMessage($mobileNumber, $message);
        return $response;
    }
}
