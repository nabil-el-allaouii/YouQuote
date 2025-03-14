<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RandomCitation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'author'=>$this->author,
            'title'=>$this->title,
            'content'=>$this->content,
            'source'=>$this->source,
            'url'=>$this->url,
            'year'=>$this->year
        ];

    }
}
