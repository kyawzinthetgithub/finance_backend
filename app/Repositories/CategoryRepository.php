<?php

namespace App\Repositories;

use App\Models\Category;
use App\Services\CloudinaryService;

class CategoryRepository
{

    protected $cloudinary;
    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinary = $cloudinaryService;
    }

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
        return json_response(201,$message, $category);
    }

    protected function createPayload($request)
    {
        $data = [
            'name' => $request->name,
            'type' => $request->type
        ];
        $icon = null;
        if($request->hasFile('icon')){
            $icon = $this->cloudinary->upload($request->file('icon'));
            $data['icon'] = $icon->id;
        }
        return $data;
    }
}
