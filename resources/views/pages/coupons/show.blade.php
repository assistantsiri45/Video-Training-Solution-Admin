@extends('adminlte::page')

@section('title', 'Coupon')

@section('content_header')
    <h1 class="m-0 text-dark">Coupon</h1>

    <div class="col text-right">
        <form method="POST"  action="{{url('update-coupon-status',$coupon->id)}}" >
            @csrf
            @if($coupon->status==1||$coupon->status==3)
            <button id="update-status" type="submit" name="status" value="2" class="btn btn-success update-status">Publish</button>
                @elseif($coupon->status==1||$coupon->status==2)
            <button id="update-status" type="submit" value="3" name="status" class="btn btn-warning update-status">Unpublish</button>
            @endif
        </form>
    </div>
@stop

@section('css')
    <style>
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3 col-md-3 text-center mb-4">
                            <div>{{$coupon->name}}</div>
                            <b> Coupon</b>
                        </div>
                        <div class="col-sm-3 col-md-3 text-center mb-4">
                            @if($coupon->amount_type==1)
                                <div>FLAT  Rs {{$coupon->amount}}/- OFF</div>
                            @endif
                            @if($coupon->amount_type==2)
                                 <div>{{$coupon->amount}} % OFF</div>
                            @endif
                            @if($coupon->amount_type == 3)
                                <div>Rs. {{ $coupon->amount }} (Fixed Price)</div>
                            @endif
                            <b> Coupon amount</b>
                        </div>
                        @if ($coupon->amount_type != 3)
                        <div class="col-sm-3 col-md-3 text-center mb-4">
                            <div>{{$coupon->coupon_per_user}}</div>
                            <b> Total coupon per user</b>
                        </div>
                        @endif
                        @if ($coupon->amount_type != 3)
                        <div class="col-sm-3 col-md-3 text-center mb-4">
                            <div>{{$coupon->total_coupon_limit}}</div>
                            <b> Total coupon limit</b>
                        </div>
                        @endif
                        <div class="col-sm-3 col-md-3 text-center mb-4">
                            <div>{{$coupon->valid_from}} - {{$coupon->valid_to}}</div>
                            <b>Validity</b>
                        </div>
                        @if ($coupon->amount_type != 3)
                        @if($coupon->min_purchase_amount)
                        <div class="col-sm-3 col-md-3 text-center mb-4">
                            <div> Rs {{$coupon->min_purchase_amount}}/-</div>
                            <b>Minimum purchase amount</b>
                        </div>
                        @endif
                        @endif
                        @if ($coupon->amount_type != 3)
                        @if($coupon->max_purchase_amount)
                        <div class="col-sm-3 col-md-3 text-center">
                            <div> Rs {{$coupon->max_purchase_amount}}/-</div>
                            <b>Maximum purchase amount</b>
                        </div>
                        @endif
                        @endif
                        @if ($coupon->amount_type == 2)
                        <div class="col-sm-3 col-md-3 text-center mb-4">
                            <div> Rs {{$coupon->max_discount_amount}}/-</div>
                            <b>Maximum discount amount</b>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($order_count)
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {!! $html->table(['id' => 'datatable'], true) !!}
                </div>
            </div>
        </div>
        @endif

    </div>
@stop

@section('js')
    {!! $html->scripts() !!}
    <script>
        {{--(function($){--}}
        {{--    $(".update-status").click(function () {--}}

        {{--        var status = $(".update-status").val();--}}
        {{--        let confirmation = confirm("Are you sure?");--}}
        {{--        if (confirmation) {--}}
        {{--            $.ajax({--}}
        {{--                url: "{{ url('update-coupon-status') }}",--}}
        {{--                type: "POST",--}}
        {{--                data: {--}}
        {{--                    _token: "{{ csrf_token() }}",--}}
        {{--                    'id': {{$coupon->id}},--}}
        {{--                    'status': status--}}
        {{--                },--}}
        {{--                success: function(result) {--}}
        {{--                    if (result) {--}}
        {{--                        location.reload;--}}
        {{--                    }--}}
        {{--                }--}}
        {{--            });--}}
        {{--        }--}}
        {{--    });--}}
        {{--})(jQuery);--}}

    </script>
@stop
