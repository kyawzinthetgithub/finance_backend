<?php

namespace App\Repositories;

use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\Wallet;
use App\Models\IncomeExpend;
use Illuminate\Support\Facades\Auth;

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

    public function index($request)
    {
        $user = Auth::user();

        $startOfWeek = null;
        $endOfWeek = null;
        if ($request->has('week')) {
            $week = $request->get('week', Carbon::now()->weekOfYear);
            $year = $request->get('year', Carbon::now()->year);
            $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $endOfWeek = Carbon::now()->setISODate($year, $week)->endOfWeek();
        }

        $data = IncomeExpend::query()
                ->where('user_id', $user->id)
                ->when($request->has('today'), function($query) {
                    $query->whereDate('action_date', Carbon::now());
                })
                ->when($request->has('week'), function($query) use($startOfWeek,$endOfWeek) {
                    $query->whereBetween('action_date', [$startOfWeek, $endOfWeek]);
                })
                ->when($request->has('month'), function($query) {
                    $query->whereMonth('action_date', Carbon::now()->month);
                })
                ->when($request->has('year'), function($query) {
                    $query->whereYear('action_date', Carbon::now()->year);
                })
                ->get();
        return $data;
    }

    public function store($request)
    {
        $income = (new IncomeExpend())->store($request);
        return $income;
    }
}
