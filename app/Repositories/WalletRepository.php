<?php

namespace App\Repositories;

use Illuminate\Http\Request;

class WalletRepository
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'wallet_type_id' => 'required',
            'amount' => 'required|integer'
        ]);

        dd('pass');
    }
}
