<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
            protected $table = 'course_progress';

        protected $fillable = ['user_id', 'course_id', 'progress_percent','cmi_core_lesson_location','cmi_core_lesson_status'];
}
