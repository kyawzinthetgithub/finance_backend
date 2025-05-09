<?php

namespace App\Http\Resources\User;

use App\Models\Image;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ? makeHash($this->id) : '-',
            'name' => $this->name ?? '-',
            'email' => $this->email ?? '-',
            'image' => $this->image ? Image::findOrFail($this->image)->image_url : null,
            'currency' => $this->currency,
            'created_at' => $this->created_at ? $this->created_at->format('d-m-Y h:ma') : '-'
        ];
    }
}
