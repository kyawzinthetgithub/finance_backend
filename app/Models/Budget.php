<?php

namespace App\Models;

use App\Models\Wallet;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'wallet_id',
        'total',
        'spend_amount',
        'remaining_amount'
    ];

    protected function casts(): array
    {
        return [
            'name' => 'string'
        ];
    }

    //relation with wallet
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function category()
    {
        return $this->hasOne(Category::class);
    }
}
