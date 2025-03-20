<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Citation extends Model
{
    use HasApiTokens;
    protected $fillable = ['author', 'title', 'source', 'url', 'year', 'publisher', 'content', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, "citationtags")->withTimestamps();
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, "citationcategories")->withTimestamps();
    }
    public function likedByUsers(){
        return $this->belongsToMany(User::class , "likes")->withTimestamps();
    }
}
