<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\Activitylog\Traits\LogsActivity;

class Instruction extends Model
{
    use SoftDeletes;

    protected $table = 'instructions';

    protected $primaryKey = 'id';

    protected static $logAttributes = ['*'];

    public function getInstructionId($name)
    {
        $res = Instruction::where('instructions.name',$name)->get();
        if(!empty($res[0])){
            return $res[0]->id;
        }
        else{
            return 0;
        }
    }

}
