<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'describe',
        'content',
        'slug',
        'image',
        'status',
        'category_post_id',
    ];
    
    public function categoryPost()
    {
        return $this->belongsTo('App\Models\CategoryPost');
    }

    public function users()
    {
        return $this->belongsTo('App\Model\User');
    }
}
