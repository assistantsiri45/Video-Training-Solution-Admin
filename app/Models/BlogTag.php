<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    public function blogTags()
    {
        return $this->belongsToMany(Blog::class, 'blog_tags_pivot', 'tag_id', 'blog_id');
    }
}
