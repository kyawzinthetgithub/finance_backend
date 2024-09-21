<?php

namespace App\Http\Controllers\Api\IncomeExpendCategory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryCollection;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $repo;
    public function __construct(CategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $res = $this->repo->getAll($request);
        return new CategoryCollection($res);
    }

    public function store(Request $request)
    {
        $res = $this->repo->store($request);
        return $res;
    }
}
