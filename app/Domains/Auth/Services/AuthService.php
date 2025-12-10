<?php

namespace App\Domains\Auth\Services;


use App\Domains\Auth\Actions\SendOtpAction;
use App\Domains\Auth\Data\RegisterUserData;
use App\Domains\Auth\Enums\OtpActionTypeEnum;
use App\Domains\Auth\Http\Requests\ForgotPasswordRequest;
use App\Domains\Auth\Models\User;
use App\Domains\Auth\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class AuthService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected SendOtpAction $sendOtpAction,
    ){}

    public function register(RegisterUserData $data): void
    {
        $email = $data->email;

        $user = $this->userRepository->findByEmailOrNull($email);

        if ($user) {
            $this->userRepository->update($data, $user);
        }
        else{
            $this->userRepository->create($data);
        }

        $this->sendOtpAction->execute($email, OtpActionTypeEnum::REGISTER->value);
    }
}
