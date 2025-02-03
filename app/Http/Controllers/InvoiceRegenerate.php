<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Mail\OrderPlaced;
use App\Mail\OrderStatusFailed;
use App\Mail\PurchaseMail;
use App\Mail\PurchaseMailAdmin;
use App\Mail\PurchaseMailRegenerate;
use App\Models\Associate;
use App\Models\Cart;
use App\Models\JMoney;
use App\Models\JMoneySetting;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PaymentOrderItem;
use App\Models\PaymentTransaction;
use App\Models\Professor;
use App\Models\Payment;
use App\Models\ProfessorRevenue;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudyMaterialOrderLog;
use App\Models\PurchaseMailNotification;
use App\Models\TempCampaignPoint;
use App\Models\User;
use App\Models\ProfessorPayout;
use App\Models\Coupon;
use App\Notifications\OrderCreated;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Mockery\Exception;
use App\Services\PaymentService;
use App\Services\ProfessorRevenueService;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use PDF;
use App\Models\HolidayOffer;

class InvoiceRegenerate extends Controller
{
     /** @var OrderService */
     var $orderService;

     /** @var PaymentService $paymentService */
     var $paymentService;
 
     /** @var ProfessorRevenueService $professorRevenueService */
     var $professorRevenueService;
 
     /**
      * OrderController constructor.
      * @param OrderService $service
      * @param PaymentService $paymentService
      * @param ProfessorRevenueService $professorRevenueService
      */
     public function __construct(OrderService $service, PaymentService $paymentService, ProfessorRevenueService $professorRevenueService)
     {
         $this->orderService = $service;
         $this->paymentService = $paymentService;
         $this->professorRevenueService = $professorRevenueService;
     }
     public function index(){
     
        return view('pages.invoice.create');
     }
     public function search(Builder $builder){
    
        if (request()->ajax()) {
        $start_date=date("Y-m-d H:i:s",strtotime(request('filter.start_date')));
        $end_date=date("Y-m-d H:i:s",strtotime(request('filter.end_date')));
       $query=Order::with('student')->where('created_at','>=',$start_date)->where('created_at','<=',$end_date)->where('payment_status',1)->orderBy('id','desc')->get();
        return DataTables::of($query)
        ->addColumn('net_amount', function($query) {
            return $query->net_amount;
        })
        ->addColumn('gross_amount',function($query){
            if($query->net_amount !=0){
                if(!empty($query->igst_amount) && $query->igst_amount != 0){
                    

                    return number_format($query->net_amount-$query->igst_amount,2);
                }
                else{
               // return $query->net_amount-($query->sgst_amount+$query->cgst_amount);
                return number_format($query->net_amount-($query->sgst_amount+$query->cgst_amount),2);
                }
            }else{
                return 0;
            }
        })
        ->addColumn('reward_amount', function($query) {
            if($query->reward_amount){
                return $query->reward_amount;
            }
            return '-';
        })
        ->addColumn('coupon_amount', function($query) {
            if($query->coupon_amount){
                return $query->coupon_amount;
            }
            return '-';
        })
        ->addColumn('created_at', function($query) {
            return $query->created_at->toDayDateTimeString();
        })

        // ->addColumn('status', function($query) {
        //     $st=PurchaseMailNotification::where('user_id',$query->user_id)->where('order_id',$query->id)->where('status','LIKE','Success')->get();
        //     if(count($st)>0){
        //     return 'Regenerated';
        //     }
        //     else{
        //     return '';
        //     }
        // })
        ->setRowClass(function ($query) {
            $st=PurchaseMailNotification::where('user_id',$query->user_id)->where('order_id',$query->id)->where('status','LIKE','Success')->get();
            if(count($st)>0){
                return 'mail-success';
            }
        })
        ->editColumn('select', static function ($query) {
            return '<input type="checkbox" name="packages[]"  value="'.$query->id.'"/>';
        })
        ->rawColumns(['select'])
      
       ->make(true);
    }
    $checkbox = '<div class="custom-control custom-checkbox text-center">
    <input id="published_select_all" class="custom-control-input select_all" name="select_all" type="checkbox">
    <label for="published_select_all" class="custom-control-label"></label>
</div>';


$html = $builder->columns([
    ['data' => 'select', 'name' => 'id', 'title' => $checkbox],
         ['data' => 'id', 'name' => 'id', 'title' => 'Order Id'],
            ['data' => 'student.name', 'name' => 'student.name', 'title' => 'Student Name'],
            ['data' => 'student.email', 'name' => 'student.email', 'title' => 'Email'],
            ['data' => 'student.phone', 'name' => 'student.phone', 'title' => 'Phone'],
            ['data' => 'gross_amount','name' => 'gross_amount', 'title' => 'Gross Amount'],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount'],
            ['data' => 'reward_amount', 'name' => 'reward_amount', 'title' => 'J-koins'],
            ['data' => 'coupon_amount', 'name' => 'coupon_amount', 'title' => 'Coupon Amount'],
            //['data' => 'status', 'name' => 'status', 'title' => 'Regenerated'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At']
   
])->parameters([
    'searching' => false,
    'ordering' => false,
    'lengthChange' => true,
    'bInfo' => true,
]);

     
return view('pages.invoice.create', compact('html'));
     }


     public function update(Request $request){
       
        $selectedOrders = $request->input('selected');
 
      if(count($selectedOrders)>0){ 
        foreach ($selectedOrders as $selectedOrders){
           
            $order_id= $selectedOrders;
            $update_order = Order::find($order_id);
            // $update_order->transaction_id = $response_parameters['transaction_id'];
          
            
            $user = User::find($update_order->user_id);

         /*   if (  $update_order->payment_status == 5) {
                $update_order->transaction_response_status ='Success';
                
            $update_order->payment_status = 1;
            $associate = Associate::where('user_id', $update_order->associate_id)->first();
            if ($associate) {
                $associateCommission = Setting::where('key', 'associate_commission')->first();
                $commission = $associate->commission ?? $associateCommission->value ?? 0;
                $commission = ($commission / 100) * $update_order->net_amount;
                $update_order->commission = $commission;
            } else {
                $order = Order::find($order_id);
                $order_items = OrderItem::where('order_id', $order->id)->pluck('package_id');
                $packages = Package::with('subject','course','level','chapter','language')->whereIn('id', $order_items)->get();
                $order_items_details = OrderItem::select('package_id','discount_amount','package_discount_amount','price')->where('order_id', $order->id)->get()->toArray();
                $study_material=OrderItem::where('order_id', $order->id)->where('item_type', 2)->pluck('package_id');
  

                $order_details = Student::where('user_id', '=', $order->user_id)->first();
                if(count($study_material)>0){
          
                    $study_material_price=Package::select(DB::raw('sum(study_material_price) as total'))->whereIn('id',$study_material)->first();
                    $order_details['stdy_material_parice']=$study_material_price->total;
                    $order_details['item_type']=2;
                }else{
                    $order_details['item_type']=1;
        
                }
               
                $order_details['order_id'] = $order->id;
                $order_details['net_amount'] = $order['net_amount'];
                $order_details['packages'] = $packages;
                $order_details['coupon_amount'] = $order['coupon_amount'];
                $order_details['coupon_code'] = $order['coupon_code'];
                if(@$update_order->coupon_id){
           $couponcode=Coupon::where('id',$update_order->coupon_id)->first();
                  $order_details['coupon_code'] = $couponcode->name;
    
                }
                $order_details['pendrive_price'] = $order['pendrive_price'];
                $order_details['reward_amount'] = $order['reward_amount'];
                if ($order['cgst']) {
                    $order_details['cgst'] = $order['cgst'];
                    $order_details['cgst_amount'] = $order['cgst_amount'];
                }
                if ($order['sgst']) {
                    $order_details['sgst'] = $order['sgst'];
                    $order_details['sgst_amount'] = $order['sgst_amount'];
                }
                if ($order['igst']) {
                    $order_details['igst'] = $order['igst'];
                    $order_details['igst_amount'] = $order['igst_amount'];
                }
                try {
                    
                   // $notification = new OrderCreated($user);
                   // Notification::route('sms', $user->phone)->notify($notification);
                    $admin_mail = Setting::where('key', 'admin_email')->first();
                    $order_details['admin_email'] = $admin_mail->value ;  
                    $order_details['address'] = $order['address'];
                    $order_details['phone'] = $order['phone'];
                    $order_details['location'] = $order['city'];
                    $order_details['order_items_details'] = $order_items_details;
                   
                  Mail::send(new PurchaseMailAdmin($order_details));
//                  $attributes['logo'] = env('WEB_URL') . '/assets/images/logo.png';
//                  $attributes['web'] = env('WEB_URL');
//                  $attributes['image_url'] = env('ADMIN_URL'). '/storage/packages/';
// $attributes=$order_details;
                // return view('emails.purchase_success_email_regenerate',compact('attributes'));
                 


                
                } catch (\Exception $exception) {
               
                  //info($exception->getMessage());
                }
            }
            if(@$update_order->coupon_id){
          
                $couponcode=Coupon::where('id',$update_order->coupon_id)->first();
              

                $update_order->coupon_code = $couponcode->name;

            }

            $order_items = OrderItem::where('order_id', $order_id)->get('package_id');

            $package_price = $order['net_amount'];
            foreach ($order_items as $order_item) {
                $package = Package::find($order_item->package_id);
                $professors  = $package['professors'];
                foreach ($professors as $professor) {
                    if ($package['professor_revenue']) {
                        $professor_revenue = $package['professor_revenue'];
                    } elseif ($professor['professor_revenue']) {
                        $professor_revenue = $professor['professor_revenue'];
                    } else {
                        $global_settings = Setting::where('key', 'professor_revenue')->first();
                        $professor_revenue = $global_settings->value;
                    }
                    $professor_revenue_percentage = $professor_revenue / 100;
                    $total_professors = count($professors);

                    $professor_payout = ProfessorPayout::updateOrCreate([
                        'professor_id' => $professor['id'],
                        'order_id' => $order_id,
                        'package_id' => $package->id,
                        'amount' => ($professor_revenue_percentage / $total_professors) * $package_price,
                        'percentage' => $professor_revenue,
                    ]);
                    $professor_payout->save();
                }
            }

            $orderItems = OrderItem::where('order_id', $order_id)->get();
            foreach ($orderItems as $orderItem) {
                if ($orderItem->is_prebook && !$package->is_prebook_package_launched) {
                    $orderItem->payment_status = OrderItem::PAYMENT_STATUS_PARTIALLY_PAID;
                } else {
                    $orderItem->payment_status = OrderItem::PAYMENT_STATUS_FULLY_PAID;
                }

                if ($orderItem->item_type == OrderItem::ITEM_TYPE_STUDY_MATERIAL) {
                    $orderItem->order_status = OrderItem::STATUS_ORDER_PLACED;

                    $studyMaterialOrderLog = new StudyMaterialOrderLog();
                    $studyMaterialOrderLog->order_item_id = $orderItem->id;
                    $studyMaterialOrderLog->status = StudyMaterialOrderLog::STATUS_ORDER_PLACED;
                    $studyMaterialOrderLog->save();

                    $package = Package::find($orderItem->package_id);
                    $user = User::with('address')->find($orderItem->user_id);

                    try {
                        Mail::send(new OrderPlaced([
                            'package_id' => $package->id,
                            'package_name' => $package->name,
                            'order_id' => $orderItem->order_id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'address' => optional($user->address)->address ?? '',
                            'area' => optional($user->address)->area ?? '',
                            'landmark' => optional($user->address)->landmark ?? '',
                            'city' => optional($user->address)->city ?? '',
                            'state' => optional($user->address)->state ?? '',
                            'pin' => optional($user->address)->pin ?? '',
                        ]));
                    } catch (\Exception $exception) {
                       //info($exception->getMessage());
                    }
                }

                $orderItem->save();
            }


            if ($update_order->spin_wheel_reward_id) {
                $tempCampaignPoint = TempCampaignPoint::find($update_order->spin_wheel_reward_id);
                $tempCampaignPoint->is_used = 1;
                $tempCampaignPoint->order_id = $update_order->id;
                $tempCampaignPoint->save();
            }

            if ($update_order->reward_id && $update_order->reward_amount) {
                $jMoney = JMoney::find($update_order->reward_id);

                if ($jMoney) {
                    if ($jMoney->points < $update_order->reward_amount) {
                        $jMoney->points = 0;
                        $jMoney->is_used = true;
                        $jMoney->save();
                    }

                    if ($jMoney->points > $update_order->reward_amount) {
                        $jMoney->points = ($jMoney->points - $update_order->reward_amount);
                        $jMoney->save();
                    }
            
                    if ($jMoney->points == $update_order->reward_amount) {
                        $jMoney->points = 0;
                        $jMoney->is_used = true;
                        $jMoney->save();
                    }
                }
            }


            $orderExist = Order::where('user_id', $update_order->user_id)->where('payment_status', 1)->first();
            if (!$orderExist) {
                $jMoney = new JMoney();
                $jMoney->user_id = $update_order->user_id;
                $jMoney->activity = JMoney::FIRST_PURCHASE;
                $jMoney->points = JMoneySetting::first()->first_purchase_point ?? null;
                $jMoney->expire_after = JMoneySetting::first()->first_purchase_point_expiry ?? null;
                $jMoney->expire_at = Carbon::now()->addDays($jMoney->expire_after);
                $jMoney->save();
            }

            Cart::where('user_id', $update_order->user_id)->delete();
            Cart::where('user_id', $update_order->associate_id)->delete();
            Cart::where('user_id', $update_order->branch_manager_id)->delete();
      
            

        //        $update_order->updated_by = $user->id;
        $update_order->updated_method = Order::UPDATE_METHOD_MANUAL;
        $update_order->updated_ip_address = request()->ip();
        $transactionResponse=array('order_id'=>$order_id,'order_status'=>'Success','tracking_id' => null,'amount'=>'1000','created_at'=>$update_order->created_at);
        $update_order->transaction_response = $transactionResponse;

        $update_order->update();

        DB::commit();

       // $transactionResponse = $request->input('transaction_response');
       // $transactionResponse = (json_decode($update_order, true));
     
    
        $payment = $this->paymentService->createpayment($transactionResponse);

        $orderItems = OrderItem::where('order_id', $order_id)->get();

        foreach ($orderItems as $orderItem) {
            $paymentOrderItem = new PaymentOrderItem;
            $paymentOrderItem->payment_id = $payment['id'];
            $paymentOrderItem->order_item_id = $orderItem->id;
            $paymentOrderItem->is_balance_payment = false;
            $paymentOrderItem->save();

            if ($orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID || $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                try {
                    $netAmount = null;

                    if (!$orderItem->is_prebook) {
                        $netAmount = $orderItem->price;
                    }

                    if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
                        $netAmount = $orderItem->booking_amount;
                    }

                    if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                        $netAmount = $orderItem->balance_amount;
                    }

                    $this->professorRevenueService->storeprofessorrevenue([
                        'package_id' => $orderItem->package_id,
                        'net_amount' => $netAmount,
                        'invoice_id' => $payment->receipt_no,
                        'invoice_date' => $payment->created_at
                    ]);
                } catch (Exception $exception) {
//                    info('PROFESSOR REVENUE SERVICE EXCEPTION: ' . $exception->getMessage());
                }
            }
        }
    }else{*/
        $update_order->transaction_response_status ='Success';
                
        $update_order->payment_status = 1;
        $associate = Associate::where('user_id', $update_order->associate_id)->first();
        if ($associate) {
            $associateCommission = Setting::where('key', 'associate_commission')->first();
            $commission = $associate->commission ?? $associateCommission->value ?? 0;
            $commission = ($commission / 100) * $update_order->net_amount;
            $update_order->commission = $commission;
        } else {
            $order = Order::find($order_id);
            $order_items = OrderItem::where('order_id', $order->id)->pluck('package_id');
            $packages = Package::with('subject','course','level','chapter','language')->whereIn('id', $order_items)->get();
            $order_items_details = OrderItem::select('package_id','discount_amount','package_discount_amount','price')->where('order_id', $order->id)->get()->toArray();
            $study_material=OrderItem::where('order_id', $order->id)->where('item_type', 2)->pluck('package_id');


            $order_details = Student::where('user_id', '=', $order->user_id)->first();
            if(count($study_material)>0){
      
                $study_material_price=Package::select(DB::raw('sum(study_material_price) as total'))->whereIn('id',$study_material)->first();
                $order_details['stdy_material_parice']=$study_material_price->total;
                $order_details['item_type']=2;
            }else{
                $order_details['item_type']=1;
    
            }
           
            $order_details['order_id'] = $order->id;
            $order_details['net_amount'] = $order['net_amount'];
            $order_details['packages'] = $packages;
            $order_details['coupon_amount'] = $order['coupon_amount'];
            $order_details['coupon_code'] = $order['coupon_code'];
            if(@$update_order->coupon_id){
       $couponcode=Coupon::where('id',$update_order->coupon_id)->first();
              $order_details['coupon_code'] = $couponcode->name;

            }
            $order_details['pendrive_price'] = $order['pendrive_price'];
            $order_details['reward_amount'] = $order['reward_amount'];
            if ($order['cgst']) {
                $order_details['cgst'] = $order['cgst'];
                $order_details['cgst_amount'] = $order['cgst_amount'];
            }
            if ($order['sgst']) {
                $order_details['sgst'] = $order['sgst'];
                $order_details['sgst_amount'] = $order['sgst_amount'];
            }
            if ($order['igst']) {
                $order_details['igst'] = $order['igst'];
                $order_details['igst_amount'] = $order['igst_amount'];
            }
            try {
                
               // $notification = new OrderCreated($user);
               // Notification::route('sms', $user->phone)->notify($notification);
                // $admin_mail = Setting::where('key', 'admin_email')->first();
                $admin_mail = Setting::where('key', 'admin_email')->first();
                $bcc = $special_bcc = '';
                $bcc_ids = $special_bcc_ids = $email_bcc = [];
                $bcc_setting = Setting::where('key', 'email_bcc')->first();
                $bcc = $bcc_setting->value;
                if (!empty($bcc_setting->value)) {
                    $bcc_ids = explode(",", $bcc);
                }
                $special_bcc_settings = Setting::where('key', 'special_bcc')->first();
                $special_bcc = $special_bcc_settings->value;
                if (!empty($special_bcc) && !empty($bcc_ids)) {
                    $special_bcc_ids = explode(",", $special_bcc);
                    $email_bcc = array_merge($bcc_ids, $special_bcc_ids);
                } else {
                    $email_bcc = $bcc_ids;
                }
                
                $order_details['admin_email'] = $admin_mail->value ;  
                $order_details['address'] = $order['address'];
                $order_details['phone'] = $order['phone'];
                $order_details['location'] = $order['city'];
                $order_details['order_items_details'] = $order_items_details;
                $order_details['email_bcc'] = $email_bcc;
                $order_details['email_bcc_user'] = $bcc_ids;
               
              Mail::send(new PurchaseMailAdmin($order_details));
              $purchasemailnotification=new PurchaseMailNotification();
            $purchasemailnotification->user_id=$order->user_id;
            $purchasemailnotification->order_id=$order_id;
            $purchasemailnotification->subject='purchase mail admin';

            $purchasemailnotification->status='Success';
           
            $purchasemailnotification->save();
//                  $attributes['logo'] = env('WEB_URL') . '/assets/images/logo.png';
//                  $attributes['web'] = env('WEB_URL');
//                  $attributes['image_url'] = env('ADMIN_URL'). '/storage/packages/';
// $attributes=$order_details;
            // return view('emails.purchase_success_email_regenerate',compact('attributes'));
              


            
            } catch (\Exception $exception) {
               
                   // info($exception->getMessage());
            }
        }
        if(@$update_order->coupon_id){
      
            $couponcode=Coupon::where('id',$update_order->coupon_id)->first();
          

            $update_order->coupon_code = $couponcode->name;

        }


        $order_items = OrderItem::where('order_id', $order_id)->get('package_id');

        $package_price = $order['net_amount'];
        foreach ($order_items as $order_item) {
            $package = Package::find($order_item->package_id);
            $professors  = $package['professors'];
            foreach ($professors as $professor) {
                if ($package['professor_revenue']) {
                    $professor_revenue = $package['professor_revenue'];
                } elseif ($professor['professor_revenue']) {
                    $professor_revenue = $professor['professor_revenue'];
                } else {
                    $global_settings = Setting::where('key', 'professor_revenue')->first();
                    $professor_revenue = $global_settings->value;
                }
                $professor_revenue_percentage = $professor_revenue / 100;
                $total_professors = count($professors);

                $professor_payout = ProfessorPayout::updateOrCreate([
                    'professor_id' => $professor['id'],
                    'order_id' => $order_id,
                    'package_id' => $package->id,
                    'amount' => ($professor_revenue_percentage / $total_professors) * $package_price,
                    'percentage' => $professor_revenue,
                ]);
                $professor_payout->save();
            }
        }


        $orderItems = OrderItem::where('order_id', $order_id)->get();
            foreach ($orderItems as $orderItem) {
                if ($orderItem->is_prebook && !$package->is_prebook_package_launched) {
                    $orderItem->payment_status = OrderItem::PAYMENT_STATUS_PARTIALLY_PAID;
                } else {
                    $orderItem->payment_status = OrderItem::PAYMENT_STATUS_FULLY_PAID;
                }

                if ($orderItem->item_type == OrderItem::ITEM_TYPE_STUDY_MATERIAL) {
                    $orderItem->order_status = OrderItem::STATUS_ORDER_PLACED;
                    $StudyMaterialOrderLogs=StudyMaterialOrderLog::where('order_item_id',$orderItem->id)->first();
                    if(empty($StudyMaterialOrderLogs)){

                    $studyMaterialOrderLog = new StudyMaterialOrderLog();
                    $studyMaterialOrderLog->order_item_id = $orderItem->id;
                    $studyMaterialOrderLog->status = StudyMaterialOrderLog::STATUS_ORDER_PLACED;
                    $studyMaterialOrderLog->save();
                    }

                    $package = Package::find($orderItem->package_id);
                    $user = User::with('address')->find($orderItem->user_id);

                    try {
                        Mail::send(new OrderPlaced([
                            'package_id' => $package->id,
                            'package_name' => $package->name,
                            'order_id' => $orderItem->order_id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'address' => optional($user->address)->address ?? '',
                            'area' => optional($user->address)->area ?? '',
                            'landmark' => optional($user->address)->landmark ?? '',
                            'city' => optional($user->address)->city ?? '',
                            'state' => optional($user->address)->state ?? '',
                            'pin' => optional($user->address)->pin ?? '',
                        ]));
                        $purchasemailnotification=new PurchaseMailNotification();
                        $purchasemailnotification->user_id=$order->user_id;
                        $purchasemailnotification->order_id=$order_id;
                        $purchasemailnotification->subject='Order placed';
                        $purchasemailnotification->status='Success';
                        $purchasemailnotification->save();
                    } catch (\Exception $exception) {
                       // info($exception->getMessage());
                    }
                }

                $orderItem->save();
            }

            $update_order->updated_method = Order::UPDATE_METHOD_MANUAL;
            $update_order->updated_ip_address = request()->ip();
            $transactionResponse=array('order_id'=>$order_id,'order_status'=>'Success','tracking_id' => null,'amount'=>'1000','created_at'=>$update_order->created_at);
            $update_order->transaction_response = $transactionResponse;
    
            $update_order->update();
    
            DB::commit();

            $payment =Payment::where('order_id',$order_id)->first();
            if(empty($payment)){
                $payment= $this->paymentService->createpayment($transactionResponse);
            }

            $orderItems = OrderItem::where('order_id', $order_id)->get();

            foreach ($orderItems as $orderItem) {
                $PaymentOrderItems=PaymentOrderItem::where('order_item_id', $orderItem->id)->first();
                if(empty($PaymentOrderItems)){ 
                $paymentOrderItem = new PaymentOrderItem;
                $paymentOrderItem->payment_id = $payment['id'];
                $paymentOrderItem->order_item_id = $orderItem->id;
                $paymentOrderItem->is_balance_payment = false;
                $paymentOrderItem->save();
                }
    
                if ($orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID || $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                    try {
                        $netAmount = null;
    
                        if (!$orderItem->is_prebook) {
                            $netAmount = $orderItem->price;
                        }
    
                        if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
                            $netAmount = $orderItem->booking_amount;
                        }
    
                        if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                            $netAmount = $orderItem->balance_amount;
                        }
                        $professorRevenue = ProfessorRevenue::where('package_id',$orderItem->package_id)->where('invoice_id',$payment->receipt_no)->first();
                        if(empty(  $professorRevenue)){ 
                        $this->professorRevenueService->storeprofessorrevenue([
                            'package_id' => $orderItem->package_id,
                            'net_amount' => $netAmount,
                            'invoice_id' => $payment->receipt_no,
                            'invoice_date' => $payment->created_at
                        ]);
                    }
                    } catch (Exception $exception) {
    //                    info('PROFESSOR REVENUE SERVICE EXCEPTION: ' . $exception->getMessage());
                    }
                }
            }
    


    // }
    $student_orders = Payment::with(['orderItems.package' => function($query) {
        $query->withTrashed();
    }])
         ->where('order_id', $order_id)
         ->first();
        

    $student_orders['net_amount_without_tax'] = $student_orders->net_amount - (( mb_strtoupper($student_orders->order->state) == 'MAHARASHTRA') ?  ($student_orders['cgst_amount'] +$student_orders['sgst_amount'] ) : $student_orders['igst_amount']);
    $gst = Setting::where('key','=','gstn')->first();
    $data['gstn'] = $gst->value;
    // $pendrive_price = Setting::where('key','=','pendrive_price')->first();
   
    // $order_detailss =  $student_orders;



    // $order_item=$order_detailss['orderItems'];
    // // $gstn =  $orders['data']['gstn'];
    // // $pendrive_price =  $orders['data']['pendrive_price'];
    // return view('pdf.invoice',compact('order_detailss','gstn','pendrive_price','order_item'));
    $data['pendrive_price'] = Setting::where('key','=','pendrive_price')->first();
       
    $data['order_details'] =  $student_orders;
    $data['holiday_offer_name']='';
    if($student_orders['order']['holiday_offer_id']){
        $offer_id=$student_orders['order']['holiday_offer_id'];
        $data['holiday_offer_name']=HolidayOffer::where('id',$offer_id)->first()->name;
    }else{
         $data['holiday_offer_name']='';
    }



    $data['order_item']= $student_orders['orderItems'];

$pdf = PDF::loadView('pdf.invoice_latest', $data);

try{ 
$order_details['pdf']=$pdf;
Mail::send(new PurchaseMailRegenerate($order_details));
$purchasemailnotification=new PurchaseMailNotification();
$purchasemailnotification->user_id=$order->user_id;
$purchasemailnotification->order_id=$order_id;
$purchasemailnotification->subject='Purchase user mail';
$purchasemailnotification->status='Success';
$purchasemailnotification->save();
} catch (\Exception $exception) {
    // $purchasemailnotification=new PurchaseMailNotification();
    // $purchasemailnotification->user_id=$order->user_id;
    // $purchasemailnotification->order_id=$order_id;
    // $purchasemailnotification->status='Failure';
    // $purchasemailnotification->save();
//                    info($exception->getMessage());
}
 //$attributes=$order_details;
        //     return view('emails.purchase_success_email_regenerate',compact('attributes'));
            
            

        }
        return response()->json( 'Invoice success fully generated', 200);
    }
        else{
            return response()->json( 'No record found', 200);
        }

        
    }
   
  
  
}
