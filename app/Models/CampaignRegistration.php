<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignRegistration extends Model
{
    public function campaign()
    {
        return $this->belongsTo(SpinWheelCampaign::class);
    }

    public function campaign_registration()
    {
        return $this->belongsTo(SpinWheelCampaign::class);
    }

    public function temp_campaign_point()
    {
        return $this->hasOne(TempCampaignPoint::class);
    }


}
