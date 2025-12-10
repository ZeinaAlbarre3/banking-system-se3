<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\Enums\OtpActionTypeEnum;
use App\Domains\Auth\Notifications\PasswordResetOtpNotification;
use App\Domains\Auth\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use Random\RandomException;

readonly class SendOtpAction
{

    public function __construct(private UserRepository $userRepository)
    {

    }

    public function execute(string $email,string $action): void
    {
        $user = $this->userRepository->findByEmail($email);

//        $otp = random_int(100000, 999999);
        $otp = 123456;

        if($action == OtpActionTypeEnum::REGISTER->value)  $cacheKey = "register_otp_" . $email;
        else $cacheKey = "password_reset_otp_" . $email;

        Cache::put($cacheKey, $otp ,now()->addMinutes(config('cacheSystem.expired.default',10)));

        $user->notify(new PasswordResetOtpNotification($otp));

    }
}
