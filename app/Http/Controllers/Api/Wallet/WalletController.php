<?php

namespace App\Http\Controllers\Api\Wallet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\WalletRepository;
use App\Http\Resources\Wallet\UserWalletResource;
use App\Http\Resources\Wallet\UserWalletCollection;
use Carbon\Carbon;

class WalletController extends Controller
{
    protected $repo;
    public function __construct(WalletRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store(Request $request)
    {
        $this->repo->store($request);
        return response(['data' => 'success'],200);
    }

    public function UserWallet(Request $request)
    {
        $res = $this->repo->UserWallet($request);
        return UserWalletCollection::make($res);
    }

    public function destory($id)
    {
        return $this->repo->destory($id);
    }
}
