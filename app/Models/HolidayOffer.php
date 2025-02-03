<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HolidayOffer extends Model
{
    const FLAT = 1;
    const FLAT_TEXT = "FLAT";
    const PERCENTAGE = 2;
    const PERCENTAGE_TEXT = "PERCENTAGE";
}
