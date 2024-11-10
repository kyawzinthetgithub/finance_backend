<?php

namespace App\Http\Resources\Budget;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\User\UserResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $category = Category::where('id', $this->category_id)->latest()->first();
        return [
            'id' => makeHash($this->id),
            'user' => $this->user_id ? UserResource::make($this->user) : null,
            'alert' => $this->alert == 1 ? true : false,
            'category' => $this->category_id ? CategoryResource::make($category) : null,
            'expired_at' => $this->expired_at->format('d-m-Y h:m:s a') ?? '-',
            'remaining_amount' => $this->remaining_amount ?? 0,
            'spend_amount' => $this->spend_amount ?? 0,
            'total' => $this->total ?? 0,
            'usage' => $this->usage ?? 0
        ];
    }
}
