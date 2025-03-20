<?php

namespace App\Models;

use App\Models\Citation;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
    public function citation()
    {
        return $this->belongsToMany(Citation::class);
    }
}
