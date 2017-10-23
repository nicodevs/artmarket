<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegistrationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'username' => 'sometimes|string',
        ]);
        $data['password'] = bcrypt($data['password']);

        if (!isset($data['username'])) {
            $data['username'] = $user->createUsername($data['email']);
        }

        if (isset($data['name'])) {
            $data = $user->splitName($data);
        }

        $user = $user->create($data);
        $token = JWTAuth::fromUser($user);

        event(new UserRegistered($user));

        return [
            'data' => array_merge($user->toArray(), ['token' => $token]),
            'success' => true
        ];
    }
}
