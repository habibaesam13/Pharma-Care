<?php
namespace App\Http\Controllers\Api;

use App\Models\Otp;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return ApiResponse::unauthorized('Invalid credentials');
        }

        $user  = User::where('email', $credentials['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ], 'Login successful');
    }

    
    public function signup(UserRequest $request)
    {
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'user'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer',
        ], 'Signup successful');
    }

    
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ApiResponse::success(null, 'Logged out successfully');
    }

    private function generateOtp($length=4){
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $otp;
    }
    public function sendOtp(Request $request){
        $email=$request->validate(['email' => 'required|string|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();

    $otpCode = $this->generateOtp(4);

    Otp::create([
        'user_id'    => $user->id,
        'otp'        => $otpCode,
        'type'       => 'password_reset',
        'expires_at' => Carbon::now()->addMinutes(15),
    ]);

    // Send via Mail
    Mail::raw("Your login OTP is: $otpCode", function ($message) use ($request) {
            $message->to($request->email)->subject('Your Login OTP');
        });

    return ApiResponse::success(null, 'OTP sent to your email');
    }


    public function resetPassword(Request $request)
{
    $validated = $request->validate([
        'email'        => 'required|string|email|exists:users,email',
        'otp'          => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::where('email', $request->email)->first();

    $otp = Otp::where('user_id', $user->id)
              ->where('otp', $request->otp)
              ->where('expires_at', '>', now())
              ->first();

    if (!$otp) {
        return ApiResponse::error('Invalid or expired OTP', 422);
    }

    // Update password
    $user->update([
        'password' => Hash::make($request->new_password),
    ]);

    $otp->delete();

    return ApiResponse::success(null, 'Password has been reset successfully');
}

}
