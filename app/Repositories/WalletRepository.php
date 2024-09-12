<?php

namespace App\Repositories;

use App\Models\User;
use Hashids\Hashids;
use App\Models\Wallet;
use App\Models\IncomeExpend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Wallet\UserWalletResource;
use App\Http\Resources\IncomeExpend\IncomeExpendResource;
use App\Http\Resources\IncomeExpend\IncomeExpendCollection;
use App\Models\Category;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsSuccessful;

class WalletRepository
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

    public function store(Request $request)
    {
        $wallet_type_id = $this->byHash($request->wallet_type_id);
        $request->validate([
            'name' => 'required',
            'wallet_type_id' => 'required',
            'amount' => 'required|integer',
        ]);

        $user = Auth::user();
        abort_if(!$user, '401', 'User not found');
        $wallet = Wallet::create([
            'user_id' => $user->id,
            'wallet_type_id' => $wallet_type_id,
            'name' => $request->name,
            'bank_name' => $request->bank_name,
            'amount' => $request->amount
        ]);
        return $wallet;
    }

    public function UserWallet($request)
    {
        $auth_user_id = $this->byHash($request->auth_user);
        $user = User::findOrFail($auth_user_id);
        abort_if($auth_user_id != $user->id, 401, 'User Not Found');
        $user_wallet = $user->wallets()->get();
        $total_amount = $user_wallet->sum('amount');
        $data = [
            'user_wallet' => $user_wallet,
            'total_amount' => $total_amount
        ];
        return $data;
    }

    public function destory($id)
    {
        $wallet_id = $this->byHash($id);
        $wallet = Wallet::findOrFail($wallet_id);
        abort_if(!$wallet, 404, 'Wallet not found');
        $wallet->delete();
        return response(['message' => 'success'], 200);
    }

    public function getDeleteWallet($request)
    {
        $wallet = Wallet::where('user_id', $this->byHash($request->user_id))->withTrashed()->where('deleted_at', '!=', null)->get();
        abort_if(!$wallet, 422, 'Wallet not found');
        return $wallet;
    }

    public function restoreWallet($request)
    {
        $wallet = Wallet::where('user_id', $this->byHash($request->user_id))->where('id', $this->byHash($request->wallet_id))->withTrashed()->latest()->first();
        abort_if(!$wallet, 422, 'Wallet not found');
        $wallet->deleted_at = null;
        $wallet->save();
        return response(['message' => 'restore success'], 200);
    }

    public function AccountDetail($request, $id)
    {
        $per_page = $request->per_page;
        $detail = IncomeExpend::where('wallet_id', byHash($id))->orderBy('created_at', 'desc')->paginate($per_page);
        $data = $detail->getCollection()->map(function ($item) {
            return [
                'id' => $item->id ? makeHash($item->id) : null,
                'category' => $item->category_id ? CategoryResource::make(Category::where('id', $item->category_id)->first()) : null,
                'description' => $this->description ?? '-',
                'amount' => $item->amount ?? '-',
                'type' => $item->type ?? '-',
                'created_at' => $item->created_at?->format('d-m-Y h:m:s a') ?? '-',
                'deleted_at' => $item->deleted_at?->format('d-m-Y h:m:s a') ?? '-'
            ];
        });
        $detail->setCollection($data);
        return $detail;
    }
}
