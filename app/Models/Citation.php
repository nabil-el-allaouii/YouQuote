<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Citation extends Model
{
    use HasApiTokens;
    protected $fillable = ['author','title','source','url','year','publisher','content','user_id'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
