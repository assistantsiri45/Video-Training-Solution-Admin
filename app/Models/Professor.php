<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Professor extends Model
{
    use SoftDeletes;
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url',
    ];

    const MAIL = 1;
    const MAIL_TEXT= "AUTO GENERATED";
    const MANUAL = 2;
    const MANUAL_TEXT = "MANUAL";
    const MANUAL_UPLOAD = 1;
    const MANUAL_UPLOAD_TEXT = "MANUAL UPLOAD";
    const YOUTUBE = 2;
    const YOUTUBE_TEXT= "YOUTUBE";


    public function students()
    {
        return $this->hasMany(Student::class)->using(Testimonial::class);
    }

    public function getImageUrlAttribute() {
        if ($this->image) {
            return env('IMAGE_URL').'/professors/images/'.$this->image;
        }

        return null;
    }


    public function getImageAttribute($value) {
        if (! $value) {
            return null;
        }

        return env('IMAGE_URL').'/professors/images/'.$value;
    }

    protected $guarded = ['id'];

    public function scopeSearch($query, $searchText)
    {
        return $query->where('name', 'LIKE', '%'.$searchText.'%');
    }

    public function free_resources()
    {
        return $this->hasMany(FreeResource::class);
    }
}
