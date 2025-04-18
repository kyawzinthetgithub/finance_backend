<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BudgetRepository;

class BudgetController extends Controller
{
    protected $repo;
    public function __construct(BudgetRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        // index listing logic
    }

    public function store(Request $request)
    {
        // logger($request->all());
        $data = $this->repo->store($request);

        if (isset($data)) {
            $message = "Budget Saved Successfully";
            return json_response(201, $message, $data);
        }
    }

    public function userBudget(Request $request)
    {
        $data = $this->repo->userBudget($request);
        return json_response(200,'success',$data);
    }
}
