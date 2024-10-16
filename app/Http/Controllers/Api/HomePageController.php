<?php

namespace App\Http\Controllers\Api;

use App\Models\IncomeExpend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomePageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $incomeExpend = IncomeExpend::query()
                    ->where('user_id', $user->id)
                    ->when($request->month, function($query, $month){
                        $query->whereMonth('action_date', $month);
                    })->when($request->year, function($query, $year){
                        $query->whereYear('action_date', $year);
                    })
                    ->select(
                        'type',
                        'user_id',
                        'wallet',
                        DB::raw("SUM(amount) as amount"),
                    )
                    ->groupBy('type')
                    ->get();

        $userWallet = $user->wallets->sum('amount');
        $data = [
            'transaction' => $incomeExpend,
            'walletBalance' => $userWallet
        ];
        
        return json_response(200, 'Data Retrived Successfully', $data);
    }
}
