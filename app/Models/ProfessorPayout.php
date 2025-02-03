<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessorPayout extends Model
{
    protected $fillable = [
        'professor_id','order_id','package_id','amount','percentage'
     ];
    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function Order()
    {
        return $this->belongsTo(Order::class);
    }

    public function Package()
    {
        return $this->belongsTo(Package::class);
    }
}
