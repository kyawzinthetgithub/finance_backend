<?php

namespace App\Repositories;

use App\Models\IncomeExpend;
use App\Models\Wallet;
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
        $income = (new IncomeExpend())->store($request);
        return $income;
    }
}
