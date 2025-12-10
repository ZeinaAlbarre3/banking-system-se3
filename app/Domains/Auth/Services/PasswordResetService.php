<?php

namespace App\Domains\Auth\Services;


use App\Domains\Auth\Actions\SendOtpAction;
use App\Domains\Auth\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class PasswordResetService
{
    public function __construct(
        protected SendOtpAction  $sendOtpAction,
        protected UserRepository $userRepository,
    ){}

    /**
     * @throws ValidationException
     */
    public function resetPassword(array $data): string
    {
        $tokenCacheKey = 'password_reset_token_' . $data['email'];
        $cachedToken = Cache::get($tokenCacheKey);

        if (!$cachedToken || $cachedToken !== $data['reset_token']) {
            throw ValidationException::withMessages([
                'reset_token' => ['The reset token is invalid or has expired.'],
            ]);
        }

        $user = $this->userRepository->findByEmail($data['email']);
        $user->password = $data['password'];
        $user->save();

        Cache::forget($tokenCacheKey);
        return $user->createToken('auth_token')->plainTextToken;
    }
}
