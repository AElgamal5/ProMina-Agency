<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AlbumService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Album\StoreAlbumRequest;
use App\Http\Requests\Album\UpdateAlbumRequest;

class AlbumController extends Controller
{
    public function __construct(private AlbumService $AlbumService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'message' => 'Getting all Albums successfully',
            'data' => $this->AlbumService->index($request->all())
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlbumRequest $request)
    {
        return response()->json([
            'message' => 'Album created successfully',
            'data' => $this->AlbumService->store($request->all())
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return response()->json([
            'message' => 'Getting Album successfully',
            'data' => $this->AlbumService->show($id)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlbumRequest $request, int $id)
    {
        $this->AlbumService->update($request->all(), $id);

        return response()->json([
            'message' => 'Album updated successfully',
            'data' => null
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyWithPictures(int $id)
    {
        $this->AlbumService->destroyWithPictures($id);

        return response()->json([
            'message' => 'Album deleted with picture successfully',
            'data' => null
        ], Response::HTTP_OK);
    }

    public function destroyAndMovePictures(int $id, int $anotherAlbumId)
    {
        $this->AlbumService->destroyAndMovePictures($id, $anotherAlbumId);

        return response()->json([
            'message' => 'Album deleted successfully',
            'data' => null
        ], Response::HTTP_OK);
    }
}
