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
        $data = $request->validate([
            'category_id' => 'required',
            'total' => 'required'
        ]);

        $category_id = $this->byHash($request->category_id);

        // check if the budget is already created with this category!!
        $budget = $this->model()->where('category_id', $category_id)->first();
        $today = Carbon::now(); //for check budget is expired or not
        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['alert'] = $request->alert ?? false;
        $data['expired_at'] = $today->endOfMonth();
        $data['category_id'] = $category_id;

        abort_if($budget && $budget->expired_at == $today, 422, "Your Budget is still active and it's not expried.");

        if ($budget) {
            return '';
        } else {
            $budget = $this->model()->create($data);
            $message = "Category Created Successfully";
            return json_response(201, $message, $budget);
        }
    }
}
