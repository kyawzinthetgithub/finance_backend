<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncomeExpend extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'category_id',
        'wallet_id',
        'description',
        'amount',
        'type',
        'action_date'
    ];

    const TYPE = [
        'income' => 'income',
        'expend' => 'expend'
    ];

    public function store($data)
    {
        $validType = $data->type == 'income' ? self::TYPE['income'] : self::TYPE['expend'];
        $data->validate([
            'category_id' => 'required',
            'wallet_id' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'type' => "required|in:{$validType}"
        ]);

        $categoryId = byHash($data->category_id);
        $walletId = byHash($data->wallet_id);
        $wallet = Wallet::findOrFail($walletId);

        self::create([
            'category_id' => $categoryId,
            'wallet_id' => $walletId,
            'description' => $data->description,
            'amount' => $data->amount,
            'type' => $validType,
            'action_date' => $data->action_date ? Carbon::createFromFormat('Y-m-d',$data->action_date) : null
        ]);

        $validType == 'income' ? $wallet->amount += $data->amount : $wallet->amount -= $data->amount;
        $wallet->save();
        return response(['message' => 'success']);


    }

    //relation with wallet and income_expend
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

     // Scope for filtering by specific day
    public function scopeForDay($query, $date)
    {
        $parsedDate = Carbon::parse($date);
        return $query->whereDate('created_at', $parsedDate->format('Y-m-d'));
    }
}
