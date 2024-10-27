<?php

namespace App\Repositories;

use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\Budget;
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
        $budget = $this->model()->where('category_id', $category_id)->where('user_id',$user->id)->first();
        $today = Carbon::now(); //for check budget is expired or not
        $expired_date = Carbon::now()->endOfMonth();
        $data['user_id'] = $user->id;
        $data['alert'] = $request->alert ?? false;
        $data['expired_at'] = $expired_date;
        $data['category_id'] = $category_id;
        if ($budget) {
            abort_if($budget && $budget->expired_at > $today, 422, "Your Budget is still active and it's not expried.");
            $budget->update($data);
            $message = "Category Updated Successfully";
            return json_response(201, $message, $budget);
        } else {
            $budget = $this->model()->create($data);
            $message = "Category Created Successfully";
            return json_response(201, $message, $budget);
        }
    }
}
