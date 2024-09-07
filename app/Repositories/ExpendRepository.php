<?php

namespace App\Repositories;

use Hashids\Hashids;
use App\Models\Wallet;
use App\Models\IncomeExpend;

class ExpendRepository
{
    protected $hashids;
    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    public function byHash($id)
    {
        return $this->hashids->decode($id)[0];
    }

    public function store($request)
    {
        $validType = IncomeExpend::TYPE['expend'];
        $request->validate([
            'category_id' => 'required',
            'wallet_id' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'type' => "required|in:{$validType}"
        ]);

        $categoryId = $this->byHash($request->category_id);
        $walletId = $this->byHash($request->wallet_id);

        $wallet = Wallet::findOrFail($walletId);

        $expend = IncomeExpend::create([
            'category_id' => $categoryId,
            'wallet_id' => $walletId,
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type
        ]);

        // dd($expend);

        // $wallet->amount-= $expend->amount;
        // $wallet->save();
        return response(['message' => 'success']);
    }
}
