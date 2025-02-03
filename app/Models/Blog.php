<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Blog extends Model
{
    use SoftDeletes;

    protected $dates = ['published_at'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return null;
        }

        return env('IMAGE_URL') . '/blogs/images/' . $this->image;
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }


    public function relatedBlogs()
    {
        return $this->belongsToMany(Blog::class, 'related_blogs', 'related_blog_id', 'blog_id');
    }

    public function relatedBlogsSync()
    {
        return $this->belongsToMany(Blog::class, 'related_blogs', 'blog_id', 'related_blog_id');
    }

    public function blogTagsSync()
    {
        return $this->belongsToMany(Blog::class, 'blog_tags_pivot', 'blog_id', 'tag_id');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOfPublished($query)
    {
        return $query->where('is_published', true);
    }

    public function publish()
    {
        if (! $this->is_published) {
            $this->is_published = true;
            $this->publisher_id = Auth::id();
            $this->published_at = Carbon::now();
        } else {
            $this->is_published = false;
            $this->publisher_id = null;
            $this->published_at = null;
        }

        $this->save();
    }
}
