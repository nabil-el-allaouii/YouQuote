<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return response()->json([
            'message'=>'tags',
            'data'=>$tags
        ]);
    }

    public function store(TagRequest $request)
    {
        $tag = Tag::create([
            'name'=>$request->name
        ]);
        return response()->json([
            'message'=>'tag created successfully'
        ]);
    }


    public function update(TagRequest $request, string $id)
    {
        $tag = Tag::find($id);
        if(!$tag){
            return response()->json([
                'message'=>'No tag Found!'
            ]);
        }
        $tag->update([
            'name'=>$request->name
        ]);
        return response()->json([
            'message'=>'tag updated Successfully',
            'tag:'=>$tag
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::find($id);
        if(!$tag){
            return response()->json([
                'message'=>'no tag found'
            ]);
        }
        $tag->delete();
        return response()->json([
            "message"=>"tag deleted successfully"
        ]);
    }
}
