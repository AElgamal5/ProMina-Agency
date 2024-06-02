<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserCollection;

class UserService
{
    public function __construct(private MediaService $mediaService)
    {
    }

    public function index(array $input): array
    {
        if (isset($input['page_size']) && $input['page_size'] == -1) {
            $users = new UserCollection(User::get());
            $pagination = null;
        } else {
            $users = new UserCollection(User::paginate($input['page_size'] ?? null)->appends($input));
            $pagination = [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
                'next_page' => $users->nextPageUrl(),
                'previous_page' => $users->previousPageUrl()
            ];
        }

        return ["users" => $users, "pagination" => $pagination];
    }

    public function store(array $input): UserResource
    {
        $user = User::create($input);

        if (isset($input['image']) && $input['image']) {
            $this->mediaService->storeMedia($input['image'], $user, 'users', 'images');
        }

        return new UserResource($user);
    }

    public function show(int $id): UserResource
    {
        return new UserResource($this->getById($id));
    }

    public function update(array $input, int $id): void
    {
        $user = $this->getById($id);

        if (isset($input['image']) && $input['image']) {
            $this->mediaService->replaceMedia($input['image'], $user, 'users', 'images');
        }

        $user->update($input);
    }

    public function destroy(int $id): void
    {
        $user = $this->getById($id);
        $user->delete();
    }

    private function getById(int $id): User
    {
        return User::findOrFail($id);
    }
}
