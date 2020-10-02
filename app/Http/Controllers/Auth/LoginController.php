<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\AdminLoginOtp;
use Session;
use Auth;
use App\Models\User;

class LoginController extends Controller
{
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


    protected $redirectTo = '/admin/home';

    public function index(){
        if (Auth::viaRemember()) {
            return redirect()->route('admin/home');
        }

        return  view('auth.login');
    }

    public function login(Request $request){

        $user = User::whereEmail($request->email)->whereStatus(1)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
        
            Session::flash('login_error', 'User name and password not matched !'); 
            Session::flash('alert-class', 'alert alert-danger alert-dismissible fade show'); 
            if(isset($request->auth_guard) && $request->auth_guard==3){
                return redirect('/');
            }else{
                return redirect('/admin');
            }
            
        }


        Auth::guard(GUARDS[$user->user_type])->login($user, $request->remember_me=='on');
        if(Auth::guard('admin')->check()){
             return redirect('/admin/home');
        }else{
            return redirect('/user/dashboard');
        }
    }

    public function verifyOtpLogin(Request $request){

        $otp    = (int) implode("",$request->otp_inp);
        $user   = User::whereEmail($request->email)->whereStatus(1)->where('user_type','=', 1)->first();

        if ($user) {
            
            if($otp === $user->login_otp){

                $user->login_otp = NULL;
                $user->save();

                Auth::guard(GUARDS[$user->user_type])->login($user, $request->remember_me=='on');
                return response()->json([
                    "status" => true,
                    "message" => "success."
                ], 200);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => "Wrong OTP. Please check your OTP."
                ], 200);
            }
        }else{
            return response()->json([
                    "status" => false,
                    "message" => "Something went wrong.Please try again later."
                ], 200);
        }
    }

    

    public function validateAdminUser(Request $request){

        $user = User::whereEmail($request->email)->whereStatus(1)->where('user_type','=', 1)->first();
        if ($user) {
                        
            if (! \Hash::check($request->password, $user->password)) {
                return response()->json([
                    "status" => false,
                    "message" => "Invalid password.Please try again."
                ], 200);
            }

            $digits = 5;
            $otp    = rand(pow(10, $digits-1), pow(10, $digits)-1);
            $details = ['email'=> $request->email,'otp'=> $otp];

            $user->login_otp =  $otp;
            $user->save();

            /* send email here for OTP to admin user */
            /*Mail::to($request->email)->send(new AdminLoginOtp([
                'otp'  =>  $otp,
                'subject'  =>  "Kudos API - OTP",
            ]));*/

            return response()->json([
                    "status" => true,
                    "data"=> $details,
                    "message" => "success."
                ], 200);
        } else {
            return response()->json([
                    "status" => false,
                    "message" => "You email address is wrong."
                ], 200);
        }

        if(Auth::guard('admin')->check()){
            return redirect('/admin/home');
        }
    }
    

    public function logout(Request $request){
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
            $request->session()->flush();
            $request->session()->regenerate();
             return redirect('/admin');
        }else{
            Auth::guard('user')->logout();
            $request->session()->flush();
            $request->session()->regenerate();
             return redirect('/');
        } 
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
