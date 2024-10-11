<?php

namespace App\Http\Controllers\Api;

use App\Models\IncomeExpend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    protected function baseQuery()
    {

    }
    public function index(Request $request)
    {
        $income = IncomeExpend::query()
                    ->when($request->month, function($query, $month){
                        $query->whereMonth('created_at', $month);
                    })->when($request->year, function($query, $year){
                        $query->whereYear('created_at', $year);
                    })
                    ->select(
                        'type',
                        DB::raw("SUM(amount) as amount")
                    )
                    ->groupBy('type')
                    ->get();

        return $income;
    }
}
