<?php

namespace App\Http\Controllers;

use App\Exports\CallRequestExport;
use App\Exports\SalesReportExport;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageVideo;
use App\Models\CustomizedPackage;
use App\Models\Professor;
use App\Models\SubjectPackage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
           $query = Order::with('student', 'payment', 'associate.user', 'orderItems.package','userAddress');

            return DataTables::of($query)
               ->filter(function($query) {

                    if(!(request()->filled('filter.search')) && !(request()->filled('filter.date')) && !(request()->filled('filter.status'))){
                       
                        $year = date('Y');
                        $month = date('m');
                        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
                        $query->whereDate('created_at', '>=', $startOfMonth);
                    }
                    if(!(request()->filled('filter.date')) && (request()->filled('filter.status'))){
                       
                        $year = date('Y');
                        $month = date('m');
                        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
                        $query->whereDate('created_at', '>=', $startOfMonth);
                    }
            


                   if (!empty(request('filter.search'))) {
                      //$query->whereHas('student.name','LIKE', '%'.request('filter.search').'%');

                       $query->whereHas('student', function($query) {
                          $query->where('name','LIKE', '%'.request('filter.search').'%')
                               ->orWhere('email','LIKE', '%'. request('filter.search').'%')
                              ->orWhere('phone','LIKE', '%'. request('filter.search').'%');

                        });
                    }
                   if (!empty(request('filter.search'))) {
                       $query->Orwhere('transaction_id',  '=', request('filter.search'));
                   }
                   if (!empty(request('filter.search'))) {
                       $query->Orwhere('net_amount','LIKE', '%'.request('filter.search').'%');
                   }
                   if (!empty(request('filter.search'))) {
                       $query->Orwhere('payment_status','LIKE', '%'.request('filter.search').'%');
                  }
                   if (!empty(request('filter.search'))) {
                       $query->Orwhere('orders.id','=', request('filter.search'));
                   }
//                    if (!empty(request('filter.date'))) {
//                        $query->whereDate('created_at', Carbon::parse(request('filter.date')));
//                    }
//
                   if (request()->filled('filter.status')) {
                       if (request()->input('filter.status') == '1') {
                           $query->where('payment_status', Order::PAYMENT_STATUS_SUCCESS);
                       }

                       if (request()->input('filter.status') == '0') {
                          $query->where('payment_status', Order::PAYMENT_STATUS_FAILED);
                      }
                   }
                           
                    if (request()->filled('filter.date')) {
                        $dateRange = request()->input('filter.date');
                        $explodedDates = explode(' - ', $dateRange);
                        $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
                        $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);
                       $from= date("Y-m-d",strtotime($fromDate)).''.' 00:00:00';
                      $to= date("Y-m-d",strtotime($toDate)).''.' 23:59:59';
                       $query->whereBetween('created_at', [$from, $to]);
                 }
                 if (request()->filled('p_date')){
                    
                    $query->whereHas('orderItems', function($query) {
                        if(request()->input('p_date')==1){
                        $query ->where('payment_status', OrderItem::PAYMENT_STATUS_FULLY_PAID)->where('is_prebook', true);
                        }
                        else{
                            $query ->where('payment_status', OrderItem::PAYMENT_STATUS_PARTIALLY_PAID)->where('is_prebook', true);
                        }
                    }) ; 
                    $query->whereDate('created_at', '>', Carbon::now()->subDays(7));
                 }

               })
              
               ->addColumn('order_id', function($query) {

                  return '<a class="a-row-details text-primary" data-id=' . $query->id . ' ><i class="fas fa-eye ml-3"></i></a>';
              })

               ->addColumn('student.name', function($query) {
                               
                   if(!empty($query->student->name)){
                       return $query->student->name;
                   }else{
                       return '-';

                    }
               })

               ->addColumn('student.email', function($query) {
                               
                   if(!empty($query->student->email)){
                       return $query->student->email;
                   }else{
                       return '-';
                   }
               })


              ->addColumn('student.phone', function($query) {
                               
                   if(!empty($query->student->phone)){
                       return $query->student->phone;
                   }else{
                       return '-';

                    }
                })

    
                ->addColumn('created_at', function($query) {
                  if(!empty($query->created_at)){
                      $datetime = explode(" ",$query->created_at);
                       $date = date("d-m-Y", strtotime($datetime[0]));
                       $date_time = $date.', '.$datetime[1];
                       return $date_time;
                   }else{
                       return '-';
                  }
                   
               })
               ->addColumn('net_amount', function($query) {
                   return $query->net_amount;
               })
               
               ->addColumn('payment_status', function($query) {
                   if(!empty($query->transaction_response_status)){
                      return $query->transaction_response_status;
                  }else{
                       return '-';
                  }
                })



               ->addColumn('payment_updated_method', function($query) {
                   if(!empty($query->updated_method)){
                       if($query->updated_method == 1){
                           return 'CCAVENUE';

                        }
                       if($query->updated_method == 2){
                           return 'MANUAL';

                        }
                       if($query->updated_method == 3){
                           return 'CRON';

                        }
                       if($query->updated_method == 4){
                           return 'EASEBUZZ';

                        }
                   }else{
                       return '-';
                 
                    }

                })
               ->addColumn('payment_mode', function($query) {
                  if(!empty($query->payment_mode)){
                       if($query->payment_mode == 1){
                           return 'ONLINE';
                       }
                       if($query->payment_mode == 2){
                           return 'CASH ON DELIVERY';
                       }
                       if($query->payment_mode == 3){
                           return 'PREPAID';

                        }
                   }else{
                       return '-';
                    }


                })


               ->addColumn('transaction_id',function($query){
                   if(!empty($query->transaction_id)){

                        return $query->transaction_id;
                   }else{
                       return '-';
                    }
               })

               ->addColumn('response', function($query) {
                   
                   return '<a class="a-response" data-id=' . $query->id . '><i class="fas fa-eye"></i></a>';
                     

                })


               ->addColumn('action', 'pages.orders.action')
              ->rawColumns(['response', 'status','action','associate_id','packagename','course','level','subject','chapter',
               'language','validity','content_delivery','study_material','packageprice','id','professors','mode_of_lecture','study_material_fees','is_pendrive','order_id'])

                ->make(true);
        }

        $table = $builder->columns([
         
           ['data' => 'id', 'name' => 'id', 'title' => 'Order ID','defaultContent' => ''],
          ['data' => 'student.id', 'name' => 'student.id', 'title' => 'Student ID', 'defaultContent' => ''],
           ['data' => 'student.name', 'name' => 'student.name', 'title' => 'Name', 'defaultContent' => ''],
           ['data' => 'student.phone', 'name' => 'student.phone', 'title' => 'Phone Number','defaultContent' => ''],
           ['data' => 'student.email', 'name' => 'student.email', 'title' => 'Email Address', 'defaultContent' => ''],
           ['data' => 'net_amount','name' => 'net_amount', 'title' => 'Amount'],
           ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status'],
           ['data' => 'payment_mode', 'name' => 'payment_mode', 'title' => 'Payment Type'],
           ['data' => 'payment_updated_method', 'name' => 'payment_updated_method', 'title' => 'Payment Mode'],
          ['data' => 'transaction_id', 'name' => 'transaction_id', 'title' => 'Transaction Id'],
          ['data' => 'response', 'name' => 'response', 'title' => 'Response'],
           ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date & Time'],
           // ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, ],
           ['data' => 'order_id', 'name' => 'order_id', 'title' => '', 'class'=>'details-control','id'=>'id','orderable' => false,],

        ])->parameters([
            'searching' => false,
           'stateSave'=>true,
            'ordering' => true,
            'lengthChange' => false,
           'pageLength'=>15,
           'bInfo' => true

        ])->orderBy(0, 'desc');

        return view('pages.sales.index', compact('table'));
    }


    public function export()
    {
        $search = request()->input('export_search') ?? '';
        $fromDate = null;
        $toDate = null;
        $status = null;

        if (request()->filled('export_created_at')) {
            $dateRange = request()->input('export_created_at');
            $explodedDates = explode(' - ', $dateRange);
            $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
            $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);
        }

        if (request()->input('export_status') == '1') {
            $status = Order::PAYMENT_STATUS_SUCCESS;
        }

        if (request()->input('export_status') == '0') {
            $status = Order::PAYMENT_STATUS_FAILED;
        }

        return Excel::download(new SalesReportExport($search, $fromDate, $toDate, $status), 'SALES_REPORT' . time() . '.xlsx');
    }
    public function fetchSaleDetails(){

        $order = Order::findOrFail(request()->input('id'));

        $order_items = OrderItem::with('package')->where('order_id',request()->input('id'))->get();
        $i=1;
        $packageIDs=[];
        $data['course'] = $data['level']=$data['subject']=$data['chapter']=$data['language']=$data['professors']=$data['mode_of_lecture']=$data['package']=$data['package_validity']='';
        $data['study_material']=$data['study_material_price']=$data['is_pendrive']=$data['pendrive_price']=$data['reward_amount']=$data['coupon_amount']=$data['expiry_date']=$data['packagetype']='';
        $data['holiday_offer_amount']=$data['net_amount']=$data['address']=$data['transaction_id']=$data['invoice_no']= $data['created_at']=$data['order_id']=$data['package_duration']='';
        $data['gross_amount']=0;
        foreach($order_items as $val2){

            if(!empty($val2->package->name)){
                $data['package'].=$i.') ';
                $data['package'].=$val2->package->name .' <br> ';                               
            }else{
                $data['package'].='- <br> ';
            }                            
            if(!empty($val2->package->course->name)){
                $data['course'].=$i.') ';
                $data['course'].=$val2->package->course->name .' <br> ';                               
            }else{
                $data['course'].='- <br> ';
            }

            if(!empty($val2->package->level->name)){
                $data['level'].=$i.') ';
                $data['level'].=$val2->package->level->name .' <br> ';                               
            }else{
                $data['level'].='- <br> ';
            }
            if(!empty($val2->package->packagetype->name)){
                $data['packagetype'].=$i.') ';
                $data['packagetype'].=$val2->package->packagetype->name .' <br> ';                               
            }else{
                $data['packagetype'].='- <br> ';
            }

            if(!empty($val2->package->subject->name)){
                $data['subject'].=$i.') ';
                $data['subject'].=$val2->package->subject->name .' <br> ';                               
            }else{
                $data['subject'].='- <br> ';
            }

            if(!empty($val2->package->chapter->name)){
                $data['chapter'].=$i.') ';
                $data['chapter'].=$val2->package->chapter->name .' <br> ';                               
            }else{
                $data['chapter'].='- <br> ';
            }

            if(!empty($val2->package->language->name)){
                $data['language'].=$i.') ';
                $data['language'].=$val2->package->language->name .' <br> ';                               
            }else{
                $data['language'].='- <br> ';
            }

            if ($val2->item_type==2) {
                $data['study_material'].=$i.') '.'Yes <br>';
                $data['study_material_price'].=$i.') '.$val2->price.'<br>';
            }
            else{
                $data['study_material'].=$i.') '.'No <br> ';
                $data['study_material_price'].=$i.') '.'-<br> ';
            }

            if(!empty($val2->package)){
                $data['mode_of_lecture'].=$i.') ';
                if($val2->package->pendrive==true){
                    $data['mode_of_lecture'].=OrderItem::PENDRIVE_TEXT.'<br>';
                }
                elseif($val2->package->g_drive == true){
                    $data['mode_of_lecture'].=OrderItem::G_DRIVE_TEXT.'<br>';
                }
                else{
                    $data['mode_of_lecture'].=OrderItem::ONLINE_TEXT.'<br>';
                }
               
            }else{
                $data['mode_of_lecture'].='- <br> ';
            }

            if(!empty($val2->package->expiry_type)){
                $data['package_validity'].=$i.') ';
                if($val2->package->expiry_type == 1){
                    $data['package_validity'].=$val2->package->expiry_month .' '.'Months<br> ';
                }elseif($val2->package->expiry_type == 2){
                    $data['package_validity'].=$val2->package->expire_at .'<br> ';
                }
            }else{
                $data['package_validity'].=$i.') ';
                if(!empty($val2->package->expire_at)){
                    $data['package_validity'].=$val2->package->expire_at .' <br> ';                               
                }else{
                    $data['package_validity'].='9 Months<br> ';
                }
            }

            if(!empty($val2->expire_at)){
                $data['expiry_date'].=$i.')'.$val2->expire_at.'<br>';
            }else{
                $data['expiry_date'].='- <br>';
            }

            if(!empty($val2->package_duration)){
                $data['package_duration'].=$i.')'.$val2->package_duration.'<br>';
            }else{
                $data['package_duration'].='-<br>';
            }


            $data['gross_amount']= $data['gross_amount'] +$val2->price;

            ++$i;
        }
        if(!empty($order_items)){
            foreach($order_items as $val2){
                if(!empty($val2->package->type)){
                    //$data['professors'].=$i.')';
                    if($val2->package->type == 1){
                        $packageIDs[] = $val2->package->id;
                    }
                    
                    if($val2->package->type == 2){
                        $packageIDss = SubjectPackage::where('package_id', $val2->package->id)->get()->pluck('chapter_package_id');
                        foreach($packageIDss as $packages){
                            $package = Package::find($packages);
    
                            $packageIDs[]=$package->id;
                        }
                    }

                    if($val2->package->type == 3){
                        $selectedPackageIDs = CustomizedPackage::where('package_id', $val2->package->id)->get()->pluck('selected_package_id');
                        foreach ($selectedPackageIDs as $selectedPackageID) {

                            $package = Package::find($selectedPackageID);
            
                            if ($package->type == 1) {
                                $packageIDs[] = $package->id;
                            }
            
                            if ($package->type == 2) {
                                $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');
            
                                foreach ($chapterPackageIDs as $chapterPackageID) {
                                    $package = Package::find($chapterPackageID);
                                    $packageIDs[] = $package->id;
                                }
                            }
                        }
                   }                   
                }
            }
                $professorIDs = PackageVideo::whereIn('package_id', $packageIDs)->with('video')->get()->pluck('video.professor_id')->unique();
                //$professorIDs = array_unique($professorIDs);
                $professors = Professor::whereIn('id', $professorIDs)->get();


                foreach($professors as $professor){
                    if($professors->last()==$professor){
                        $data['professors'].= $professor->name;

                    }else{ 
                    $data['professors'].= $professor->name.',';
                    }
                    
                }
        }
        else{
            $data['professors'].= '- <br>';
        }

        if(!empty($order->pendrive_price)){
            $data['is_pendrive'] .= 'Yes';
            $data['pendrive_price'] = $order->pendrive_price;
        }else{
            $data['is_pendrive'] .= 'No';
            $data['pendrive_price'] = '-';
        }

        if($order->reward_amount){
            $data['reward_amount']=$order->reward_amount;
        }else{
            $data['reward_amount']='-';
        }

        if($order->coupon_amount){
            $data['coupon_amount']=$order->coupon_amount;
        }else{
            $data['coupon_amount']='-';
        }

        if($order->holiday_offer_amount){
            $data['holiday_offer_amount']=$order->holiday_offer_amount;
        }else{
            $data['holiday_offer_amount']= '-';
        }

        if(!empty($order->net_amount)){
            $data['net_amount']=$order->net_amount;
        }else{
            $data['net_amount']='-';
        }

        if(!empty($order->address)){
            $data['address']=$order->address;
        }else{
            $data['address']='-';
        }

        if ($order->transaction_id) {
            $data['transaction_id']= $order->transaction_id;
        }else{
            $data['transaction_id']='';
        }

        if(!empty($order->payment->receipt_no)){
            $data['invoice_no']=$order->payment->receipt_no;
        }else{
            $data['invoice_no']='-';
        }

        if(!empty($order->created_at)){
            $datetime = explode(" ",$order->created_at);
            $date = date("d-m-Y", strtotime($datetime[0]));
            $date_time = $date.', '.$datetime[1];
            $data['created_at']=$date_time;
        }else{
            $data['created_at']='-';
        }

        if(!empty($order->id)){
            $data['order_id']=$order->id;
        }else{
            $data['order_id']='';
        }


        return $data;
    }

}
