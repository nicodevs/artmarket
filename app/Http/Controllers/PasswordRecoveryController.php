<?php

namespace App\Http\Controllers;

use App\User;
use App\Email;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PasswordRecoveryController extends Controller
{
    /**
     * Starts the password recovery process.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @param \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user, Email $email)
    {
        $data = $request->validate([
            'email' => 'required|email'
        ]);

        $user = $user->where('email', '=', $data['email'])->first();
        if (!$user) {
            throw new InvalidCredentialsException;
        }

        $token = JWTAuth::fromUser($user);
        $email->composePasswordRecoveryEmail($user, $token);

        return [
            'data' => ['email' => $user->email],
            'success' => true
        ];
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return [
            'data' => $user,
            'success' => true
        ];
    }
}
