<?php

namespace App\Models;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletTransferLog extends Model
{
    use HasFactory,SoftDeletes;

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

    public function makeTransfer($fromWallet, $toWallet, $amount, $description)
    {
        // Log the transfer
        self::create([
            "amount" => $amount,
            "from_wallet_id" => $fromWallet->id,
            "to_wallet_id" => $toWallet->id,
            "description" => $description
        ]);

        // Update wallet balances
        $fromWallet->decrement('amount', $amount);
        $toWallet->increment('amount', $amount);
    }
}
