<?php

namespace App;

use App\Models\Package;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AskAQuestion extends Model
{
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class, 'question_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
