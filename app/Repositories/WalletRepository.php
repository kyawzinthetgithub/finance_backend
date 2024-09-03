<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsSuccessful;

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

    public function destory($id)
    {
        $wallet = Wallet::findOrFail($id);
        abort_if(!$wallet,404,'Wallet not found');
        $wallet->delete();
        return response(['message' => 'success'],200);
    }

    public function getDeleteWallet($request)
    {
        $wallet = Wallet::where('user_id',$request->user_id)->withTrashed()->get();
        abort_if(!$wallet,422,'Wallet not found');
        return $wallet;
    }

    public function restoreWallet($request)
    {
        $wallet = Wallet::where('user_id',$request->user_id)->where('id',$request->wallet_id)->withTrashed()->latest()->first();
        abort_if(!$wallet,422,'Wallet not found');
        $wallet->deleted_at = null;
        $wallet->save();
        return response(['message' => 'restore success'],200);
    }
}
