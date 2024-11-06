<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Category;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Auth;

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
                // If the budget is true, get only non-expired budgets
                $query->with(['budgets' => function ($query) {
                    $query->where('expired_at', '>', Carbon::now())->where('user_id', Auth::user()->id);
                }]);
            })
            ->get();

        // If 'budget' is not true, don't load any budgets
        if (!$request->boolean('budget')) {
            $category->load('budgets');
        }

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
