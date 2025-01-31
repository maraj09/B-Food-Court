<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Laravel\Firebase\Facades\Firebase;

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['sometimes', 'nullable', 'string'],
            'phone' => ['sometimes', 'nullable', 'string', 'phone:BD,IN'],
            'password' => ['required_if:email,!=,null', 'string'],
            'firebaseIdToken' => ['required_if:phone,!=,null', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password', 'phone', 'firebaseIdToken');
        $result = false;
        // Determine if the input is an email address
        if (isset($credentials['email'])) {
            // Determine if the input is an email address
            $isEmail = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) !== false;
        } else {
            // Handle the case where 'email' key is not present
            $isEmail = false;
        }

        // Attempt to authenticate using either email or phone
        if ($isEmail) {
            // For email-based authentication
            $result = Auth::attempt($credentials, $this->boolean('remember'));
        } else {
            $auth = Firebase::auth();
            try {
                $verifiedIdToken = $auth->verifyIdToken($credentials['firebaseIdToken']);
            } catch (FailedToVerifyToken $e) {
                throw ValidationException::withMessages([
                    'firebaseIdToken' => 'The token is invalid: ' . $e->getMessage(),
                ]);
            }
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = $auth->getUser($uid);
            if (!$user) {
                throw ValidationException::withMessages([
                    'firebaseIdToken' => 'The token is invalid',
                ]);
            }
            $user = User::where('phone', $credentials['phone'])->first();
            if ($user) {
                // Authenticate the user
                Auth::login($user);
                $result = true; // Set result to true since authentication was successful
            }
        }

        // Check if the login attempt was unsuccessful
        if (!$result) {
            RateLimiter::hit($this->emailThrottleKey());
            RateLimiter::hit($this->phoneThrottleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
                'phone' => trans('auth.failed'),
            ]);
        }

        // If successful, clear the rate limiter for the given key
        RateLimiter::clear($this->emailThrottleKey());
        RateLimiter::clear($this->phoneThrottleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $email = $this->input('email');
        $phone = $this->input('phone');

        if ($email && !$phone) {
            // Rate limit for email authentication
            $key = $this->emailThrottleKey();
        } elseif (!$email && $phone) {
            // Rate limit for phone authentication
            $key = $this->phoneThrottleKey();
        } else {
            // Both email and phone provided, choose one or handle differently
            // For example, you can prioritize email authentication
            $key = $this->emailThrottleKey();
        }

        if (!RateLimiter::tooManyAttempts($key, 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
            'phone' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for email authentication.
     */
    public function emailThrottleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }

    /**
     * Get the rate limiting throttle key for phone authentication.
     */
    public function phoneThrottleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('phone')) . '|' . $this->ip());
    }
}
