<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function actingAsAdmin()
    {
        return $this->actingAsUser(factory(User::class, 'admin')->create());
    }

    protected function actingAsUser($user)
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . JWTAuth::fromUser($user)
        ]);
    }
}
