<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;
    
    // public $timestamps = false;

    protected $fillable = [
        'title', 
        'description',
        'user_id',
        'user'
    ];

    protected $appends = ['hasComments'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComments::class, 'post_id');
    }

    public function getHasCommentsAttribute()
    {
        return $this->comments()->exists();
    }
}
