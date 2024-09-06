<?php

namespace App\Http\Resources\Wallet;

use App\Http\Resources\User\UserResource;
use App\Http\Resources\WalletType\WalletTypeResource;
use App\Models\User;
use App\Models\WalletType;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $hashids = new Hashids(config('hashids.connections.main.salt'), config('hashids.connections.main.length'));
        return [
            'id' => $this->id ? $hashids->encode($this->id) : '-',
            'user' => $this->user_id ? UserResource::make($user) : '-',
            'wallet_type' => $this->wallet_type_id ? WalletTypeResource::make($wallet_type) : '-',
            'name' => $this->name ?? '-',
            'bank_name' => $this->bank_name ?? '-',
            'amount' => $this->amount ?? '0',
            // 'total_ammount' => $this->amount?$this->amount->sum('')
            'created_at' => $this->created_at?->format('d-m-Y h:m:s a') ?? '-',
            'deleted_at' => $this->deleted_at?->format('d-m-Y h:m:s a') ?? '-'
        ];
    }
}
