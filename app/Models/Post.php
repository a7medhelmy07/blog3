<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes ;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'title',
        'content',
        'photo',
        'slug',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFeaturedAttribute($photo){
        return asset($photo);
    }
}
