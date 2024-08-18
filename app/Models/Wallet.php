<?php

namespace App\Models;

use App\Models\WalletType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

    public function wallet_type()
    {
        return $this->belongsTo(WalletType::class);
    }
}
