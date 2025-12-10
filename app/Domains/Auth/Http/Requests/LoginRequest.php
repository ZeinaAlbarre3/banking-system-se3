<?php

namespace App\Domains\Auth\Http\Requests;

use App\Domains\Auth\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => ['bail', 'required', 'email', 'exists:users,email', 'string'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws Exception
     */
    public function authenticate(): Authenticatable
    {
        $user = User::where('email', $this->validated()['email'])->first();

        if (!$user || !Hash::check($this->validated()['password'], $user->password) || $user->email_verified_at === null) {
            throw  new BadRequestHttpException('failed');
        }

        if ($user->banned_at !== null) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.banned')],
            ]);
        }
        return $user;
    }
}
