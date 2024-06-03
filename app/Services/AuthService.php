<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    public function __construct(private MediaService $mediaService)
    {
    }

    public function login(string $email, string $password): array
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Invalid credentials');
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
