<?php

namespace App\Services;

use App\Models\Album;
use App\Http\Resources\Album\AlbumResource;
use App\Http\Resources\Album\AlbumCollection;
use App\Models\Picture;

class AlbumService
{
    public function __construct(private MediaService $mediaService)
    {
    }

    public function index(array $input): array
    {
        if (isset($input['page_size']) && $input['page_size'] == -1) {
            $albums = new AlbumCollection(Album::with('pictures')->get());
            $pagination = null;
        } else {
            $albums = new AlbumCollection(Album::with('pictures')->paginate($input['page_size'] ?? null)->appends($input));
            $pagination = [
                'total' => $albums->total(),
                'per_page' => $albums->perPage(),
                'current_page' => $albums->currentPage(),
                'last_page' => $albums->lastPage(),
                'from' => $albums->firstItem(),
                'to' => $albums->lastItem(),
                'next_page' => $albums->nextPageUrl(),
                'previous_page' => $albums->previousPageUrl()
            ];
        }

        return ["albums" => $albums, "pagination" => $pagination];
    }

    public function store(array $input): AlbumResource
    {
        $album = Album::create($input);

        if (isset($input['pictures'])) {
            foreach ($input['pictures'] as $picture) {
                $createdPicture = Picture::create([
                    'title' => $picture['title'],
                    'album_id' => $album->id
                ]);
                $this->mediaService->storeMedia($picture['content'], $createdPicture, 'pictures', 'content');
            }
        }

        return new AlbumResource($album->load('pictures'));
    }

    public function show(int $id): AlbumResource
    {
        return new AlbumResource($this->getById($id)->load('pictures'));
    }

    public function update(array $input, int $id): void
    {
        $album = $this->getById($id);

        if (isset($input['pictures'])) {
            Picture::where('album_id', $id)->delete();

            foreach ($input['pictures'] as $picture) {
                $createdPicture = Picture::create([
                    'title' => $picture['title'],
                    'album_id' => $album->id
                ]);
                $this->mediaService->storeMedia($picture['content'], $createdPicture, 'pictures', 'content');
            }
        }

        $album->update($input);
    }

    public function destroyWithPictures(int $id): void
    {
        $album = $this->getById($id);
        $album->pictures()->delete();
        $album->delete();
    }

    public function destroyAndMovePictures(int $id, int $anotherAlbumId): void
    {
        $album = $this->getById($id);
        $album->pictures()->update(['album_id' => $anotherAlbumId]);
        $album->delete();
    }

    private function getById(int $id): Album
    {
        return Album::findOrFail($id);
    }
}
