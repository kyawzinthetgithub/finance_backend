<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    protected function model()
    {
        return new Category();
    }
    public function getAll($request)
    {
        $category = $this->model()->getWithType($request->type);
        return $category;
    }
}
