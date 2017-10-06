<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class SessionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            throw new InvalidCredentialsException;
        }

        return [
            'data' => array_merge(auth()->user()->toArray(), ['token' => $token]),
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
