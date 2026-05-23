<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->input('admin') || $this->input('employee')) {
            return [
                'username' => ['required', 'string'],
                'password' => ['required', 'string'],
            ];
        }

        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $useUsername = $this->input('admin') || $this->input('employee');

        if ($useUsername) {
            $credentials = $this->only('username', 'password');
        } else {
            $login = $this->string('login');
            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $credentials = ['email' => $login, 'password' => $this->input('password')];
            } else {
                $credentials = ['username' => $login, 'password' => $this->input('password')];
            }
        }

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            if ($useUsername && filter_var($this->string('username'), FILTER_VALIDATE_EMAIL)) {
                $altCredentials = ['email' => $this->string('username'), 'password' => $this->input('password')];
                if (Auth::attempt($altCredentials, $this->boolean('remember'))) {
                    RateLimiter::clear($this->throttleKey());
                    return;
                }
            }

            RateLimiter::hit($this->throttleKey());

            $field = $useUsername ? 'username' : 'login';
            throw ValidationException::withMessages([
                $field => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $field = $this->input('admin') || $this->input('employee') ? 'username' : 'login';

        throw ValidationException::withMessages([
            $field => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $field = $this->input('admin') || $this->input('employee') ? $this->string('username') : $this->string('login');
        return Str::transliterate(Str::lower($field).'|'.$this->ip());
    }
}
