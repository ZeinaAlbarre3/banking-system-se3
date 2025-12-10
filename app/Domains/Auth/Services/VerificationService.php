<?php

namespace App\Domains\Auth\Services;


use App\Domains\Auth\Actions\SendOtpAction;
use App\Domains\Auth\Enums\OtpActionTypeEnum;
use App\Domains\Auth\Http\Requests\ForgotPasswordRequest;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class VerificationService
{
    public function __construct(
        protected UserRepository $userRepository,
    ){}

    /**
     * Verify OTP for email verification (after register).
     */
    public function verifyRegisterOtp(array $data): User
    {
        $user = $this->userRepository->findByEmail($data['email']);

        $otpCacheKey = $this->buildOtpCacheKey($data['email'], OtpActionTypeEnum::REGISTER);
        $this->assertValidOtp($otpCacheKey, $data['otp']);

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        return $user;
    }

    /**
     * Verify OTP for password reset and create reset token.
     */
    public function verifyResetOtp(array $data): string
    {
        $this->userRepository->findByEmail($data['email']);

        $otpCacheKey = $this->buildOtpCacheKey($data['email'], OtpActionTypeEnum::RESET);
        $this->assertValidOtp($otpCacheKey, $data['otp']);

        return $this->createResetToken($data['email']);
    }

    private function buildOtpCacheKey(string $email, OtpActionTypeEnum $action): string
    {
        return match ($action) {
            OtpActionTypeEnum::REGISTER  => 'register_otp_' . $email,
            OtpActionTypeEnum::RESET => 'password_reset_otp_' . $email,
        };
    }

    /**
     * @throws ValidationException
     */
    private function assertValidOtp(string $otpCacheKey, string|int $providedOtp): void
    {
        $cachedOtp = Cache::get($otpCacheKey);

        if (!$cachedOtp || (string)$cachedOtp !== (string)$providedOtp) {
            throw ValidationException::withMessages([
                'otp' => ['The provided OTP is invalid or has expired.'],
            ]);
        }

        Cache::forget($otpCacheKey);
    }

    private function createResetToken(string $email): string
    {
        $resetToken   = Str::random(60);
        $tokenCacheKey = 'password_reset_token_' . $email;

        Cache::put($tokenCacheKey, $resetToken, now()->addMinutes(10));

        return $resetToken;
    }
}
