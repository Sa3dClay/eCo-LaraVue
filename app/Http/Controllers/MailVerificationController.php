<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class MailVerificationController extends Controller
{
    public function emailVerify()
    {
        return response()->json([
            'message' => 'Please verify your email address'
        ], 200);
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        // Verifying
        $request->fulfill();

        $user_verify_date = Auth::user()->email_verified_at;

        return response()->json([
            'message' => 'Email verified successfully',
            'user_verify_date' => $user_verify_date
        ], 200);
    }

    public function resendEmailVerify(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link sent!'
        ], 200);
    }
}
