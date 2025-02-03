<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\PurchaseMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PaymentOrderItem;
use App\Models\Student;
use App\Services\PaymentService;
use App\Services\ProfessorRevenueService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class OrderController extends Controller
{
    /** @var PaymentService $paymentService */
    var $paymentService;

    /** @var ProfessorRevenueService $professorRevenueService */
    var $professorRevenueService;

    /**
     * Create a new command instance.
     *
     * @param PaymentService $paymentService
     * @param ProfessorRevenueService $professorRevenueService
     * @return void
     */
    public function __construct(PaymentService $paymentService, ProfessorRevenueService $professorRevenueService)
    {
        $this->paymentService = $paymentService;
        $this->professorRevenueService = $professorRevenueService;
    }

    public function verify($orderID)
    {
        $orders = Order::query()
            ->whereIn('id', [$orderID])
            ->where('payment_status', '!=', Order::PAYMENT_STATUS_SUCCESS)
            ->get();

        if (count($orders) == 0) {
            return response()->json(['status' => false, 'message' => 'Order does not exist or already verified.']);
        }

        foreach ($orders as $order) {
            $paymentOrder = $this->getOrder($order->id);

            if ($paymentOrder && $paymentOrder['status'] == 0) {
                $payment = $this->paymentService->create($paymentOrder);

                if ($payment) {
                    if ($paymentOrder['order_status'] == 'Shipped') {
                        $order->transaction_response = $paymentOrder;
                        $order->transaction_response_status = $paymentOrder['order_status'];
                        $order->payment_status = Order::PAYMENT_STATUS_SUCCESS;
                        $order->updated_method = Order::UPDATE_METHOD_CRON;
                        $order->updated_ip_address = request()->ip();
                        $order->save();

                        foreach ($order->orderItems as $orderItem) {
                            if ($orderItem->is_prebook) {
                                $orderItem->payment_status = OrderItem::PAYMENT_STATUS_PARTIALLY_PAID;
                                $orderItem->save();
                            } else {
                                $orderItem->payment_status = OrderItem::PAYMENT_STATUS_FULLY_PAID;
                                $orderItem->save();
                            }

                            $paymentOrderItem = new PaymentOrderItem;
                            $paymentOrderItem->payment_id = $payment['id'];
                            $paymentOrderItem->order_item_id = $orderItem->id;
                            $paymentOrderItem->is_balance_payment = false;
                            $paymentOrderItem->save();

                            if ($orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID || $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                                try {
                                    $netAmount = null;

                                    if (! $orderItem->is_prebook) {
                                        $netAmount = $orderItem->price;
                                    }

                                    if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
                                        $netAmount = $orderItem->booking_amount;
                                    }

                                    if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                                        $netAmount = $orderItem->balance_amount;
                                    }

                                    $this->professorRevenueService->store([
                                        'package_id' => $orderItem->package_id,
                                        'net_amount' => $netAmount,
                                        'invoice_id' => $payment->receipt_no,
                                        'invoice_date' => $payment->created_at
                                    ]);
                                } catch (Exception $exception) {
                                    info ('PROFESSOR REVENUE SERVICE EXCEPTION: ' . $exception->getMessage());
                                }
                            }
                        }

                        try {
                            $order_items = OrderItem::where('order_id', $order->id)->pluck('package_id');
                            $packages = Package::with('subject')->whereIn('id',$order_items)->get();
                            $order_details = Student::where('user_id','=',$order->user_id)->first();
                            $order_details['order_id'] = $order->id;
                            $order_details['net_amount'] = $order->net_amount;
                            $order_details['packages'] = $packages;
                            if($order['cgst']){
                                $order_details['cgst'] = $order['cgst'];
                                $order_details['cgst_amount'] = $order['cgst_amount'];
                            }
                            if($order['igst']){
                                $order_details['igst'] = $order['igst'];
                                $order_details['igst_amount'] = $order['igst_amount'];
                            }
                            if($order['sgst']){
                                $order_details['sgst'] = $order['sgst'];
                                $order_details['sgst_amount'] = $order['sgst_amount'];
                            }

                            Mail::send(new PurchaseMail($order_details));
                        } catch(\Exception $exception) {
                            info($exception->getMessage());
                        }
                    }
                }
            }

            $paymentOrder = $this->getOrder($order->id . '-F');

            if ($paymentOrder && $paymentOrder['status'] == 0) {
                $payment = $this->paymentService->create($paymentOrder);

                if ($payment) {
                    if ($paymentOrder['order_status'] == 'Shipped') {
                        $order->transaction_response = $paymentOrder;
                        $order->transaction_response_status = $paymentOrder['order_status'];
                        $order->payment_status = Order::PAYMENT_STATUS_SUCCESS;
                        $order->updated_method = Order::UPDATE_METHOD_CRON;
                        $order->updated_ip_address = request()->ip();
                        $order->save();

                        foreach ($order->orderItems as $orderItem) {
                            $orderItem->payment_status = OrderItem::PAYMENT_STATUS_FULLY_PAID;
                            $orderItem->save();

                            $paymentOrderItem = new PaymentOrderItem;
                            $paymentOrderItem->payment_id = $payment['id'];
                            $paymentOrderItem->order_item_id = $orderItem->id;
                            $paymentOrderItem->is_balance_payment = true;
                            $paymentOrderItem->save();

                            if ($orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID || $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                                try {
                                    $netAmount = null;

                                    if (! $orderItem->is_prebook) {
                                        $netAmount = $orderItem->price;
                                    }

                                    if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_PARTIALLY_PAID) {
                                        $netAmount = $orderItem->booking_amount;
                                    }

                                    if ($orderItem->is_prebook && $orderItem->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                                        $netAmount = $orderItem->balance_amount;
                                    }

                                    $this->professorRevenueService->store([
                                        'package_id' => $orderItem->package_id,
                                        'net_amount' => $netAmount,
                                        'invoice_id' => $payment->receipt_no,
                                        'invoice_date' => $payment->created_at
                                    ]);
                                } catch (Exception $exception) {
                                    info ('PROFESSOR REVENUE SERVICE EXCEPTION: ' . $exception->getMessage());
                                }
                            }
                        }

                        try {
                            $order_items = OrderItem::where('order_id', $order->id)->pluck('package_id');
                            $packages = Package::with('subject')->whereIn('id',$order_items)->get();
                            $order_details = Student::where('user_id','=',$order->user_id)->first();
                            $order_details['order_id'] = $order->id;
                            $order_details['net_amount'] = $order->net_amount;
                            $order_details['packages'] = $packages;
                            if($order['cgst']){
                                $order_details['cgst'] = $order['cgst'];
                                $order_details['cgst_amount'] = $order['cgst_amount'];
                            }
                            if($order['igst']){
                                $order_details['igst'] = $order['igst'];
                                $order_details['igst_amount'] = $order['igst_amount'];
                            }
                            if($order['sgst']){
                                $order_details['sgst'] = $order['sgst'];
                                $order_details['sgst_amount'] = $order['sgst_amount'];
                            }

                            Mail::send(new PurchaseMail($order_details));
                        } catch(\Exception $exception) {
                            info($exception->getMessage());
                        }
                    }
                }
            }
        }

        return response()->json(['status' => true, 'message' => 'Order successfully verified.']);
    }

    public function getOrder($orderId)
    {
        $working_key = env('CCAVENUE_WORKING_KEY'); //'F8677030079394D8D35511AC548D85E3'; //Shared by CCAVENUES
        $access_code = env('CCAVENUE_ACCESS_CODE'); //'AVLT90HA04BY39TLYB';


        $merchant_json_data =
            array(
                'reference_no' => '',
                'order_no' => $orderId
            );

        $merchant_data = json_encode($merchant_json_data);
        $encrypted_data = $this->encrypt($merchant_data, $working_key);


        $final_data = "request_type=JSON&access_code=$access_code&command=orderStatusTracker&response_type=JSON&enc_request=".$encrypted_data;


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://login.ccavenue.com/apis/servlet/DoWebTrans");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$final_data);
        // Get server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec ($ch);

        curl_close ($ch);


        $status = '';
        $information=explode('&',$result);
        $dataSize=sizeof($information);

        for($i = 0; $i < $dataSize; $i++)
        {
            $info_value=explode('=',$information[$i]);
            if($info_value[0] == 'enc_response'){
                $status = $this->decrypt(trim($info_value[1]), $working_key);
            }
        }

        $status_result = json_decode($status, true);

        return $status_result['Order_Status_Result'] ?? null;
    }

    /**
     * CCAvenue encryption
     * @param $plainText string
     * @param $key string
     * @return string
     */
    public function encrypt($plainText, $key)
    {
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    /**
     * CCAvenue decryption
     * @param $encryptedText string
     * @param $key
     * @return string
     */
    public function decrypt($encryptedText, $key)
    {
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    /**
     * @param $plainText
     * @param $blockSize
     * @return string
     */
    protected function pkcs5_pad($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }

    /**
     * @param $hexString
     * @return string
     */
    protected function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString="";
        $count=0;
        while($count<$length)
        {
            $subString =substr($hexString,$count,2);

            $packedString = pack("H*",$subString);
            if ($count==0)
            {
                $binString=$packedString;
            }

            else
            {
                $binString.=$packedString;
            }

            $count+=2;
        }
        return $binString;
    }

    public function getStudentPackages()
    {
        $userID = request()->input('user_id');

        $packages = [];

        $orderItems = OrderItem::with('package')->where('user_id', $userID)->get();

        foreach ($orderItems as $orderItem) {
//            if ($orderItem->expire_at) {
//                $packages['expire_at'] = Carbon::parse($orderItem->expire_at)->toFormattedDateString();
//            } else {
//                $packages['expire_at'] = null;
//            }
//
//            if ($orderItem->package) {
//                $packages['name'] = $orderItem->package->name;
//            } else {
//                $packages['name'] = null;
//            }

            $packages[] = ['expire_at' => Carbon::parse($orderItem->expire_at)->toFormattedDateString(), 'name' => $orderItem->package->name];
        }

        info ($packages);

        return response()->json($packages);
    }
}
