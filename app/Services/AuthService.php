<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService extends BaseService
{
    public function __construct(protected AuthRepository $authRepository)
    {
        parent::__construct($authRepository);
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->storeResource($data);
        return [
            'user' => new UserResource($user),
            'token' => JWTAuth::fromUser($user)
        ];
    }

    public function login(array $credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new \Exception('Invalid credentials',401);
        }
        return [
            'user' => new UserResource(\auth()->user()),
            'token' => $token,
        ];
    }

    public function logout()
    {
       return JWTAuth::invalidate(JWTAuth::getToken());
    }

}
