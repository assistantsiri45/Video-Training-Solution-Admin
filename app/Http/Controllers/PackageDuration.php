<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\OrderItem;

class PackageDuration extends Controller
{
    public function update(){
        set_time_limit(300);
        echo "hi";
        $package=Package::select('id','duration')->get();
        $i=0;
        foreach($package as $row){
            $package_id=$row->id;
            $data['package_duration']=$row->duration;
           // $orderItem=new OrderItem();
          //  $orderItem->package_duration=$duration;
          OrderItem::where('package_id',$package_id)->update($data);
            echo $i;
            $i++;

        }
    }
  
}
