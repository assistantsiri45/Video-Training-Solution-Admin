<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use SoftDeletes;

    const UNPUBLISHED = 1;
    const PUBLISHED = 2;

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function professor() {
        return $this->belongsTo(Professor::class);
    }
}
