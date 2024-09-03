<?php

namespace App\Models;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletType extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name'
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
}
