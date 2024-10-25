<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\IncomeRepository;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    protected $repo;
    public function __construct(IncomeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        return $this->repo->index($request);
    }

    public function detail($id)
    {
        return $this->repo->detail($id);
    }

    public function store(Request $request)
    {
        return $this->repo->store($request);
    }
}
