<?php

namespace App\Domains\Auth\Http\Controllers;

use App\Domains\Auth\Actions\SendOtpAction;
use App\Domains\Auth\Enums\OtpActionTypeEnum;
use App\Domains\Auth\Http\Requests\LoginRequest;
use App\Domains\Auth\Http\Requests\RefreshCodeRequest;
use App\Domains\Auth\Http\Requests\RegisterRequest;
use App\Domains\Auth\Http\Requests\VerifyOtpRequest;
use App\Domains\Auth\Http\Resources\UserResource;
use App\Domains\Auth\Repositories\UserRepository;
use App\Domains\Auth\Services\AuthService;
use App\Domains\Auth\Services\PasswordResetService;
use App\Domains\Auth\Data\RegisterUserData;
use App\Domains\Auth\Services\VerificationService;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function __construct(
        protected UserRepository       $userRepository,
        protected SendOtpAction        $sendOtpAction,
        protected PasswordResetService $passwordResetService,
        protected VerificationService  $verificationService,
        protected AuthService           $authService
    )
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $userData = RegisterUserData::from($request->validated());

        $this->authService->register($userData);

        return self::Success(msg:'Code Sent Successfully. Please check your inbox.');
    }

    public function verifyRegisterOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = $this->verificationService->verifyRegisterOtp($request->validated());

        $token = $user->createToken('user-auth-token')->plainTextToken;

        return self::SuccessWithToken($token,new UserResource($user),'User Registered Successfully');
    }

    public function refreshCode(RefreshCodeRequest $request): JsonResponse
    {
        $this->sendOtpAction->execute($request['email'],$request['action']);

        return self::Success(msg:'Code Refreshed Successfully.');
    }

    /**
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $request->authenticate();
        $token = $user->createToken('user-auth-token')->plainTextToken;

        return self::SuccessWithToken($token, new UserResource($user), 'User Logged In Successfully');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return self::Success(msg: 'User logged out successfully');
    }

    public function me(Request $request): JsonResponse
    {
        return self::Success(new UserResource($request->user()));
    }
}
