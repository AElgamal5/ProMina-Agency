<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'message' => 'Getting all users successfully',
            'status' => 'success',
            'data' => $this->userService->index($request->all())
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        return response()->json([
            'message' => 'User created successfully',
            'status' => 'success',
            'data' => $this->userService->store($request->all())
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return response()->json([
            'message' => 'Getting user successfully',
            'status' => 'success',
            'data' => $this->userService->show($id)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $this->userService->update($request->all(), $id);

        return response()->json([
            'message' => 'User updated successfully',
            'status' => 'success',
            'data' => null
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->userService->destroy($id);

        return response()->json([
            'message' => 'User deleted successfully',
            'status' => 'success',
            'data' => null
        ], Response::HTTP_OK);
    }
}
