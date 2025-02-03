<!DOCTYPE html>
<html lang="en">
<title>INVOICE</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<head>
    {{--<link rel="stylesheet" href="{{ url('assets/css/bootstrap.css') }}"/>--}}
    {{--<link rel="stylesheet" href="{{ url('vendor/@fortawesome/fontawesome-free/css/all.css') }}" />--}}

    <style>

        .items th, .items td {
            /* border: 2px solid #000; */
            /* border-bottom: 1px solid #f58f3d;
            border-right: 1px solid #f58f3d; */
            padding: 0.5em 1em;
        }
        table td {
            padding: 0;
        }

        .description tr:last-child td:first-child { border-bottom-left-radius: 10px; }
        .description tr:last-child td:last-child { border-bottom-right-radius: 10px; }
        .order tr:first-child td:first-child { border-top-left-radius: 10px; }
        .order tr:first-child td:last-child { border-top-right-radius: 10px; }
        .order tr:last-child td:first-child { border-bottom-left-radius: 10px; }
        .order tr:last-child td:last-child { border-bottom-right-radius: 10px; }
       
        body {
            background-color: #fff;
            overflow: hidden;
            overflow-y: auto;
            margin: 0 auto;
            font-family: Arial, Helvetica, sans-serif;
            width: 100%;
            height: 100%;
        }

        .container {
            padding: 15px;
            /* border-right: 15px solid #dd5f2b; */
        }
    </style>
</head>


<table width="100%">
    <tbody>
    <tr>
        <td colspan="2" style="text-align: right;">
            <img src="{{public_path('images/logo-invoice.png')}}" alt="JK Shah Classes Online" title="JK Shah Classes Online" style="width: 15%;">
        </td>
        
    </tr>
    <tr>
        <td colspan="2">
        <h1 class="heading" style="  margin: 0; text-align: center;  text-decoration: underline;  font-family: serif;"><b>INVOICE</b></h1>
        </td>
        
    </tr>
    <tr>
        <td width="50%">
            <h5 style="font-size: 16px;margin: 10px 0;text-transform: uppercase;"><b>RECEIPT No.:</b> <span style="padding-left: 10px;font-size: 18px;margin: 0 0 10px 0;font-weight: 600;">{{str_pad($order_details['receipt_no'], 6, "0", STR_PAD_LEFT)}}</span></h5>
        </td>
        <td width="50%"  style="margin-left:auto;text-align:right;">
            <h5 style="margin-top: 10px;font-size: 16px;text-transform: uppercase;">Date:<span style=" padding-left: 10px;   font-size: 18px;margin: 0 0 10px 0;font-weight: 600;">{{ date("d-m-Y", strtotime($order_details['created_at']))}}</span></h5>
        </td>
    </tr>
    </tbody>
</table>

<table width="100%;" style="border-radius: 10px 0 0 0; border-spacing: 0;" >
    <tbody>
    <tr>
        <td style="width: 50%;border-top: 2px solid #000;border-right: 1px solid #000;border-bottom: 2px solid #000;border-left: 2px solid #000;border-radius: 10px 0 0 0 ">
            <h3 style="padding: 10px;color: #000;text-align: center;font-size: large;font-size: 18px;font-weight: bold; margin: 0px;">FROM</h3>
        
        </td>

        <td style="width: 50%;border-top: 2px solid #000;border-right: 2px solid #000;border-bottom: 2px solid #000;border-left: 1px solid #000;border-radius:  0 10px 0 0">
            <h3 style="padding: 10px;color: #000;text-align: center;font-size: large;font-size: 18px;font-weight: bold;margin: 0px;">Customer Name & Address</h3>
        </td>

    </tr>
    <tr>
        <td style="border-right: 1px solid #000;border-bottom: 2px solid #000;border-left: 2px solid #000;border-radius: 0 0 0 10px;vertical-align: top;">
            <h3 style="margin: 10px auto;width: 95%;">J K Shah Classes (Unit of Veranda XL Learning Solutions Private Limited)</h3>
            <p style="margin: 10px auto;width: 95%;font-size: 17px;">3rd Floor, Shraddha,
                                Old Nagardas Road,
                                Andheri East,
                                Mumbai-400069.</p>
            <p style="margin: 10px auto;width: 95%;font-size: 17px;">GSTN : <b>{{$gstn}}</b></p>
            <p style="margin: 10px auto;width: 95%;font-size: 17px;"><img src="{{public_path('images/whatsapp.png')}}" style="width: 15px;height: auto;margin-right: 5px;" class="whatsapp" alt="">9757001272</p>
        </td>
        <td style="border-right: 2px solid #000;border-bottom: 2px solid #000;border-left: 1px solid #000;border-radius: 0 0 10px 0;vertical-align: top;padding: 10px;">
            <h3 style="margin: 10px auto;width: 95%;">{{ $order_details['order']['name'] }}</h3>
            <p style="margin: 10px auto;width: 95%;font-size: 17px;">{{ $order_details['order']['address'] }},
                {{ $order_details['order']['city'] }},
                {{ $order_details['order']['state'] }},
                {{ $order_details['order']['pin'] }}</p>
        </td>
    </tr>
    </tbody>
</table>

<table class="description"  style="margin: 20px 0; border-radius: 10px 0 0 0; border-spacing: 0;" width="100%;">
    <tbody>
                <tr>
                    <th class="zero" style="width: 8%;border-radius: 10px 0 0 0;padding: 10px;font-size: 18px;border-top: 2px solid #000;border-bottom: 2px solid #000;border-left: 2px solid #000;white-space:nowrap;">SR. NO.</th>
                    <th class="one" style="width: 30%;border: 2px solid #000;padding: 10px;">DESCRIPTION</th>
                    <th class="two" style="width: 10%;border-top: 2px solid #000;border-right: 2px solid #000;border-bottom: 2px solid #000;padding: 10px">VIEWS</th>
                    <th class="three" style="width: 15%;border-top: 2px solid #000;border-right: 2px solid #000;border-bottom: 2px solid #000;padding: 10px;">VALID UPTO</th>
                    <th class="four" style="width: 15%;border-top: 2px solid #000;border-right: 2px solid #000;border-bottom: 2px solid #000;border-radius: 0 10px 0 0;padding: 10px;">AMOUNT</th>
                </tr>
           
            @php $i=1; @endphp
            @foreach($order_item as $item)
                <tr>
                    <td style="border-left: 2px solid #000;border-bottom: 2px solid #000;padding: 0 10px;font-size: 14px !important;margin: 0 auto !important;text-align: center;"><h3>{{$i}}<h3></td>
                    <td style="padding: 0 10px;border-right: 2px solid #000;border-bottom: 2px solid #000;border-left: 2px solid #000;font-size: 14px !important;margin: 0 auto !important;">
                        <h3>
                            @if ($order_details['order']['is_refunded']) [REFUND] - @endif {{ $item['package']['name'] }} @if ($item['item_type'] == 2) (Study Material) @endif
                        </h3>
                    </td>

                    <td style="padding: 0 10px;border-right: 2px solid #000;border-bottom: 2px solid #000;text-align: center;font-size: 12px;margin: 0 auto !important;">
                        <h3>@if($item['package_duration']) {{$item['package_duration']}} @endif</h3>
                    </td>

                    <td style="padding: 0 10px;border-right: 2px solid #000;border-bottom: 2px solid #000;text-align: center;font-size: 12px;margin: 0 auto !important;">
                        <h3>{{ date("d-m-Y", strtotime($item['expire_at']))}}</h3>
                    </td>

                    <td style="padding: 10px;border-right: 2px solid #000;border-bottom: 2px solid #000;text-align: center;font-size: 12px;margin: 0 auto !important;">
                       
                        <h3>@if ($item['pivot']['is_balance_payment'])
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{ number_format($item['balance_amount'], 2) }}
                            @else
                                @if ($item['is_prebook'])
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>@if($item['booking_amount']){{ number_format($item['booking_amount'], 2) }}
                                @else
                               {{ $item['price']}}
                                @endif
                                @else
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ number_format($item['price'], 2) }}
                                @endif
                            @endif</h3>
                        
                    </td>
                </tr>
                @php $i++; @endphp
            @endforeach    
    </tbody>
</table>

<table class="order" width="100%;" style="border-radius: 10px 0 0 0;margin-top: 20px;border-spacing: 0;" border="0">
    <tbody>

        @if($order_details['order']['pendrive_price'])
         <tr>
            <td style="padding:5px;width: 48%;border-top: 2px solid #000; border-left: 2px solid #000;"><h3 style="font-size: 14px;text-align: left;">PENDRIVE</h3></td>
            <td style="padding:5px;width: 15%;border-top: 2px solid #000;"></td>
            <td style="padding:5px;width: 15%;border-top: 2px solid #000;border-right: 2px solid #000 ;text-align: center"><h3 style="font-size: 14px;text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($order_details['order']['pendrive_price'],2)}}</h3></td>
        </tr>
        @endif
        @if($order_details['order']['coupon_amount'])
        <tr>
            <td style="padding:5px;width: 48%;  border-left: 2px solid #000;@if(!$order_details['order']['pendrive_price'])border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: left;">COUPON</h3></td>
            <td style="padding:5px;width: 15%; @if(!$order_details['order']['pendrive_price'])border-top: 2px solid #000;@endif"></td>
            <td style="padding:5px;width: 15%;text-align: right;text-align: center;border-right: 2px solid #000; @if(!$order_details['order']['pendrive_price'])border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: center;">- <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{number_format($order_details['order']['coupon_amount'],2)}}</h3></td>
        </tr>
        @endif
        @if($order_details['order']['reward_amount'])
        <tr>
            <td style="padding:5px;width: 48%; border-left: 2px solid #000 ; @if(empty($order_details['order']['pendrive_price']) || empty($order_details['order']['coupon_amount'])) border-top: 2px solid #000;@endif">
            <h3 style="font-size: 14px;text-align: left;">REWARD @if(!empty($order_details['order']['spin_wheel_reward_text']))({{ $order_details['order']['spin_wheel_reward_text']  }}) @endif</h3>
            </td>
            <td style="padding:5px;width: 15%; @if(empty($order_details['order']['pendrive_price']) || empty($order_details['order']['coupon_amount'])) border-top: 2px solid #000;@endif"></td>
            <td style="padding:5px;width: 15%;text-align: right;border-right: 2px solid #000; @if(empty($order_details['order']['pendrive_price'])|| empty($order_details['order']['coupon_amount'])) border-top: 2px solid #000;@endif">
            <h3 style="font-size: 14px;text-align: center;">- <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{number_format($order_details['order']['reward_amount'],2)}} </h3>
            </td>
        </tr>
        @endif
        @if($order_details['order']['holiday_offer_amount'])
        <tr>
            <td style="padding:5px;width: 48%; border-left: 2px solid #000; @if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount']) || empty($order_details['order']['reward_amount'])) border-top: 2px solid #000;@endif">
            <h3 style="font-size: 14px;text-align: left;"> {{@$holiday_offer_name}} Discount </h3>
            </td>
            <td style="padding:5px;width: 15%; @if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount']) || empty($order_details['order']['reward_amount'])) border-top:2px solid #000;@endif"></td>
            <td style="padding:5px;width: 15%;text-align: right;border-right: 2px solid #000;  @if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount']) || empty($order_details['order']['reward_amount'])) border-top: 2px solid #000;@endif">
            <h3 style="font-size: 14px;text-align: center;">- <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{number_format($order_details['order']['holiday_offer_amount'],2)}} </h3>
            </td>
        </tr>
        @endif
        @if(strtoupper($order_details['order']['state']) =='MAHARASHTRA')
        <tr>
            <td style="padding:10px;width: 48%;border-left: 2px solid #000; @if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount'])||empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"></td>
            <td style="padding:10px;width: 15%;border-left: 2px solid #000; @if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount'])||empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: center;">CGST ({{$order_details['order']['cgst']}}%)</h3></td>
            <td style="padding:10px;width: 15%;border-left: 2px solid #000;border-right: 2px solid #000; @if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount'])||empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: center;"><h3 style="font-size: 14px;text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{$order_details['order']['cgst_amount']}}</h3></td>
        </tr>
        <tr>
            <td style="padding:10px;width: 48%;border-left: 2px solid #000;border-top: 2px solid #000;"></td>
            <td style="padding:10px;width: 15%;border-left: 2px solid #000;border-top: 2px solid #000;"><h3 style="font-size: 14px;text-align: center;">SGST ({{$order_details['order']['sgst']}}%)</h3></td>
            <td style="padding:10px;width: 15%;border-left: 2px solid #000;border-right: 2px solid #000;border-top: 2px solid #000;"><h3 style="font-size: 14px;text-align: center;"><h3 style="font-size: 14px;text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{$order_details['order']['sgst_amount']}}</h3></td>
        </tr>
        @else
        <tr>
            <td style="padding:10px;width: 48%;border-left: 2px solid #000; @if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount'])||empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"></td>
            <td style="padding:10px;width: 15%;border-left: 2px solid #000; @if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount'])||empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: center;">IGST ({{$order_details['order']['igst']}}%)</h3></td>
            <td style="padding:10px;width: 15%;border-left: 2px solid #000;border-right: 2px solid #000; @if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount'])|| empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: center;"><h3 style="font-size: 14px;text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{$order_details['order']['igst_amount']}}</h3></td>
        </tr>
        @endif
     
    <tr>
        <td style="padding:10px;width:48%;border-left: 2px solid #000;border-bottom: 2px solid #000;@if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount'])||empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: center;">{{ strtoupper(\Riskihajar\Terbilang\Facades\Terbilang::make($order_details['net_amount']) )}} RUPEES ONLY</h3></td>
        <td style="padding:10px;width:15%;border-bottom: 2px solid #000;border-left: 2px solid #000;@if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount'])||empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: center;">TOTAL<br> AMOUNT @if ($order_details['order']['is_refunded']) REFUNDED @else PAID @endif</h3></td>
        @if ($order_details['order']['commission'])
            <td style="padding:10px;width: 15%;text-align: right;border-bottom: 2px solid #000;border-left: 2px solid #000;border-right: 2px solid #000;@if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount']) || empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ number_format($order_details['order']['net_amount'],2)}}</h3></td>
        @else
            <td style="padding:10px;width: 15%;text-align: right;border-bottom: 2px solid #000;border-left: 2px solid #000;border-right: 2px solid #000;@if(empty($order_details['order']['pendrive_price'])||empty($order_details['order']['coupon_amount'])||empty($order_details['order']['reward_amount']) || empty($order_details['order']['holiday_offer_amount'])) border-top: 2px solid #000;@endif"><h3 style="font-size: 14px;text-align: center;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ number_format($order_details['order']['net_amount'],2)}}</h3></td>
        @endif
    </tr>

    </tbody>
</table>

<table class="" width="100%;" style="margin-top: 50px;border-spacing: 0;" border="0">
    <div class="signature" style="width: 40%;margin-left: auto;margin-bottom: 78px;text-align: center;">
        <span style="border-bottom: 2px solid #000;width: 100%;margin-bottom: 10px;display: block;"></span>
        <h3 style="font-size: 18px;margin:0;">Authorised Signatory</h3>
        <p style="font-size: 14px;margin:0;">This is a computer generated Invoice.</p>
        <p style="font-size: 14px;margin:0;">No Signature required</p>
    </div>
</table>
