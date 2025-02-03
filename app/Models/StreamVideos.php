<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StreamVideos extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Stream Database Connection defined in the model.
     */
    protected $connection = 'stream_db';

    /**
     * Table name of the stream database videos table.
     */
    protected $table = 'video_url';
}
