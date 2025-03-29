<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function register(RegisterRequest $request):JsonResponse
    {
        try {
            $data = $this->authService->register($request->validated());
            return $this->returnData($data ,'Registration successful',201);
        }
        catch (\Exception $exception){
            return $this->errorMessage($exception->getMessage(),$exception->getCode());
        }
    }

    public function login(LoginRequest $request):JsonResponse
    {
        try {
            $data = $this->authService->login($request->validated());
            return $this->returnData($data ,'You have successfully logged in.',200);
        }
        catch (\Exception $exception){
            return $this->errorMessage($exception->getMessage(),$exception->getCode());
        }
    }

    public function logout():JsonResponse
    {
        $this->authService->logout();
        return $this->successMessage('You have successfully logged out.',200);
    }

}
