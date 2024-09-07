<?php

namespace App\Repositories;

use Hashids\Hashids;
use App\Models\Wallet;
use App\Models\IncomeExpend;

class ExpendRepository
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
        $expend = (new IncomeExpend())->store($request);
        return $expend;
    }
}
