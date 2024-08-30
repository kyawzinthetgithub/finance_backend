<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Repositories\WalletRepository;
use Illuminate\Http\Request;

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
}
