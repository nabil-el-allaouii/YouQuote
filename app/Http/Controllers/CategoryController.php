<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Citation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cateogries = Category::all();
        return response()->json([
            'message'=>'Categories',
            'data'=>$cateogries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'name'=>$request->name
        ]);
        return response()->json([
            'message'=>'Category Created successfully!'
        ],201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                'message'=>'No Category Found'
            ]);
        }
        $category->update([
            'name'=>$request->name
        ]);
        return response()->json([
            'message'=>'category Updated Successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                'message'=>'No Category Foune :/'
            ],404);
        }
        $category->delete();
        return response()->json([
            'message'=>'category Deleted Successfully'
        ]);
    }
}
