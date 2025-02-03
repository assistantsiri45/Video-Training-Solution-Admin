<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageVideo;
use App\Models\Professor;
use App\Models\SubjectPackage;
use App\Models\CustomizedPackage;
use App\Models\Video;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesReportExport implements FromQuery, WithMapping, WithHeadings , WithEvents
{
    private $search;
    private $fromDate;
    private $toDate;
    private $status;

    public function __construct($search = null, $fromDate = null, $toDate = null, $status = null)
    {
        $this->search = $search;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function query()
    {
//        $orderItems = OrderItem::query()->with('package.course', 'package.level')->whereHas('package', function ($query){
//            $query->where('course_id', 7);
//        });

    /*    $orderItems = OrderItem::query();

        $orderItems->with('order.student');

        if ($this->search) {
            $search = '%' . $this->search . '%';

            $orderItems->where(function ($query) use ($search) {
                $query->whereHas('order', function ($query) use ($search) {
                    $query->where('id', $search)
                        ->orWhere('transaction_id', $search)
                        ->orWhere('net_amount', $search);
                })->orWhere(function ($query) use ($search) {
                    $query->whereHas('order.student', function ($query) use ($search) {
                        $query->where('name', 'like', $search)
                            ->orWhere('email', 'like', $search)
                            ->orWhere('phone', 'like', $search);
                    });
                });
            });
        }

        if ($this->fromDate && $this->toDate) {
            $orderItems->whereBetween('created_at', [$this->fromDate, $this->toDate]);
        }

        if ($this->status) {
            $orderItems->whereHas('order', function ($query) {
                $query->where('payment_status', $this->status);
            });
        }

        return $orderItems; */

        $query = Order::select('orders.*')->with('student', 'associate.user','third_party.user','orderItems','payment');
        if(empty($this->search) && empty($this->fromDate) && empty($this->toDate) && empty($this->status)){
          
            $year = date('Y');
            $month = date('m');
            $startOfMonth = Carbon::create($year, $month)->startOfMonth();
            $query->whereDate('created_at', '>=', $startOfMonth);
        }

        if(empty($this->fromDate) && empty($this->toDate) && !empty($this->status)){
          
            $year = date('Y');
            $month = date('m');
            $startOfMonth = Carbon::create($year, $month)->startOfMonth();
            $query->whereDate('created_at', '>=', $startOfMonth);
        }

        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->whereHas('student', function($query) use ($search) {
                $query->where('name', 'like', $search)
                ->orWhere('email','like', $search)
                ->orWhere('phone','like', $search);
            });
        }
        if ($this->search) {
            $query->Orwhere('transaction_id',  '=', $this->search);
        }
        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->Orwhere('net_amount','LIKE', $search);
        }
        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->Orwhere('payment_status','LIKE', $search);
        }
        if ($this->search) {
            $query->Orwhere('orders.id','=', $this->search);
        }

        if($this->fromDate && $this->toDate){
            $from= date("Y-m-d",strtotime($this->fromDate)).''.' 00:00:00';
            $to= date("Y-m-d",strtotime($this->toDate)).''.' 23:59:59';
            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($this->status) {
                $query->where('payment_status', $this->status);
        }
        
        return $query;
    }

    /**
     * @param OrderItem $orderItem
     * @return array|void
     */
    public function map($order): array
    {
        return [
            [
                $order->id ?? null,
             //   $order->student->id ?? null,
                $order->student->name ?? null,
                $order->student->email ?? null,
                $order->student->phone ?? null,
                $this->getPackage($order) ?? null,
                $this->getCourse($order),
                $this->getLevel($order),
                $this->getPackageType($order),
                $this->getSubject($order),
                $this->getChapter($order),
                $this->getLanguages($order),
                $this->getProfessor($order),
                $this->getModeOfLecture($order),
                $this->getDuration($order),
                $this->getPackageValidity($order),
                $this->getExpiry($order),
                $this->getStudyMaterial($order),
                $this->getStudyMaterialPrice($order),
                $this->getPendrive($order),
                $this->getPendrivePrice($order),
                $this->getGrossAmount($order),
                $this->getDiscount($order),
                $this->getJkoin($order),
                $this->getCoupon($order),
                $order->net_amount,
                $this->getAddress($order),
                $order->transaction_id,
                $this->getInvoiceNumber($order),
                $this->getPaymentStatus($order),
                $this->getPaymentMode($order),
                $this->getPaymentMethod($order),
                $this->getResponse($order),
                $this->getIsRefund($order),
                $this->getCreatedAt($order),
                // $this->getPaymentStatus($order),
                // $order->net_amount ?? null,
//                $orderItem->price ?? null,
//                $orderItem->package->price ?? null,
     //           $order->created_at->format('d/m/Y H:i') ?? null,
             //   $this->getPackage($order) ?? null,
//                $orderItem->package->course->name ?? null,
//                $orderItem->package->level->name ?? null,
              //  $this->getProfessors($order) ?? null,
              //  $this->getProfessors($order, $count = true) ?? null,
         //       $this->getPrice($order) ?? null,
          //      $this->getIsRefund($order)
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'ORDER ID',
         //   'STUDENT ID',
            'NAME',
            'EMAIL ADDRESS',
            'PHONE NUMBER',
            'PACKAGE',
            'COURSE',
            'LEVEL',
            'TYPE',
            'SUBJECT',
            'CHAPTER',
            'LANGUAGE',
            'PROFESSORS',
            'MODE OF LECTURE',
            'PACKAGE DURATION',
            'PACKAGE VALIDITY',
            'EXPIRE AT',
            'STUDY MATERIAL',
            'STUDY MATERIAL FEES',
            'PENDRIVE',
            'PENDRIVE FEES',
            'GROSS AMOUNT',
            'DISCOUNT',
            'JKOINS',
            'COUPONS',
            'NET AMOUNT',
            'ADDRESS',
            'TRANSACTION ID',
            'INVOICE NUMBER',
            'PAYMENT STATUS',
            'PAYMENT TYPE',
            'PAYMENT MODE',
            'RESPONSE',
            'REFUND?',
            'CREATED AT',
//             'STATUS',
//             'TOTAL AMOUNT',
// //            'AMOUNT PAID',
// //            'COST',
//             'DATE',
//            // 'PACKAGE',
// //            'COURSE',
// //            'LEVEL',
//            // 'PROFESSORS',
//            // 'SINGLE/MULTIPLE PROFESSORS',
//             'PRICE',
//             'REFUNDED?'
        ];
    }
    public function getIsRefund($order)
    {
        $is_refund = '';

       
        if ($order->is_refunded==1)
        {
            $is_refund='Yes';
        }
        else
        {
            $is_refund= 'No';
        }

        return $is_refund;
    }
    public function getProfessors($orderItem, $count = false)
    {
        $package = Package::find($orderItem->package_id);

        if (!$package) {
            return null;
        }

        $packageIDs = [];

        if ($package->type == 2) {
            $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->pluck('chapter_package_id');

            foreach ($chapterPackageIDs as $chapterPackageID) {
                $packageIDs[] = $chapterPackageID;
            }
        } else {
            $packageIDs[] = $package->id;
        }

        $videoIDs = PackageVideo::whereIn('package_id', $packageIDs)->pluck('video_id');
        $professorIDs = Video::whereIn('id', $videoIDs)->pluck('professor_id');
        $professors = Professor::whereIn('id', $professorIDs)->pluck('name');

        if ($count) {
            return count($professors) > 1 ? 'Multiple' : 'Single';
        }

        return implode("\n", $professors->toArray());
    }

    public function getPrice($orderItem)
    {
        if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
            return $orderItem->booking_amount;
        }

        return $orderItem->price;
    }

    public function getPackage($order)
    {
        $package = '';
        $i = 1;
        foreach($order->orderItems as $val2){
                           
            if(!empty($val2->package)){
                $package.=$i.') ';
                $package.=$val2->package->name .PHP_EOL;
            }else{
                $package.='-'.PHP_EOL;
            }
                ++$i;
            }

        return $package;
    }

    public function getCourse($order){
        $course = '';
        
        $i=1;
        foreach($order->orderItems as $val2){
        if(!empty($val2->package->course->name)){
            $course.=$i.') ';
            $course.=$val2->package->course->name .PHP_EOL;  
                                       
        }else{
            $course.='-'.PHP_EOL;
        }
        $i++;
        }

        return $course;
    }

    public function getLevel($order){
        $level = '';
        
        $i=1;
        foreach($order->orderItems as $val2){
        if(!empty($val2->package->level->name)){
            $level.=$i.') ';
            $level.=$val2->package->level->name .PHP_EOL;  
                                       
        }else{
            $level.='-'.PHP_EOL;
        }
        $i++;
        }

        return $level;
    }
    public function getPackageType($order){
        $packagetype = '';
        
        $i=1;
        foreach($order->orderItems as $val2){
        if(!empty($val2->package->packagetype->name)){
            $packagetype.=$i.') ';
            $packagetype.=$val2->package->packagetype->name .PHP_EOL;  
                                       
        }else{
            $packagetype.='-'.PHP_EOL;
        }
        $i++;
        }

        return $packagetype;
    }

    public function getSubject($order){
        $subject = '';
        
        $i=1;
        foreach($order->orderItems as $val2){
        if(!empty($val2->package->subject->name)){
            $subject.=$i.') ';
            $subject.=$val2->package->subject->name .PHP_EOL;  
                                       
        }else{
            $subject.='-'.PHP_EOL;
        }
        $i++;
        }

        return $subject;
    }

    public function getChapter($order){
        $chapter = '';
        
        $i=1;
        foreach($order->orderItems as $val2){
        if(!empty($val2->package->chapter->name)){
            $chapter.=$i.') ';
            $chapter.=$val2->package->chapter->name .PHP_EOL;  
                                       
        }else{
            $chapter.='-'.PHP_EOL;
        }
        $i++;
        }

        return $chapter;
    }

    public function getLanguages($order){
        $lan = '';
        
        $i=1;
        foreach($order->orderItems as $val2){
        if(!empty($val2->package->language->name)){
            $lan.=$i.') ';
            $lan.=$val2->package->language->name .PHP_EOL;  
                                     
        }else{
            $lan.= '-';
        }
        $i++;
        }

        return $lan;
    }

    public function getProfessor($order){
        $prof = '';
        $i = 1;
        $packageIDs =[];
        foreach($order->orderItems as $val2){
           // $prof.=$i.')';
            if(!empty($val2->package->type)){
                if($val2->package->type == 1){
                    $packageIDs[] = $val2->package->id;
                }
                
                if($val2->package->type == 2){
                    $packageIDs = SubjectPackage::where('package_id', $val2->package->id)->get()->pluck('chapter_package_id');
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
                                $packageIDs[] = $chapterPackageID;
                            }
                        }
                    }
                }

               
           //$i++;
        }
        
    }
    $professorIDs = PackageVideo::whereIn('package_id', $packageIDs)->with('video')->get()->pluck('video.professor_id')->unique();

        $professors = Professor::whereIn('id', $professorIDs)->get();

        foreach($professors as $professor){
            if($professors->last()==$professor){
                $prof.= $professor->name;
            }else{ 
            $prof.= $professor->name.',';
            }
        }
        return $prof;
    }
    public function getPaymentStatus($order)
    {
        $status = null;

        
            if ($order->payment_status==1)
            {
                $status=Order::PAYMENT_STATUS_SUCCESS_TEXT;
            }
            elseif ($order->payment_status==2)
            {
                $status=Order::PAYMENT_STATUS_FAILED_TEXT;
            }
            elseif ($order->payment_status==3)
            {
                $status=Order::PAYMENT_STATUS_RETURN_TEXT;
            }
            elseif ($order->payment_status==4)
            {
                $status=Order::PAYMENT_STATUS_FAILED_TEXT;
            }
            elseif ($order->payment_status==5)
            {
                $status=Order::PAYMENT_STATUS_INITIATED_TEXT;
            }
       

        return $status;

    }

    // public function getPaymentMode($order){
    //     $mode = '';
    //     if ($order->updated_method==1)
    //     {
    //         $mode= "CCAVENUE";
    //     }
    //     elseif ($order->updated_method==2)
    //     {
    //         $mode="MANUAL";
    //     }
    //     elseif ($order->updated_method==3)
    //     {
    //         $mode="CRON";
    //     }
    //     elseif ($order->updated_method==4)
    //     {
    //         $mode="EASEBUZZ";
    //     }
       
    //     return $mode;
    // }

    public function getPaymentMode($order){
        $mode = '';
        if($order->payment_mode == 1){
            return 'ONLINE';
        }
        if($order->payment_mode == 2){
            return 'CASH ON DELIVERY';
        }
        if($order->payment_mode == 3){
            return 'PREPAID';
        }
       
        return $mode;
    }

    public function getPaymentMethod($order){
        $mode = '';
        if ($order->updated_method==1)
        {
            $mode= "CCAVENUE";
        }
        elseif ($order->updated_method==2)
        {
            $mode="MANUAL";
        }
        elseif ($order->updated_method==3)
        {
            $mode="CRON";
        }
        elseif ($order->updated_method==4)
        {
            $mode="EASEBUZZ";
        }
       
        return $mode;
    }

    public function getPackageValidity($order){
        $validity= '';
        
        $i=1;
        foreach($order->orderItems as $val2){
            if(!empty($val2->package->expiry_type)){
                $validity.=$i.') ';
                if($val2->package->expiry_type == 1){
                    $validity.=$val2->package->expiry_month .' '.PHP_EOL;
                }elseif($val2->package->expiry_type == 2){
                    $validity.=$val2->package->expire_at .PHP_EOL;
                }
            }else{
                $validity.=$i.') ';
                if(!empty($val2->package->expire_at)){
                    $validity.=$val2->package->expire_at .PHP_EOL;                               
                }else{
                    $validity.='9 Months'.PHP_EOL;
                }
            }
        $i++;
        }

        return $validity;
    }

    public function getExpiry($order){
        $expiry = '';
        $i=1;
        foreach($order->orderItems as $val2){
            if(!empty($val2->expire_at)){
                $expiry.=$i.')'.$val2->expire_at.PHP_EOL;
            }else{
                $expiry.=PHP_EOL;
            }
            $i++;
        }
        return $expiry;
    }

    public function getDuration($order){
        $duration = '';
        $i=1;
        foreach($order->orderItems as $val2){
            if(!empty($val2->package->duration)){
                $duration.=$i.')'.$val2->package->duration.PHP_EOL;
            }else{
                $duration.=PHP_EOL;
            }
            $i++;
        }
        return $duration;
    }

    public function getResponse($order){
        $response='';
        $i=1;

        if(!empty($order->transaction_response)){
            $response = $order->transaction_response;
        }else{
            $response = '-';
        }

        return $response;

    }

    public function getModeOfLecture($order){
        $mod = '';
        $i=1;
        foreach($order->orderItems as $val2){
            if(!empty($val2->package)){
                $mod.=$i.') ';
                if($val2->package->pendrive==true){
                    $mod.=OrderItem::PENDRIVE_TEXT.PHP_EOL;
                }
                elseif($val2->package->g_drive == true){
                    $mod.=OrderItem::G_DRIVE_TEXT.PHP_EOL;
                }
                else{
                    $mod.=OrderItem::ONLINE_TEXT.PHP_EOL;
                }
               
            }else{
                $mod.='-'.PHP_EOL;
            }
            $i++;
        }
        return $mod;
    }

    public function getStudyMaterial($order){
        $mat = '';
        $i=1;
        foreach($order->orderItems as $val2){
            if ($val2->item_type==2) {
                $mat.=$i.') '.'Yes'.PHP_EOL;
               
            }
            else{
                $mat.=$i.') '.'No'.PHP_EOL;
            }
            $i++;
        }
        return $mat;
    }

    public function getStudyMaterialPrice($order){
        $price = '';
        $i=1;
        foreach($order->orderItems as $val2){
            if ($val2->item_type==2) {
                $price.=$i.') '.$val2->price.PHP_EOL;               
            }
            else{
                $price.=$i.') '.'-'.PHP_EOL;
            }
            $i++;
        }
        return $price;
    }

    public function getPendrive($order){
        $pendrive = '';
        if(!empty($order->pendrive_price)){
            $pendrive .= 'Yes';
        }else{
            $pendrive .= 'No';
        }
        return $pendrive;
    }

    public function getPendrivePrice($order){
        $pendriveprice = '';
        if(!empty($order->pendrive_price)){
            $pendriveprice = $order->pendrive_price;
        }else{
            $pendriveprice .= '-';
        }
        return $pendriveprice;
    }

    public function getGrossAmount($order){
        $gross = 0;
        foreach($order->orderItems as $val2){
            $gross = $gross + $val2->price;
        }
        return $gross;
    }

    public function getDiscount($order){
        $discount = '';
        if($order->holiday_offer_amount){
            $discount=$order->holiday_offer_amount;
        }else{
            $discount= '-';
        }
        return $discount;
    }

    public function getJkoin($order){
        $jkoin = '';
        if($order->reward_amount){
            $jkoin=$order->reward_amount;
        }else{
            $jkoin= '-';
        }
        return $jkoin;
    }

    public function getCoupon($order){
        $coupon = '';
        if($order->coupon_amount){
            $coupon=$order->coupon_amount;
        }else{
            $coupon= '-';
        }
        return $coupon;
    }

    public function getInvoiceNumber($order){
        $invoice = '';
        if(!empty($order->payment->receipt_no)){
            $invoice=$order->payment->receipt_no;
        }else{
            $invoice='-';
        }
        return $invoice;
    }

    public function getAddress($order){
        $addr = '';
        if(!empty($order->address)){
            $addr=$order->address;
        }else{
            $addr='-';
        }
        return $addr;
    }

    public function getCreatedAt($order){
        $created_at = '';
        if(!empty($order->created_at)){
            $created_at = date("d-m-Y H:i:s",strtotime($order->created_at));
        }else{
            $created_at = '-';
        }

        return $created_at;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
               
                $event->sheet->getStyle('A1:AH1')->applyFromArray([
                    'font'=> [
                        'bold' =>true
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('A:AA')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(40);
                $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(50);
                $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('V')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Y')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('Z')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('AA')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('AB')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('AC')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('AD')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('AE')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('AG')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('AH')->setWidth(30);
            },
        ];
    }
}
