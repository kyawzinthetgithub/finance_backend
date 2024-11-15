<?php

namespace App\Repositories;

use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\Image;
use App\Models\Category;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Category\CategoryResource;

class CategoryRepository
{

    protected $cloudinary;
    protected $hashIds;
    public function __construct(CloudinaryService $cloudinaryService, HashIds $hashIds)
    {
        $this->cloudinary = $cloudinaryService;
        $this->hashIds = $hashIds;
    }

    public function byHash($id)
    {
        return $this->hashIds->decode($id)[0];
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

    public function detail($id)
    {
        $cateId = $this->byHash($id);
        $category = $this->model()->find($cateId);

        $message = "Category Detail Retrived Successfully";
        $data = new CategoryResource($category);

        return json_response(200, $message, $data);
    }

    public function update($request, $id)
    {
        $category = $this->model()->find($this->byHash($id));
        abort_unless($category, 404, "Category Not found for updating!!");

        $iconId = $this->updateIcon($request, $category);
        $name = $request->name;
        $type = $request->type;

        $category->name = $name;
        $category->icon = $iconId;
        $category->type = $type;
        $category->save();

        $message = "Category Updated Successfully";
        $data = new CategoryResource($category);
        return json_response(200, $message, $data);
    }

    protected function updateIcon($request, $category)
    {
        if ($request->hasFile('updated_icon')) {
            $old_icon = Image::where('id', $category->icon)->latest()->first();
            if ($old_icon) {
                $icon = $this->cloudinary->update($old_icon->image_url, $request->file('updated_icon'));
            } else {
                $icon = $this->cloudinary->upload($request->file('updated_icon'));
            }

            return $icon->id;
        }
        return null;
    }
}
