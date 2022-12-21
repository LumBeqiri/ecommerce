<?php

namespace App\Http\Controllers\Admin\Category;

use App\Models\Category;
use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class AdminCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('id')->get();

        return $this->showAll(CategoryResource::collection($categories));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $newCategory = Category::create($request->validated());

        return $this->showOne(new CategoryResource($newCategory), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showOne(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CreateCategoryRequest $request
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {

        $category->fill($request->only([
            'name',
            'description',
            'slug'
        ]));

        if($request->parent){
            $category->parent_id = Category::where('uuid',$request->parent)->first()->id;
        }

         if(!$category->isDirty()){
            return $this->errorResponse("Nothing to update!", 422 );
        }

        $category->save();

        return $this->showOne(new CategoryResource($category), 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}
