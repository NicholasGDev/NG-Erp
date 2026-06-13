<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /* ── Helpers ─────────────────────────────────────────────────── */

    private function tokenResponse(string $token, ?User $user = null): JsonResponse
    {
        $user ??= auth('api')->user();

        return response()->json([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user'       => $user,
        ]);
    }

    /* ── POST /api/auth/register ─────────────────────────────────── */

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'company'  => ['nullable', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->tokenResponse($token, $user)->setStatusCode(201);
    }

    /* ── POST /api/auth/login ────────────────────────────────────── */

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'E-mail ou senha incorretos.'], 401);
        }

        return $this->tokenResponse($token);
    }

    /* ── POST /api/auth/logout ───────────────────────────────────── */

    public function logout(): JsonResponse
    {
        try {
            auth('api')->logout();
        } catch (JWTException) {
            // token já inválido, ignora
        }

        return response()->json(['message' => 'Logout realizado.']);
    }

    /* ── POST /api/auth/refresh ──────────────────────────────────── */

    public function refresh(): JsonResponse
    {
        try {
            $token = auth('api')->refresh();
        } catch (JWTException) {
            return response()->json(['message' => 'Token inválido.'], 401);
        }

        return $this->tokenResponse($token);
    }

    /* ── GET /api/auth/me ────────────────────────────────────────── */

    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }
}
