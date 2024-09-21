<?php

namespace App\Http\Resources\Wallet;

use App\Models\User;
use Hashids\Hashids;
use App\Models\Image;
use App\Models\WalletType;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\WalletType\WalletTypeResource;

class UserWalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::findOrFail($this->user_id);
        $wallet_type = WalletType::findOrFail($this->wallet_type_id);
        return [
            'id' => $this->id ? makeHash($this->id) : '-',
            'user' => $this->user_id ? UserResource::make($user) : '-',
            'wallet_type' => $this->wallet_type_id ? WalletTypeResource::make($wallet_type) : '-',
            'name' => $this->name ?? '-',
            'bank_name' => $this->bank_name ?? '-',
            'amount' => $this->amount ?? '0',
            'image' => $this->image ? Image::where('id',$this->image)->latest()->first()->image_url : null,
            'created_at' => $this->created_at?->format('d-m-Y h:m:s a') ?? '-',
            'deleted_at' => $this->deleted_at?->format('d-m-Y h:m:s a') ?? '-'
        ];
    }
}
