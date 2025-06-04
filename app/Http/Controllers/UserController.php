<?php
namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
     function userLoginPage():View{
        return view('pages.auth.loginpage');
    }

    function userRegistrationPage(){
        return view('pages.auth.register');
    }

    function SendOtpPage():View{
        return view('pages.auth.sendotp');
    }
    function VerifyOTPPage():View{
        return view('pages.auth.verifyotp');
    }

    function ResetPasswordPage():View{
        return view('pages.auth.passwordreset');
    }
    // Registration
    public function userRegistration(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'first_name' => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'email'      => 'required|email|unique:users,email',
                'mobile'     => 'required|string|max:15',
                'password'   => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => $validator->errors(),
                ], 422);
            }
            User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'mobile'     => $request->mobile,
                'password'   => $request->password,
            ]);
            return response()->json([
                'status'  => 'success',
                'message' => 'User Registration successful',
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'failed',
                'message' => 'User Registration failed',
            ], 200);
        }
        // return view('pages.auth.register');
    }

    // Login
    public function userLogin(Request $request)
    {
        try {

            $validator = Validator::make(request()->all(), [
                'email'    => 'required|email',
                'password' => 'required|string|min:3',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => $validator->errors(),
                ], 422);
            }

            $user_id = User::where(['email' => $request->email, 'password' => $request->password])->first();

            if ($user_id !== null) {
                // User Login
                $token = JWTToken::createToken($request->email, $user_id->id);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'User Login successful',
                ], 200)->cookie('token', $token, time() + 60 * 60 * 24);
            } else {
                return response()->json([
                    'status'  => 'failed',
                    'message' => 'User Login failed',
                ]);
            }
        } catch (\Throwable $e) {
              // log(error->getMessage);
            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], 200);
        }
        // return view('pages.auth.loginpage');
    }

    // Logout
    public function logout()
    {
        try {

            // return response()->json([
            //     'status'  => 'success',
            //     'message' => 'User Logout successful',
            // ])->cookie('token', null, -1);
            return redirect('/userLogin')->withCookie(cookie('token', null, -1));
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], 200);

        }
    }

    // Send OTP
    public function sendOTP(Request $request)
    {
        try {

            $validator = Validator::make(request()->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $email = $request->email;
            $otp   = rand(1000, 9999);
            $user  = User::where('email', $email)->first();

            if ($user !== null) {

                // Send OTP to the email address
                Mail::to($email)->send(new OTPMail($otp));

                //Save OTP to the database
                // dd(User::where('email', $email)->first());
                // $user->update(['otp' => $otp]);
                // $user->otp = $otp;
                // $user->save();
                User::where('email', $email)->update(['otp' => $otp]);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'OTP sent successfully',
                ], 200);

            } else {
                return response()->json([
                    'status'  => 'error',
                    'errors' => 'Cant send OTP, user not found',
                ], 422);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], 422);
        }
        // return view('pages.auth.sendotp');
    }

    // Verify OTP
    public function verifyOTP(Request $request)
    {
        try {

            $validator = Validator::make(request()->all(), [
                
                'otp'   => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => $validator->errors(),
                ], 422);
            }
            $email = $request->email;
            $otp   = $request->otp;

            $user = User::where(['email' => $email, 'otp' => $otp])->first();

            if ($user !== null) {
                //Update OTP To O
                User::where('email', $email)->update(['otp' => 0]);

                $token = JWTToken::createTokenForResetPassword($email);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'OTP verified successfully',
                ])->cookie('token', $token, time() + 60 * 60 * 24);
            } else {
                return response()->json([
                    'status'  => 'failed',
                    'message' => 'Unable to verify OTP',
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ], 200);
        }

        // return view('pages.auth.verifyotp');
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                
                'password' => 'required|string|min:3',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => $validator->errors(),
                ], 422);
            }

            $email    = $request->header('email');
            $password = $request->password;
            User::where('email', $email)->update(['password' => $password]);
            return response()->json([
                'status'  => 'success',
                'message' => 'Password Reset successfully',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'failed',
                'message' => $e->getMessage(),
            ] , 422);

        }
        // return view('pages.auth.resetpassword');
    }
                                    // get user profile
    public function profilePage()
    { // Fetch user profile based on email and user_id from headers
        try {
            $validator = Validator::make(request()->all(), [
                'email'   => 'required|email',
                'user_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => $validator->errors(),
                ], 422);
            }

            $email   = request()->header('email');
            $user_id = request()->header('user_id');
            $user    = User::where(['email' => $email, 'id' => $user_id])->first();

            if ($user) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'User profile fetched successfully',
                    'data'    => $user,
                ], 200);
            } else {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found',
                ], 404);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
        // return view('pages.dashboard.profile');
    }

    // Update user profile
    public function updateProfile(Request $request)
    {
        try { // Validate the request
            $validator = Validator::make(request()->all(), [
                'first_name' => 'sometimes|string|max:255',
                'last_name'  => 'sometimes|string|max:255',
                'email'      => 'sometimes|email|unique:users,email',
                'mobile'     => 'sometimes|string|max:15',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => $validator->errors(),
                ], 422);
            }
            // Fetch user profile based on email and user_id from headers
            $email   = request()->header('email');
            $user_id = request()->header('user_id');
            $user    = User::where(['email' => $email, 'id' => $user_id])->first();

            if ($user) {
                try {
                    $user->update([
                        'first_name' => $request->input('first_name') ?? $user->first_name,
                        'last_name'  => $request->input('last_name') ?? $user->last_name,
                        'email'      => $request->input('email') ?? $user->email,
                        'mobile'     => $request->input('mobile') ?? $user->mobile,
                    ]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'User profile updated successfully',
                    ], 200);
                } catch (\Throwable $e) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Unable to update user profile',
                    ], 500);
                }
            } else {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'User not found',
                ], 404);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
