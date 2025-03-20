<?php

namespace App\Models;

use App\Models\Citation;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];
    public function citation(){
        return $this->belongsToMany(Citation::class);
    }
}
