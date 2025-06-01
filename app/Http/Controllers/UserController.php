<?php
namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // Registration
    public function userRegistration(Request $request)
    {
        try {
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
        // return view('pages.auth.loginpage');
    }

    // Logout
    public function logout()
    {
        return response()->json([
            'status'  => 'success',
            'message' => 'User Logout successful',
        ])->cookie('token', null, -1);
    }

    // Send OTP
    public function sendOTP(Request $request)
    {
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
                'status'  => 'failed',
                'message' => 'Unable to send OTP',
            ], 200);
        }
        // return view('pages.auth.sendotp');
    }

    // Verify OTP
    public function verifyOTP(Request $request)
    {
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
        // return view('pages.auth.verifyotp');
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        try {
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
                'message' => 'Unable to reset password',
            ]);

        }
        // return view('pages.auth.resetpassword');
    }
    // get user profile
    public function profilePage()
    {

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
        // return view('pages.dashboard.profile');
    }

    // Update user profile
    public function updateProfile(Request $request)
    {
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
    }
}
