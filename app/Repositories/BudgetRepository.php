<?php

namespace App\Repositories;

use App\Http\Resources\Budget\BudgetResource;
use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetRepository
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

    protected function model()
    {
        return new Budget();
    }

    public function store($request)
    {
        $data = $request->validate(
            [
                'category_id' => 'required',
                'total' => 'required'
            ],
            [
                'category_id.required' => 'The category field is required. Please select a category.',
                'total.required' => 'The total field is required. Please provide the total amount.'
            ]
        );

        $category_id = $this->byHash($request->category_id);

        // check if the budget is already created with this category!!
        $user = Auth::user();
        $budget = $this->model()->where('category_id', $category_id)->where('user_id', $user->id)->first();
        $today = Carbon::now(); //for check budget is expired or not
        $expired_date = Carbon::now()->endOfMonth();
        $data['user_id'] = $user->id;
        $data['alert'] = $request->alert ?? false;
        $data['expired_at'] = $expired_date;
        $data['category_id'] = $category_id;
        $data['remaining_amount'] = $request->total;
        if ($budget) {
            if ($budget->expired_at->isSameMonth($today)) {
                // If the budget is still active for the same month, update it
                $budget->update($data);
                $message = "Budget Updated Successfully for the current month.";
            } else {
                // If the month is different, create a new budget
                $data['remaining_amount'] = $request->total;
                $budget = $this->model()->create($data);
                $message = "New Budget Created Successfully for a new month.";
            }
            return json_response(201, $message, $budget);
        } else {
            $budget = $this->model()->create($data);
            $message = "Category Created Successfully";
            return json_response(201, $message, $budget);
        }
    }

    public function userBudget(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000|max:' . Carbon::now()->year,
            'per_page' => 'integer|min:1|max:100',
        ]);
        $month = $request->month;
        $perPage = $request->input('per_page', 10);
        $year = $request->year;
        $auth_user = Auth::user();
        $user = User::findOrFail($auth_user->id);
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
        $data = $this->model()
            ->with('user') // Eager load user and category relationships
            ->whereBetween('expired_at', [$startDate, $endDate])
            ->paginate($perPage);

        // Return the paginated data as a resource collection
        return BudgetResource::collection($data);
    }
}
