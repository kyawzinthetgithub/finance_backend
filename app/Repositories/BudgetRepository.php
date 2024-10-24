<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;

class BudgetRepository
{
  protected function model()
  {
    return new Budget();
  }
  public function store($request)
  {
    $data = $request->validate([
      'category_id' => 'required|numeric',
      'total' => 'required|numeric'
    ]);

    // check if the budget is already created with this category!!
    $budget = Budget::where('category_id')->first();
    $today = Carbon::now();


    $user = Auth::user();
    $data['user_id'] = $user->id;
  }
}
