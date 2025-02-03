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
            /*border: 1px solid #f58457;*/
            border-bottom: 1px solid #f58f3d;
            border-right: 1px solid #f58f3d;
            padding: 0.5em 1em;
        }


        /*.invoice{*/
            /*background-color: #f58457;*/
            /*color: white;*/
            /*font-weight: bold;*/
        /*}*/
        /*.invoice-border{*/
            /*border: 1px solid #f58457;*/
        /*}*/
        /*.table-bordered td {*/
            /*border: 1px solid #ffc9a3;*/
        /*}*/
        /*.table-bordered th {*/
            /*border: 1px solid #ffc9a3;*/
        /*}*/
        /*.payment-details-table{*/
            /*border: #f58457 1px solid !important;*/
            /*background: #f58457;*/
            /*color: whitesmoke;*/
        /*}*/
        /*.price-details{*/
            /*border: #f58457 1px solid !important;*/
            /*background: #f5a884;*/
        /*}*/
        /*#gst {*/
            /*width: 200px;*/
            /*height: 100px;*/
            /*background: coral;*/
        /*}*/

        /*.transparent-border{*/
            /*border: 1px solid white;*/
            /*background: white;*/
        /*}*/

        /*.receipt{*/
            /*color: #f58457;*/
        /*}*/
    </style>
</head>


<table width="100%" border="0">
    <tbody>
    <tr>
        <td style="width: 70%">
            <img src="{{url('images/logo.png')}}" alt="JK Shah Classes Online" title="JK Shah Classes Online" width="100px" height="50">
        </td>
        <td style="width: 30%">
            <div style="font-size: 25pt; color: #f58457"><b>RECEIPT</b></div>
            <div style="font-size: medium;color: #f58457"><b>#{{str_pad($order_detailss['receipt_no'], 6, "0", STR_PAD_LEFT)}}</b></div>
            <div style="font-size: medium;color: #f58457"><b>{{ Carbon\Carbon::createFromTimeString($order_detailss['created_at'])->format('d/m/Y')}}</b></div>
        </td>
    </tr>
    </tbody>
</table>

<table width="100%;" style="margin-top: 50px" border="0">
    <tbody>
    <tr>
        <td style="width: 40%">
            <div style="padding:10px;color:#ffffff;text-align: center; background-color: #f58457; font-size: large">FROM</div>
            <div style="border: 1px solid #f58457">
                <div style="padding: 5px;">J.K. Shah Education Pvt Ltd</div>
                <div style="padding: 5px;">3rd Floor, Shraddha,</div>
                <div style="padding: 5px;">Old Nagardas Road, Andheri East,</div>
                <div style="padding: 5px;">Mumbai 400069</div>
                <div style="padding: 5px;">GSTN : {{$gstn}}</div>
                <div>&nbsp;</div>
            </div>


        </td>
        <td style="width: 20%">

        </td>
        <td style="width: 40%">
            <div style="padding:10px;color:#ffffff;text-align: center; background-color: #f58457; font-size: large">TO</div>
            <div style="border: 1px solid #f58457">
                <div style="padding: 5px;">{{ strtoupper($order_detailss['order']['name']) }}</div>
                <div style="padding: 5px;">{{ strtoupper($order_detailss['order']['address']) }}</div>
                <div style="padding: 5px;">{{ strtoupper($order_detailss['order']['city']) }}</div>
                <div style="padding: 5px;">{{ strtoupper($order_detailss['order']['state']) }}</div>
                <div style="padding: 5px;">{{ strtoupper($order_detailss['order']['pin']) }}</div>
            </div>
        </td>

    </tr>
    </tbody>
</table>
<table class="items" width="100%;" style="margin-top: 50px;border-spacing: 0;" border="0">
    <tbody>
    <tr style="border: 1px solid #f58457" >
        <td style="padding:8px;width: 56%; color:#ffffff;background-color: #f58457; ">DESCRIPTION</td>
{{--        <td style="padding:8px;width: 18%; color:#ffffff;background-color: #f58457;">UNIT COST</td>--}}
{{--        <td style="padding:8px;width: 13%;color:#ffffff;background-color: #f58457;">TAX</td>--}}
        <td style="padding:8px;width: 13%;color:#ffffff;background-color: #f58457;text-align: right">AMOUNT</td>
    </tr>
    @foreach($order_item as $item)
        <tr style="border: 1px solid #f58457">
            <td style="padding:8px;width: 56%; border-left: solid 1px  #f58457">@if ($order_detailss['order']['is_refunded']) [REFUND] - @endif {{ $item['package']['name'] }} @if ($item['item_type'] == 2) (Study Material) @endif</td>
            <td style="padding:8px;width: 13%;text-align: right">
                @if ($item['pivot']['is_balance_payment'])
                    {{ number_format($item['balance_amount'], 2) }}
                @else
                    @if ($item['is_prebook'])
                        {{ number_format($item['booking_amount'], 2) }}
                    @else
                        {{ number_format($item['price'], 2) }}
                    @endif
                @endif
            </td>
        </tr>
    @endforeach

    </tbody>
</table>

<table class="items" width="100%;" style="margin-top: 50px;border-spacing: 0;" border="0">
    <tbody>
{{--    <tr>--}}
{{--        <td style="padding:8px;width: 56%; border-left: solid 1px  #f58457 ;border-top: 1px solid #f58f3d;">PAYMENT MADE BY : @if($order_detailss['payment_mode']==1) ONLINE @else CASH ON DELIVERY @endif</td>--}}
{{--        <td style="padding:8px;width: 31%;border-top: 1px solid #f58f3d;background-color: #f58457;color:#ffffff">NET TOTAL</td>--}}
{{--        <td style="padding:8px;width: 13%;border-top: 1px solid #f58f3d;text-align: right">{{ number_format($order_detailss['net_amount_without_tax'],2)}}</td>--}}
{{--    </tr>--}}
    @if($order_detailss['order']['pendrive_price'])
        <tr>
            <td style="padding:8px;width: 56%;border-top: 1px solid #f58f3d; border-left: solid 1px  #f58457 ;">PENDRIVE</td>
            <td style="padding:8px;width: 31%;border-top: 1px solid #f58f3d;color:#ffffff"></td>
            <td style="padding:8px;width: 13%;border-top: 1px solid #f58f3d;text-align: right"> {{number_format($order_detailss['order']['pendrive_price'],2)}}</td>
        </tr>
    @endif
    @if($order_detailss['order']['coupon_amount'])
        <tr>
            <td style="padding:8px;width: 56%;  border-left: solid 1px  #f58457; @if(!$order_detailss['order']['pendrive_price'])border-top: 1px solid #f58f3d;@endif">COUPON</td>
            <td style="padding:8px;width: 31%;color:#ffffff; @if(!$order_detailss['order']['pendrive_price'])border-top: 1px solid #f58f3d;@endif"></td>
            <td style="padding:8px;width: 13%;text-align: right; @if(!$order_detailss['order']['pendrive_price'])border-top: 1px solid #f58f3d;@endif">- {{number_format($order_detailss['order']['coupon_amount'],2)}}</td>
        </tr>
    @endif
    @if($order_detailss['order']['reward_amount'])
        <tr>
            <td style="padding:8px;width: 56%; border-left: solid 1px  #f58457 ; @if(!($order_detailss['order']['pendrive_price']||$order_detailss['order']['coupon_amount'])) border-top: 1px solid #f58f3d;@endif">
                REWARD
            </td>
            <td style="padding:8px;width: 31%; @if(!($order_detailss['order']['pendrive_price']||$order_detailss['order']['coupon_amount'])) border-top: 1px solid #f58f3d;@endif">{{ $order_detailss['order']['spin_wheel_reward_text'] ?? null }}</td>
            <td style="padding:8px;width: 13%;text-align: right;  @if(!($order_detailss['order']['pendrive_price']||$order_detailss['order']['coupon_amount'])) border-top: 1px solid #f58f3d;@endif">
                - {{number_format($order_detailss['order']['reward_amount'],2)}}
            </td>
        </tr>
    @endif
    <tr>
        <td style="padding:8px;width: 56%; border-left: solid 1px  #f58457; @if(!($order_detailss['order']['pendrive_price']||$order_detailss['order']['coupon_amount']||$order_detailss['order']['reward_amount'])) border-top: 1px solid #f58f3d;@endif"></td>
        <td style="padding:8px;width: 31%;background-color: #f58457;color:#ffffff; @if(!($order_detailss['order']['pendrive_price']||$order_detailss['order']['reward_amount']||$order_detailss['order']['reward_amount'])) border-top: 1px solid #f58f3d;@endif">
            @if(strtoupper($order_detailss['order']['state']) =='MAHARASHTRA')
                    CGST ({{$order_detailss['order']['cgst']}}%) <br> SGST ({{$order_detailss['order']['sgst']}}%)
            @else   IGST ({{$order_detailss['order']['igst']}}%)
            @endif
        </td>
        <td style="padding:8px;width: 13%;text-align: right;@if(!($order_detailss['order']['pendrive_price']||$order_detailss['order']['reward_amount']||$order_detailss['order']['reward_amount'])) border-top: 1px solid #f58f3d;@endif">
            @if($order_detailss['order']['cgst_amount'] && $order_detailss['order']['sgst_amount'])
                {{ number_format($order_detailss['order']['cgst_amount'], 2) }}<br>{{ number_format($order_detailss['order']['sgst_amount'], 2) }}
            @elseif($order_detailss['order']['igst_amount'] )
                {{  number_format($order_detailss['order']['igst_amount'],2)}}
            @else 0
            @endif
        </td>
    </tr>
    <tr>
        @php 
        $f = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );

$word = $f->format($order_detailss['order']['net_amount']);
        @endphp
        <td style="padding:8px;width: 56%; border-left: solid 1px  #f58457;">{{ strtoupper($word) }} RUPEES ONLY</td>
        <td style="padding:8px;width: 31%;background-color: #f58457; color:#ffffff">TOTAL AMOUNT @if ($order_detailss['order']['is_refunded']) REFUNDED @else PAID @endif</td>
        @if ($order_detailss['order']['commission'])
            <td style="padding:8px;width: 13%;text-align: right">{{ number_format($order_detailss['order']['net_amount'],2)}}</td>
        @else
            <td style="padding:8px;width: 13%;text-align: right">{{ number_format($order_detailss['order']['net_amount'],2)}}</td>
        @endif
    </tr>

    </tbody>
</table>

<table class="" width="100%;" style="margin-top: 50px;border-spacing: 0;" border="0">
    <tbody>
    <tr>
        <td style="padding:8px;width: 56%; "></td>
        <td style="padding:8px;width: 44%;border-top: 1px solid #f58f3d; text-align: center; font-style:bold">Authorised Signatory</td>
    </tr>
    <tr>
        <td style="padding:8px;width: 10%; "></td>
        <td style="font-style: italic;padding:8px;width: 90%; text-align: right">This is a computer generated invoice. No signature required</td>
    </tr>


    </tbody>
</table>
