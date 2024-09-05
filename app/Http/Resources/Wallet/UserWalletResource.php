<?php

namespace App\Http\Resources\Wallet;

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
            'user' => $this->user_id ? $user->name : '-',
            'wallet_type' => $this->wallet_type_id ? $wallet_type->name : '-',
            'name' => $this->name ?? '-',
            'bank_name' => $this->bank_name ?? '-',
            'amount' => $this->amount ?? '0',
            'created_at' => $this->created_at?->format('d-m-Y h:m:s a') ?? '-',
            'deleted_at' => $this->deleted_at?->format('d-m-Y h:m:s a') ?? '-'
        ];
    }
}
