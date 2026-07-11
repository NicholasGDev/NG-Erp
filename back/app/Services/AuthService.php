<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTGuard;

final class AuthService
{
    public function __construct(private readonly User $model)
    {
    }

    /**
     * @param array{name: string, email: string, password: string} $data
     * @return array{token: string, token_type: string, expires_in: int, user: User}
     */
    public function register(array $data): array
    {
        /** @var User $user */
        $user = $this->model->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->buildTokenPayload($token, $user);
    }

    /**
     * @param array{email: string, password: string} $credentials
     * @return array{token: string, token_type: string, expires_in: int, user: User}|null
     */
    public function login(array $credentials): ?array
    {
        $token = $this->guard()->attempt($credentials);

        if ($token === false) {
            return null;
        }

        return $this->buildTokenPayload($token);
    }

    public function logout(): void
    {
        try {
            $this->guard()->logout();
        } catch (JWTException) {
            // token já inválido, ignora
        }
    }

    /**
     * @return array{token: string, token_type: string, expires_in: int, user: User}|null
     */
    public function refresh(): ?array
    {
        try {
            $token = $this->guard()->refresh();
        } catch (JWTException) {
            return null;
        }

        return $this->buildTokenPayload($token);
    }

    public function me(): ?User
    {
        /** @var User|null $user */
        $user = $this->guard()->user();

        return $user;
    }

    /**
     * @return array{token: string, token_type: string, expires_in: int, user: User}
     */
    private function buildTokenPayload(string $token, ?User $user = null): array
    {
        /** @var User $resolvedUser */
        $resolvedUser = $user ?? $this->guard()->user();

        return [
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => (int) config('jwt.ttl') * 60,
            'user'       => $resolvedUser,
        ];
    }

    private function guard(): JWTGuard
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        return $guard;
    }
}
