<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service) {}

    /* ── POST /api/auth/register ─────────────────────────────────── */

    public function register(RegisterRequest $request): JsonResponse
    {
        $payload = $this->service->register($request->validated());

        return response()->json($this->withUserResource($payload), 201);
    }

    /* ── POST /api/auth/login ────────────────────────────────────── */

    public function login(LoginRequest $request): JsonResponse
    {
        $payload = $this->service->login($request->validated());

        if ($payload === null) {
            return response()->json(['message' => 'E-mail ou senha incorretos.'], 401);
        }

        return response()->json($this->withUserResource($payload));
    }

    /* ── POST /api/auth/logout ───────────────────────────────────── */

    public function logout(): JsonResponse
    {
        $this->service->logout();

        return response()->json(['message' => 'Logout realizado.']);
    }

    /* ── POST /api/auth/refresh ──────────────────────────────────── */

    public function refresh(): JsonResponse
    {
        $payload = $this->service->refresh();

        if ($payload === null) {
            return response()->json(['message' => 'Token inválido.'], 401);
        }

        return response()->json($this->withUserResource($payload));
    }

    /* ── GET /api/auth/me ────────────────────────────────────────── */

    public function me(): JsonResponse
    {
        return response()->json(new UserResource($this->service->me()));
    }

    /**
     * @param array{token: string, token_type: string, expires_in: int, user: \App\Models\User} $payload
     * @return array{token: string, token_type: string, expires_in: int, user: UserResource}
     */
    private function withUserResource(array $payload): array
    {
        $payload['user'] = new UserResource($payload['user']);

        return $payload;
    }
}
