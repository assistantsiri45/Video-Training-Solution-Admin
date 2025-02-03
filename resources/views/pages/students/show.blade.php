@extends('adminlte::page')

@section('title', $student->name)

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">{{ $student->name }}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-widget bg-primary">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Email</h5>
                                <span>{{ $student->email }}</span>
                            </div>
                        </div>
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Phone</h5>
                                <span class="description-text">{{ $student->phone }}</span>
                            </div>
                        </div>
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Course</h5>
                                <span class="description-text"> @if($student->course){{ $student->course->name }}@else - @endif</span>
                            </div>
                        </div>
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Level</h5>
                                <span class="description-text"> @if($student->level){{ $student->level->name }}@else - @endif</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">Address</h5>
                                @if ($student->address)
                                    <span>{{ $student->address }}</span><br>
                                @endif
                                <span>{{ $student->city ? $student->city . ', ' : '' }}{{ $student->state->name ? $student->state->name . ', ' : '' }}{{ $student->country->name ? $student->country->name . ' - ' : '' }}{{ $student->pin ? $student->pin : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-orders" data-toggle="pill" href="#tab-orders-content" role="tab" aria-controls="tab-orders-content" aria-selected="true">ORDERS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-cart-items" data-toggle="pill" href="#tab-cart-items-content" role="tab" aria-controls="tab-cart-items-content" aria-selected="false">CART ITEMS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-applied-coupons" data-toggle="pill" href="#tab-applied-coupons-content" role="tab" aria-controls="tab-applied-coupons-content" aria-selected="false">APPLIED COUPONS</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="tab-orders-content" role="tabpanel" aria-labelledby="tab-orders">
                            {!! $tableOrders->table(['id' => 'table-orders'], true) !!}
                        </div>
                        <div class="tab-pane fade" id="tab-cart-items-content" role="tabpanel" aria-labelledby="tab-cart-items">
                            {!! $tableCart->table(['id' => 'table-cart'], true) !!}
                        </div>
                        <div class="tab-pane fade" id="tab-applied-coupons-content" role="tabpanel" aria-labelledby="tab-applied-coupons">
                            {!! $tableCoupons->table(['id' => 'table-coupons'], true) !!}
                        </div>
                        <div class="text-center">TOTAL REWARDS EARNED: <b>₹{{ $rewardsEarned }}</b> | TOTAL REWARDS GAINED: <b>₹{{ $rewardsGained }}</b></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $tableOrders->scripts() !!}
    {!! $tableCart->scripts() !!}
    {!! $tableCoupons->scripts() !!}

    <script>
        $(function() {
            $('.buttons-csv').hide();
            $('.buttons-pdf').hide();
        })
    </script>
@stop

