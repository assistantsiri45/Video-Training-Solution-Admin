<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class UserTestPower extends Model
{
    use SoftDeletes;

    protected $table = 'user_test_power';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];



    public  static function getAllUserTestPower($id = "")
    {
        $query = UserTestPower::where('status','1');
        if($id != '')
        {
            $query->where('id',$id);
        }
        $result = $query->get();
        return $result;
    }
}
