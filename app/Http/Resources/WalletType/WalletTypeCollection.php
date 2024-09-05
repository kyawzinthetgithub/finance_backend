<?php

namespace App\Http\Resources\WalletType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WalletTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function($walletType){
            return new WalletTypeResource($walletType);
        });
    }
}
