<?php

namespace App\Repositories;

use App\Models\IncomeExpend;

class IncomeRepository
{
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
        dd('pass validation');
    }
}
