<?php

namespace App\Models;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function wallets(){
        return $this->hasMany(Wallet::class);
    }
}
