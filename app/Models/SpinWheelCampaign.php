<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpinWheelCampaign extends Model
{
    public function registration()
    {
        return $this->hasone(CampaignRegistration::class);
    }

    public function spinWheelSegment()
    {
        return $this->hasMany(SpinWheelSegment::class);
    }
}
