<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "hash" => base64_encode($this->id),
            "fk_user_id" => $this->fk_user_id,
            "title" => $this->title,
            "path" => $this->path,
            "status" => $this->status,
            "updated_at" => $this->updated_at,
        ];
    }
}
