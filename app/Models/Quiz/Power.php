<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Power extends Model
{
    use SoftDeletes;

    protected $table = 'power_mst';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];


    public function getPowerId($name)
    {
        $res = Power::where('power_mst.name',$name)->get();
        if(!empty($res[0])){
            return $res[0]->id;
        }
        else{
            return 0;
        }
    }

    public  static function getAllPower($id = "")
    {
        $query = Power::where('status','1');
        if($id != '')
        {
            $query->where('id',$id);
        }
        $result = $query->get();
        return $result;
    }
}
