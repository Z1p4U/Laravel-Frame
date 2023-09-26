<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "url" => asset(Storage::url($this->url)),
            "user_name" => $this->user->name,
            "size" => $this->size,
            "created_at" => $this->created_at->format("d M Y"),
            "updated_at" => $this->updated_at->format("d M Y"),
        ];
    }
}
