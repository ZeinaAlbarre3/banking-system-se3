<?php

namespace App\Domains\Auth\Http\Controllers;

use App\Domains\Auth\Actions\SendOtpAction;
use App\Domains\Auth\Enums\OtpActionTypeEnum;
use App\Domains\Auth\Http\Requests\ForgotPasswordRequest;
use App\Domains\Auth\Http\Requests\ResetPasswordRequest;
use App\Domains\Auth\Http\Requests\VerifyOtpRequest;
use App\Domains\Auth\Services\PasswordResetService;
use App\Domains\Auth\Services\VerificationService;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Random\RandomException;


class PasswordResetController extends Controller
{
    use ResponseTrait;
    public function __construct(
        protected SendOtpAction        $sendOtpAction,
        protected PasswordResetService $passwordResetService,
        protected VerificationService $verificationService,
    ){}

    /**
     * @throws RandomException
     */
    public function sendOtp(ForgotPasswordRequest $request): JsonResponse
    {
        $this->sendOtpAction->execute($request->input('email'),OtpActionTypeEnum::RESET->value);

        return self::Success(msg: 'Otp sent successfully to your email.');
    }

    /**
     * @throws ValidationException
     */
    public function verifyResetOtp(VerifyOtpRequest $request): JsonResponse
    {
        $resetToken = $this->verificationService->verifyResetOtp($request->validated());
        return self::Success(
            data: ['reset_token' => $resetToken],
            msg: __('Otp Verified successfully.')
        );
    }

    /**
     * @throws ValidationException
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $this->passwordResetService->resetPassword($request->validated());

        return self::Success(msg: 'Password Reset Successfully.');
    }
}
