<?php

namespace App\Repositories;

use App\Models\IncomeExpend;
use Hashids\Hashids;

class IncomeRepository
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
        $validType = IncomeExpend::TYPE['income'];
        $request->validate([
            'category_id' => 'required',
            'wallet_id' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'type' => "required|in:{$validType}"
        ]);

        $categoryId = $this->byHash($request->category_id);
        $walletId = $this->byHash($request->wallet_id);

        $income = IncomeExpend::create([
            'category_id' => $categoryId,
            'wallet_id' => $walletId,
            'description' => $request->description,
            'amount' => $request->amount,
            'type' => $request->type
        ]);
        return response(['message' => 'success']);
    }
}
