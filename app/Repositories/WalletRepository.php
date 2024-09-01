<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletRepository
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'wallet_type_id' => 'required',
            'amount' => 'required|integer',
            'bank_name' => 'required_unless:wallet_type_id,1',
        ]);

        $user = Auth::user();
        abort_if(!$user,'401','User not found');
        $wallet = Wallet::create([
            'user_id' => $user->id,
            'wallet_type_id' => $request->wallet_type_id,
            'name' => $request->name,
            'bank_name' => $request->bank_name,
            'amount' => $request->amount
        ]);
        return $wallet;
    }

    public function UserWallet($request)
    {
        $user = User::findOrFail($request->auth_user);
        abort_if($request->auth_user != $user->id,401,'User Not Found');
        $user_wallet = $user->wallets()->get();
        return $user_wallet;
    }
}
