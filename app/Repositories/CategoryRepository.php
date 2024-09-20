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

    public function store($request)
    {
        $data = $this->createPayload($request);
        $category = $this->model()->create($data);
        $message = "Category Created Successfully";
        return json_response(200,$message, $category);
    }

    protected function createPayload($request)
    {
        $icon = $request->icon;
        info($icon);
        $data = [
            'name' => $request->name,
            'type' => $request->type
        ];
        $imagename = time().'.' . $icon->extension();
        $icon->move(public_path('images/category'), $imagename);
        $data['icon'] = 'images/item/' . $imagename;
        return $data;
    }
}
