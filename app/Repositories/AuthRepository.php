<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository extends BaseRepository
{
    public function __construct(protected User $user)
    {
        parent::__construct($user);
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function login(array $credentials)
    {
        return JWTAuth::attempt($credentials);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refreshToken()
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    public function getAuthenticatedUser()
    {
        return auth()->user();
    }
}
