<?php

namespace App\Http\Resources\Category;

use Hashids\Hashids;
use App\Models\Image;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id?makeHash($this->id):'-',
            'name' => $this->name,
            'type' => $this->type,
            'icon' => $this->icon ? Image::where('id',$this->icon)->latest()->first()->image_url : null
        ];
    }
}
