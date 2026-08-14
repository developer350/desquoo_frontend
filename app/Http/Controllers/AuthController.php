<?php

namespace App\Http\Controllers;

use App\Helpers\FrontendHelpers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Mail\OtpMail;
use App\Mail\WelcomeMail;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login()
    {
        $meta = FrontendHelpers::getPageDetails('login');

        return view('auth.login', compact('meta'));
    }

    public function loginAction(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
                'errors' => [
                    'email' => [
                        'User not found.',
                    ],
                ],
            ], 422);
        }

        if (! $user->status) {
            return response()->json([
                'status' => false,
                'message' => 'Your account is disabled. Please contact support.',
                'errors' => [
                    'email' => [
                        'Your account is disabled. Please contact support.',
                    ],
                ],
            ], 422);
        }

        $user->update([
            'otp' => rand(1000, 9999),
            'otp_sent_at' => now(),
        ]);

        session()->put([
            'email' => $user->email,
            'otp' => $user->otp,
            'otp_sent_at' => $user->otp_sent_at,
        ]);

        // send email
        defer(function () use ($user) {
            Mail::to($user->email)->send(new OtpMail($user));
        });

        return response()->json([
            'status' => true,
            'message' => 'Login successfully.',
            'showSuccess' => false,
            'url' => route('otp'),
        ]);
    }

    public function signup()
    {
        $meta = FrontendHelpers::getPageDetails('signup');

        return view('auth.signup', compact('meta'));
    }

    public function signupAction(SignupRequest $request)
    {
        // check the email is already exist or not
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return response()->json([
                'status' => false,
                'message' => 'Email already exist.',
                'errors' => [
                    'email' => [
                        'Email already exist.',
                    ],
                ],
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->email),
            'otp' => rand(1000, 9999),
            'otp_sent_at' => now(),
        ]);

        session()->put([
            'email' => $user->email,
            'otp' => $user->otp,
            'otp_sent_at' => $user->otp_sent_at,
        ]);

        // send email
        defer(function () use ($user) {
            Mail::to($user->email)->send(new OtpMail($user));
        });

        return response()->json([
            'status' => true,
            'message' => 'Register successfully.',
            'showSuccess' => false,
            'url' => route('otp'),
        ]);
    }

    public function otp()
    {
        $meta = FrontendHelpers::getPageDetails('otp');

        $email = session()->get('email');

        if (! $email) {
            return redirect()->route('login');
        }
        $parts = explode('@', $email);
        $localPart = $parts[0];
        $domain = $parts[1] ?? '';

        if (strlen($localPart) <= 2) {
            $maskedLocal = $localPart;
        } else {
            $maskedLocal =
                $localPart[0].str_repeat('*', strlen($localPart) - 2).substr($localPart, -1);
        }

        $maskedEmail = $maskedLocal.'@'.$domain;

        return view('auth.otp', compact('email', 'maskedEmail', 'meta'));
    }

    public function otpAction(Request $request)
    {
        $email = session()->get('email');
        $otp = session()->get('otp');
        $otp_sent_at = session()->get('otp_sent_at');

        if (! $email || ! $otp || ! $otp_sent_at) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
                'user_not_found' => true,
            ]);
        }

        if ($user->otp != $request->otp) {
            return response()->json([
                'status' => false,
                'message' => 'OTP is incorrect.',
                'errors' => [
                    'otp' => [
                        'OTP is incorrect.',
                    ],
                ],
            ], 422);
        }

        // check otp is expired or not
        if (now()->diffInMinutes($otp_sent_at) > 10) {
            return response()->json([
                'status' => false,
                'message' => 'OTP is expired.',
                'errors' => [
                    'otp' => [
                        'OTP is expired.',
                    ],
                ],
            ], 422);
        }

        $oldSessionId = session()->getId();

        Auth::login($user);

        $this->changeSessionCartIntoUserCart($oldSessionId);

        session()->forget(['email', 'otp', 'otp_sent_at']);

        if ($user->email_verified_at == null) {
            $user->email_verified_at = now();
            $user->save();

            defer(function () use ($user) {
                Mail::to($user->email)->send(new WelcomeMail($user));
            })->always();
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully.',
            'showSuccess' => false,
            'url' => route('my-account'),
        ]);
    }

    public function resendOtp()
    {
        $email = session()->get('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
                'user_not_found' => true,
            ]);
        }

        $user->otp = rand(1000, 9999);
        $user->otp_sent_at = now();
        $user->save();

        session()->put([
            'email' => $user->email,
            'otp' => $user->otp,
            'otp_sent_at' => $user->otp_sent_at,
        ]);

        // send email
        defer(function () use ($user) {
            Mail::to($user->email)->send(new OtpMail($user));
        })->always();

        return response()->json([
            'status' => true,
            'message' => 'OTP resend successfully.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        session()->forget(['email', 'otp', 'otp_sent_at']);

        return redirect()->route('login');
    }

    public function changeSessionCartIntoUserCart($oldSessionId)
    {
        Cart::where('session_id', $oldSessionId)->update([
            'session_id' => null,
            'user_id' => Auth::id(),
        ]);

        session()->forget('cart');
    }
}
