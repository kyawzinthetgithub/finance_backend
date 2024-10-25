<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ExpendRepository;
use Illuminate\Http\Request;

class ExpendController extends Controller
{
    protected $repo;
    public function __construct(ExpendRepository $repo)
    {
        $this->repo = $repo;
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
