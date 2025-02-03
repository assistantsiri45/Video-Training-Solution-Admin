<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomTestimonial extends Model
{
    use SoftDeletes;

    const UNPUBLISHED = 1;
    const PUBLISHED = 2;

    public function getImageUrlAttribute() {
        if ($this->image) {
            return env('IMAGE_URL').'/custom_testimonials/'.$this->image;
        }

        return null;
    }
}
