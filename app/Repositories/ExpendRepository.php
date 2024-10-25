<?php

namespace App\Repositories;

use App\Http\Resources\IncomeExpend\IncomeExpendResource;
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

    public function detail($id)
    {
        $expendId = $this->byHash($id);
        $expend = IncomeExpend::find($id);

        $data = new IncomeExpendResource($expend);
        $message = "Expend Retrived Successfully";

        return json_response(200, $message, $data);
    }

    public function store($request)
    {
        $expend = (new IncomeExpend())->store($request);
        return $expend;
    }
}
