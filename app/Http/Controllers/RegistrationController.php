<?php

namespace App\Http\Controllers;

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
        ]);
        $data['password'] = bcrypt($data['password']);

        $user = $user->create($data);
        $token = JWTAuth::fromUser($user);

        return [
            'data' => array_merge($user->toArray(), ['token' => $token]),
            'success' => true
        ];
    }
}
