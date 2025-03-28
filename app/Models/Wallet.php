<?php

namespace App\Models;

use App\Models\User;
use App\Models\WalletType;
use App\Models\WalletTransferLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id','wallet_type_id','name','bank_name','amount','image'
    ];

    //relation with wallet type and wallet
    public function wallet_type()
    {
        return $this->belongsTo(WalletType::class);
    }

    //relation with user and wallet
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relation with wallet and wallet transfer_logs
    public function transfer_logs()
    {
        return $this->hasMany(WalletTransferLog::class);
    }

    //relation with wallet and income_expends
    public function income_expends()
    {
        return $this->hasMany(IncomeExpend::class);
    }
}
