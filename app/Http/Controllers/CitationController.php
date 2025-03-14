<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitationRequest;
use App\Http\Requests\CitationUpdate;
use App\Http\Requests\FilterCitation;
use App\Http\Resources\RandomCitation;
use App\HttpResponse;
use App\Models\Citation;
use Illuminate\Http\Request;

class CitationController extends Controller
{
    use HttpResponse;

    public function random(string $id){
        $random = Citation::inRandomOrder()->take($id)->get();
        return response()->json([
            'status'=>'success',
            'random quote'=>RandomCitation::collection($random)
        ]);
    }

    public function filter(FilterCitation $request){
        $min = $request->min;
        $max = $request->max;
        $citation = Citation::query();
        if($min){
            $citation = $citation->whereRaw('LENGTH(content) - LENGTH(REPLACE(content," ","")) + 1 >= ?',[$min]);
        }
        if($max){
            $citation = $citation->whereRaw('LENGTH(content) - LENGTH(REPLACE(content," ","")) + 1 <= ?' ,[$max]);
        }
        return response()->json([
            'status'=> 'success',
            'data'=>$citation->get()
        ]);
    }
    public function visitorShow(string $id){
        $citation = Citation::where('id',$id)->first();
        if(!$citation){
            return $this->Error('NO Citation Found!',404);
        }
        $citation->popularity += 1;
        $citation->save();
        return $this->Success([
            'status'=>'success',
            'citation'=>new RandomCitation($citation)
        ]);
    }
    
    public function index(Request $request)
    {
        $citations = $request->user()->citations;
        if($citations){
            return $this->Success([
                "citations" => $citations,
                "status" => 'success'
            ]);
        }else{
            return $this->Error('no citations for you' , 404);
        }
    }
    public function popular(){
        $citaions = Citation::selectRaw('*')->orderByDesc('popularity')->limit(3)->get(); 
        return $this->Success([
            'status'=>'success',
            'citation'=>RandomCitation::collection($citaions)
        ],'Popular Citations');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CitationRequest $request)
    {
        $citation = Citation::create([
            'author' => $request->author,
            'title' => $request->title,
            'source' => $request->source,
            'year' => $request->year,
            'url' => $request->url,
            'publisher' => $request->publisher,
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);
        return $this->Success([
            'status'=>'success',
            'user' => $request->user()->name
        ],'citation created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id,Request $request)
    {
        return $this->success([
            'status'=>'success',
            'data'=> $request->user()->citations->where('id' , $id)
        ]);
    }

    
    public function update(CitationUpdate $request, string $id)
    {
        $citation = $request->user()->citations->where('id' , $id)->first();
        if(!$citation){
            return $this->Error('Citation Not Found' , 404);
        }
        $citation->update([
            'author'=> $request->author,
            'title'=> $request->title,
            'source'=> $request->source,
            'year'=> $request->year,
            'url'=> $request->url,
            'publisher'=>$request->publisher,
            'content'=> $request->content
        ]);
        return $this->Success([
            'status'=> 'success',
            'data'=> $citation
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $citation = $request->user()->citations->where('id' , $id)->first();
        $citation->delete();
        return $this->Success([
            'message' => 'deleted Successfully'
        ]);
    }
}
