<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeExpend extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'category_id',
        'wallet_id',
        'description',
        'amount',
        'type'
    ];

    const TYPE = [
        'income' => 'income',
        'expend' => 'expend'
    ];

    //relation with wallet and income_expend
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
