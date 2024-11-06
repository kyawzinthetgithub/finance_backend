<?php

namespace App\Repositories;

use Carbon\Carbon;
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
        $category = $this->model()
            ->query()
            ->when($request->input('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->boolean('budget'), function ($query) {
                $query->has('budgets');
            })
            ->with(['budgets' => function ($query) {
                $query->where('expired_at', '>', Carbon::now());
            }])
            ->get();

        return $category;
    }

    public function store($request)
    {
        $data = $this->createPayload($request);
        $category = $this->model()->create($data);
        $message = "Category Created Successfully";
        return json_response(201, $message, $category);
    }

    protected function createPayload($request)
    {
        $data = [
            'name' => $request->name,
            'type' => $request->type
        ];
        $icon = null;
        if ($request->hasFile('icon')) {
            $icon = $this->cloudinary->upload($request->file('icon'));
            $data['icon'] = $icon->id;
        }
        return $data;
    }
}
