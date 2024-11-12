<?php

namespace App\Repositories;

use App\Http\Resources\IncomeExpend\IncomeExpendResource;
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
        info('here');
        info($id);
        return $this->hashids->decode($id)[0];
    }

    public function detail($id)
    {
        $incomeId = $this->byHash($id);
        $income = IncomeExpend::find($incomeId);

        $data = new IncomeExpendResource($income);
        $message = 'Income Retrived Successfully';
        return json_response(200, $message, $data);
    }

    public function index($request)
    {

        $request->validate([
            'category_id' => 'nullable|string'
        ]);
        $user = Auth::user();

        $startOfWeek = null;
        $endOfWeek = null;
        if ($request->has('week')) {
            $week = Carbon::now()->weekOfYear;
            $year = Carbon::now()->year;
            $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $endOfWeek = Carbon::now()->setISODate($year, $week)->endOfWeek();
        }

        $data = IncomeExpend::query()
            ->where('user_id', $user->id)
            ->when($request->boolean('today'), function ($query) {
                $query->whereDate('action_date', Carbon::now());
            })
            ->when($request->boolean('week'), function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('action_date', [$startOfWeek, $endOfWeek]);
            })
            ->when($request->boolean('month'), function ($query) {
                $query->whereMonth('action_date', Carbon::now()->month);
            })
            ->when($request->boolean('year'), function ($query) {
                $query->whereYear('action_date', Carbon::now()->year);
            })
            ->when($request->has('category_id'), function ($query) use ($request) {
                $query->where('category_id', $this->byHash($request->category_id));
            })
            ->when($request->has('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->has('sort'), function ($query) use ($request) {
                $sort = $request->sort;
                if ($sort == 'highest' || $sort == 'lowest') {
                    $query->orderBy('amount', $sort == 'highest' ? 'asc' : 'desc');
                } else {
                    $query->orderBy('action_date', $sort == 'newest' ? 'desc' : 'asc');
                }
            })
            ->get();
        $result = IncomeExpendResource::collection($data);
        $message = 'IncomeExpend Transaction Retrived Successfully';
        return json_response(200, $message, $result);
    }

    public function store($request)
    {
        $income = (new IncomeExpend())->store($request);
        return $income;
    }
}
