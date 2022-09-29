<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateCategoryFormRequest;

class CategoryController extends Controller
{
    private $mCategory;

    public function __construct(Category $category)
    {
        $this->mCategory = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = $this->mCategory->getResults($request->name);

        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrUpdateCategoryFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrUpdateCategoryFormRequest $request)
    {
        $category = $this->mCategory->create($request->all());

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->mCategory->find($id);
        if (!$category)
            return response()->json(['Message' => 'Not found'], 404);

        return response()->json($category, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrUpdateCategoryFormRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrUpdateCategoryFormRequest $request, $id)
    {
        $category = $this->mCategory->find($id);
        if (!$category)
            return response()->json(['Message' => 'Not found'], 404);

        $category->update($request->all());
        
        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->mCategory->find($id);
        if (!$category)
            return response()->json(['Message' => 'Not found'], 404);

        $category->delete();
        
        return response()->json(['Success' => true], 204);
    }
}
