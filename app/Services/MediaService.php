<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class MediaService
{
    public function storeMedia($media, Model $model, string $prefix, string $type, string $uniquer = null): void
    {
        $model->addMedia($media)
            ->usingName($prefix . '_' . $model->id . '_' . $type)
            ->usingFileName($prefix . '_' . $model->id . '_' . $type . '.' . $media->extension())
            ->toMediaCollection($this->collectionName($prefix, $type, $uniquer));
    }

    private function deleteMedias(Model $model, string $prefix, string $type, string $uniquer = null): void
    {
        $model->clearMediaCollection($this->collectionName($prefix, $type, $uniquer));
    }

    private function collectionName(string $prefix, string $type, string|null $uniquer): string
    {
        if ($uniquer) {
            return $prefix . $type . $uniquer;
        }
        return $prefix . $type;
    }

    public function replaceMedia($media, Model $model, string $prefix, string $type, string $uniquer = null)
    {
        $this->deleteMedias($model, $prefix, $type, $uniquer);
        $this->storeMedia($media, $model, $prefix, $type, $uniquer);
    }
}
