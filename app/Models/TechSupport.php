<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechSupport extends Model
{
    protected $table = 'tech_support';

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function actionby(){
        return $this->belongsTo(User::class,'actions_by');
    }

    public function attachments(){
        return $this->hasMany(TechSupportAttachment::class,'query_id');
    }
}
