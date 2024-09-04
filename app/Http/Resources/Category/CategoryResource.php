<?php

namespace App\Http\Resources\Category;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\This;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        $hashids = new HashIds(config('hashids.connections.main.salt'),config('hashids.connections.main.length'));
        return [
            'id' => $this->id?$hashids->encode($this->id):'-',
            'name' => $this->name,
            'type' => $this->type
        ];
    }
}
