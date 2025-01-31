<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class AdminCategoryController extends ApiController
{
    public function index(): JsonResponse
    {

        $categories = Category::orderBy('id')->get();

        return $this->showAll(CategoryResource::collection($categories));
    }

    public function store(CreateCategoryRequest $request): JsonResponse
    {
        $newCategory = Category::create($request->validated());

        return $this->showOne(new CategoryResource($newCategory), 201);
    }

    public function show(Category $category): JsonResponse
    {
        return $this->showOne(new CategoryResource($category));
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->fill($request->only([
            'name',
            'description',
            'slug',
        ]));

        if ($request->parent) {
            $category->parent_id = Category::where('ulid', $request->parent)->first()->id;
        }

        if (! $category->isDirty()) {
            return $this->errorResponse('Nothing to update!', 422);
        }

        $category->save();

        return $this->showOne(new CategoryResource($category), 201);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return $this->showOne($category);
    }
}
