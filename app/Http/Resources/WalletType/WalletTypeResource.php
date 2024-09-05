<?php

namespace App\Http\Resources\WalletType;

use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $hashids = new Hashids(config('hashids.connections.main.salt'), config('hashids.connections.main.length'));
        return [
            'id' => $this->id ? $hashids->encode($this->id) : '-',
            'name' => $this->name??'-',
            'created_at' => $this->created_at?$this->created_at->format('d-m-Y h:m:s a'):'-'
        ];
    }
}
