<?php

namespace App\Repositories;

use App\Models\User;
use Hashids\Hashids;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\IncomeExpend;
use Illuminate\Http\Request;
use App\Models\WalletTransferLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Wallet\UserWalletResource;
use App\Http\Resources\IncomeExpend\IncomeExpendResource;
use App\Http\Resources\IncomeExpend\IncomeExpendCollection;
use App\Services\CloudinaryService;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsSuccessful;

class WalletRepository
{
    protected $hashids;
    protected $cloudinary;
    public const DEPOSITE = 'deposite';

    public function __construct(Hashids $hashids, CloudinaryService $cloudinaryService)
    {
        $this->hashids = $hashids;
        $this->cloudinary = $cloudinaryService;
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();
        abort_if(!$user, '401', 'User not found');

        $image = null;
        if($request->hasFile('image')){
            $image = $this->cloudinary->upload($request->file('image'));
        }

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'wallet_type_id' => $wallet_type_id,
            'name' => $request->name,
            'bank_name' => $request->bank_name,
            'amount' => $request->amount,
            'image' => $request->hasFile('image') && $image ? $image->id : null
        ]);

        // income transaction for wallet creation
        $this->incomeTransactionForWallet($request, $user, $wallet);

        return UserWalletResource::make($wallet);
    }

    public function incomeTransactionForWallet($request, $user, $wallet)
    {
        // to make sure that category for deposit exit
        $category = Category::where('name', self::DEPOSITE)->first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Deposite',
                'type' => 'income'
            ]);
        };
        // data for income transaction
        $incomeData = new Request([
            'category_id' => $category->id,
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'description' => 'Wallet Creation income',
            'amount' => $request->amount,
            'type' => 'income',
            'action_date' => $wallet->created_at,
        ]);
        
        (new IncomeExpend())->store($incomeData);
    }

    public function UserWallet($request)
    {
        // $auth_user_id = $this->byHash($request->auth_user);
        $auth_user_id = Auth::user()->id;
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
        return new IncomeExpendCollection($detail);
    }

    public function transfer($request)
    {
        $request->validate([
            "amount" => "required",
            "from_wallet_id" => "required",
            "to_wallet_id" => "required",
            "description" => "required"
        ]);
        $fromWalletId = byHash($request->from_wallet_id);
        $toWalletId = byHash($request->to_wallet_id);
        $amount = $request->amount;
        $description = $request->description;

        DB::beginTransaction();

        try {
            $fromWallet = Wallet::findOrFail($fromWalletId);
            $toWallet = Wallet::findOrFail($toWalletId);

            // ပို့မယ့်ကောင်ထပ် amount ကမများအောင်စစ်ထားတာ
            if ($fromWallet->amount < $amount) {
                return response(['message' => 'စောက်ဆံမလောက်ဘူး'], 400);
            }

            //make transfer in wallettransferlog model
            (new WalletTransferLog())->makeTransfer($fromWallet, $toWallet, $amount, $description);

            // Commit the transaction
            DB::commit();

            return response(['message' => 'success']);
        } catch (\Exception $e) {
            // Rollback the transaction on failure
            DB::rollBack();
            return response(['message' => 'Transfer failed', 'error' => $e->getMessage()], 500);
        }
    }
}
