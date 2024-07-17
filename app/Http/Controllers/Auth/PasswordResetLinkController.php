<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $response = $this->broker()->sendResetLink($request->only('email'));
        if ($response == Password::RESET_LINK_SENT) {
            return response()->json(['status' => true, 'message' => 'We have emailed your password reset link.']);
        }
        throw ValidationException::withMessages([
            'email' => 'We can not find a user with that email address.',
        ]);

    }

    protected function broker()
    {
        return Password::broker();
    }
}
