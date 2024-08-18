<?php

namespace App\Models;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransferLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_wallet_id','to_wallet_id','description','amount'
    ];

    protected function casts()
    {
        return [
            'description' => 'string',
            'amount' => 'integer'
        ];
    }

    public function wallet()
    {
        $this->belongsTo(Wallet::class);
    }
}
