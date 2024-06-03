<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;

class AuthService
{
    public function __construct(private MediaService $mediaService)
    {
    }

    public function login(string $email, string $password): array
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            throw new \Exception('Invalid credentials');
        }

        $user = Auth::user();

        return [
            'token' => $user->createToken('token', ['*'], now()->addDays(1))->plainTextToken,
            'user' => new UserResource($user),
        ];
    }
    public function register(array $input): void
    {
        User::create($input);
    }
}
