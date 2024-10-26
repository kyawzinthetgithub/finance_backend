<?php

namespace App\Http\Resources\IncomeExpend;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Wallet\UserWalletResource;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeExpendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category = Category::findOrFail($this->category_id);
        return [
            'id' => $this->id ? makeHash($this->id) : '-',
            'category' => $this->category_id ? CategoryResource::make($category) : null,
            'wallet' => $this->wallet_id ? UserWalletResource::make(Wallet::find($this->wallet_id)) : null,
            'description' => $this->description ?? '-',
            'amount' => $this->amount ?? '-',
            'type' => $this->type == 'expend' ? 'expense' : $this->type,
            'action_date' => $this->action_date->format('d-m-Y h:m:s a'),
            'created_at' => $this->created_at?->format('d-m-Y h:m:s a') ?? '-',
            'deleted_at' => $this->deleted_at?->format('d-m-Y h:m:s a') ?? '-',
            'time' => $this->created_at?->format('h:m A') ?? '-',
        ];
    }
}
