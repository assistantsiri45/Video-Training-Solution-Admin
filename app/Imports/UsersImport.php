<?php

namespace App\Imports;

use App\Mail\SendOrderMail;
use App\Mail\SendRegistrationMail;
use App\Models\ImportLog;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\Student;
use App\Notifications\OrderCreated;
use App\Notifications\UserCreated;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $importLog = ImportLog::latest()->first();
        $status = false;
        $message = null;
        $successCount = 0;
        $failedCount = 0;

        try {
            foreach ($rows as $i => $row) {
                if ($i != 0) {
                    $user = User::where('phone', $row[1])->orWhere('email', $row[2])->first();

                    if ($user) {
                        $this->createOrder($user->id, $row[4], $row[5], $user->name, $user->email);
                        $failedCount++;
                    } else {
                        $user = new User();
                        $user->name = $row[0];
                        $user->country_code = '+91';
                        $user->phone = $row[1];
                        $user->email = $row[2];
                        $user->password = Hash::make($row[3]);
                        $user->role = 5;
                        $user->is_imported = true;
                        $user->save();

                        $attributes['name'] = $user->name;
                        $attributes['email'] = $user->email;
                        $attributes['password'] = $row[3];
                        try{
                            Mail::send(new SendRegistrationMail($attributes));
                        }
                        catch (\Exception $exception) {
                            info($exception->getMessage(), ['exception' => $exception]);
                        }
                        $notification = new UserCreated($attributes);
                        Notification::route('sms', $user->phone)->notify($notification);

                        $student = new Student();
                        $student->user_id = $user->id;
                        $student->name = $user->name;
                        $student->email = $user->email;
                        $student->country_code = $user->country_code;
                        $student->phone = $user->phone;
                        $student->save();

                        $this->createOrder($user->id, $row[4], $row[5], $user->name, $user->email, $user->phone);
                        $successCount++;
                    }
                }
            }

            $status = true;
        } catch (\Exception $exception) {
            $status = false;
            $message = $exception->getMessage();
        }

        $importLog->status = $status;
        $importLog->message = $message;
        $importLog->success_count = $successCount;
        $importLog->failed_count = $failedCount;
        $importLog->save();
    }

    public function createOrder($userID = null, $packageID = null, $expireAt = null, $userName = null, $userEmail = null, $userPhone = null)
    {
        $package = Package::where('id', $packageID)
            ->orWhere('slug', $packageID)
            ->first();

        if ($package) {
            $isOrderItemExist = OrderItem::query()
                ->where('user_id', $userID)
                ->where('package_id', $package->id)
                ->where('payment_status', OrderItem::PAYMENT_STATUS_FULLY_PAID)
                ->exists();

            if (! $isOrderItemExist) {
                $order = new Order();
                $order->user_id = $userID;
                $order->cgst = 0;
                $order->cgst_amount = 0;
                $order->sgst = 0;
                $order->sgst_amount = 0;
                $order->igst = 0;
                $order->igst_amount = 0;
                $order->transaction_id = rand(0, 1000000000000);
                $order->transaction_response_status = 'Success';
                $order->unique_key = rand(0, 100000000000);
                $order->payment_status = 1;
                $order->payment_mode = 1;
                $order->net_amount =  0;
                $order->address_id = null;
                $address = null;
                $order->name = null;
                $order->country_code = null;
                $order->phone = null;
                $order->alternate_phone = null;
                $order->city = null;
                $order->state = null;
                $order->pin = null;
                $order->address = null;
                $order->payment_initiated_at = null;
                $order->status = 1;
                $order->save();

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->package_id = $package->id;
                $orderItem->user_id = $order->user_id;
                $orderItem->price = 0;
                $orderItem->price_type = 1;
                $orderItem->is_prebook = false;
                $orderItem->delivery_mode = 1;
                $orderItem->payment_status = 2;
                $orderItem->is_completed = 0;
                $orderItem->item_type = 1;
                $orderItem->item_id = $package->id;
                $orderItem->expire_at = Carbon::parse($expireAt);
                $orderItem->save();

                $attributes['name'] = $userName;
                $attributes['email'] = $userEmail;
                $attributes['package_name'] = $package->name;

                try{
                    Mail::send(new SendOrderMail($attributes));
                }
                catch (\Exception $exception) {
                    info($exception->getMessage(), ['exception' => $exception]);
                }

                $notification = new OrderCreated($attributes);
                Notification::route('sms', $userPhone)->notify($notification);
            }
        }
    }
}
