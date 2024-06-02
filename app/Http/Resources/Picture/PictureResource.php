<?php

namespace App\Http\Resources\Picture;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PictureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->getFirstMediaUrl('pictures_content'),
            'created_at' => $this->created_at,
            'updated_at' => $this->created_at,
        ];
    }
}
