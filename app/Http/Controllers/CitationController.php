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

    public function random(string $id)
    {
        $random = Citation::inRandomOrder()->take($id)->get();
        return response()->json([
            'status' => 'success',
            'random quote' => RandomCitation::collection($random)
        ]);
    }

    public function approve(request $request, string $id)
    {
        $valid = $request->validate([
            'status' => ['required', 'string']
        ]);
        $citation = Citation::find($id);
        if (!$citation) {
            return response()->json([
                'message' => 'there is no citation :/'
            ], 404);
        }
        $citation->status = $request->status;
        $citation->save();
        return response()->json([
            'message' => 'Citation Approved Successfully'
        ], 201);
    }

    public function filter(FilterCitation $request)
    {
        $min = $request->min;
        $max = $request->max;
        $citation = Citation::query();
        if ($min) {
            $citation = $citation->whereRaw('LENGTH(content) - LENGTH(REPLACE(content," ","")) + 1 >= ?', [$min]);
        }
        if ($max) {
            $citation = $citation->whereRaw('LENGTH(content) - LENGTH(REPLACE(content," ","")) + 1 <= ?', [$max]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $citation->get()
        ]);
    }
    public function visitorShow(string $id)
    {
        $citation = Citation::where('id', $id)->first();
        if (!$citation) {
            return $this->Error('NO Citation Found!', 404);
        }
        $citation->popularity += 1;
        $citation->save();
        return $this->Success([
            'status' => 'success',
            'citation' => new RandomCitation($citation)
        ]);
    }

    public function index(Request $request)
    {
        $citations = $request->user()->citations;
        if ($citations) {
            return $this->Success([
                "citations" => $citations,
                "status" => 'success'
            ]);
        } else {
            return $this->Error('no citations for you', 404);
        }
    }
    public function popular()
    {
        $citaions = Citation::selectRaw('*')->orderByDesc('popularity')->limit(3)->get();
        return $this->Success([
            'status' => 'success',
            'citation' => RandomCitation::collection($citaions)
        ], 'Popular Citations');
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
        if ($request->has('tags')) {
            $citation->tags()->sync($request->tags);
        }
        if ($request->has('categories')) {
            $citation->categories()->sync($request->categories);
        }
        return $this->Success([
            'status' => 'success',
            'user' => $request->user()->name
        ], 'citation created successfully');
    }
    public function like(string $id ,Request $request){
        $user = $request->user();
        $citation = Citation::find($id);
        if(!$citation){
            return response()->json([
                'message'=>'no citation were found :/'
            ]);
        }
        $user->likedCitations()->syncWithoutDetaching($citation->id);
        return response()->json([
            'message'=>'liked successfully :)'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        return $this->success([
            'status' => 'success',
            'data' => $request->user()->citations->where('id', $id)
        ]);
    }


    public function update(CitationUpdate $request, string $id)
    {
        $citation = Citation::find($id);
        if (!$citation) {
            return $this->Error('Citation Not Found', 404);
        }
        if ($request->user()->cannot('update', $citation)) {
            return response()->json([
                'message' => 'You Are not Authorized To update this citation'
            ], 401);
        }
        $citation->update([
            'author' => $request->author,
            'title' => $request->title,
            'source' => $request->source,
            'year' => $request->year,
            'url' => $request->url,
            'publisher' => $request->publisher,
            'content' => $request->content
        ]);
        return $this->Success([
            'status' => 'success',
            'data' => $citation
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $citation = Citation::find($id);
        if (!$citation) {
            return $this->Error('Citation Not Found', 404);
        }
        if ($request->user()->cannot('delete', $citation)) {
            return $this->Error('You Are Unauthorized to Delete This Citation', 401);
        }
        $citation->delete();
        return $this->Success([
            'message' => 'deleted Successfully'
        ]);
    }
}
