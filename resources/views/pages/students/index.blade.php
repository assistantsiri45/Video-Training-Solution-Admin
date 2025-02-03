@extends('adminlte::page')

@section('title', 'Students')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Students</h1>
        </div>
    </div>
@stop

@section('content')
{{--    <div class="row">--}}
{{--        <div class="col-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-3">--}}
{{--                            <select id="select-date-mode" class="form-control">--}}
{{--                                <option value="y">Year</option>--}}
{{--                                <option value="m">Month</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3">--}}
{{--                            <input id="input-year" type="text" class="form-control float-right" placeholder="Chose Year" value="{{ date('Y') }}">--}}
{{--                            <input id="input-month" type="text" class="form-control float-right" placeholder="Chose Month" style="display: none" value="{{ date('m') . '-' . date('Y') }}">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="m-1 p-5 border">--}}
{{--                                <p class="text-center"><strong>SIGN-UP</strong></p>--}}
{{--                                <div id="bar-sign-up" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;" width="728" height="300"></canvas><canvas class="flot-overlay" width="728" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;"></canvas><div class="flot-svg" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; pointer-events: none;"><svg style="width: 100%; height: 100%;"><g class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="153.14772727272725" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">February</text><text x="284.14559659090907" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">March</text><text x="409.1200284090909" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">April</text><text x="532.1257102272727" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">May</text><text x="34.59517045454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">January</text><text x="649.7251420454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">June</text></g><g class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="7.953125" y="269" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">0</text><text x="7.953125" y="205.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">5</text><text x="1" y="15" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">20</text><text x="1" y="142" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">10</text><text x="1" y="78.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">15</text></g></svg></div></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="m-1 p-5 border">--}}
{{--                                <p class="text-center"><strong>ORDERS</strong></p>--}}
{{--                                <div id="bar-order" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;" width="728" height="300"></canvas><canvas class="flot-overlay" width="728" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;"></canvas><div class="flot-svg" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; pointer-events: none;"><svg style="width: 100%; height: 100%;"><g class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="153.14772727272725" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">February</text><text x="284.14559659090907" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">March</text><text x="409.1200284090909" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">April</text><text x="532.1257102272727" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">May</text><text x="34.59517045454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">January</text><text x="649.7251420454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">June</text></g><g class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="7.953125" y="269" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">0</text><text x="7.953125" y="205.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">5</text><text x="1" y="15" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">20</text><text x="1" y="142" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">10</text><text x="1" y="78.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">15</text></g></svg></div></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="mt-3 mr-1 mb-1 ml-1 p-5 border">--}}
{{--                                <p class="text-center"><strong>CART</strong></p>--}}
{{--                                <div id="bar-cart" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;" width="728" height="300"></canvas><canvas class="flot-overlay" width="728" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;"></canvas><div class="flot-svg" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; pointer-events: none;"><svg style="width: 100%; height: 100%;"><g class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="153.14772727272725" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">February</text><text x="284.14559659090907" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">March</text><text x="409.1200284090909" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">April</text><text x="532.1257102272727" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">May</text><text x="34.59517045454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">January</text><text x="649.7251420454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">June</text></g><g class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="7.953125" y="269" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">0</text><text x="7.953125" y="205.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">5</text><text x="1" y="15" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">20</text><text x="1" y="142" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">10</text><text x="1" y="78.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">15</text></g></svg></div></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input id="sign-up-date" type="text" class="form-control float-right" placeholder="Date of Sign-Up">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button id="btn-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                            <button id="button-export" class="btn btn-primary ml-2">Export</button>
                        </div>
                    </div>
                </div>
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
    <form id="form-export" method="POST" action="{{ url('reports/students/export') }}">
        @csrf
        <input id="export-sign-up-date" type="hidden" name="export_sign_up_date">
    </form>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            let table = $('#datatable').DataTable();

            $('#sign-up-date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    sign_up_date: $('#sign-up-date').val()
                }
            });

            $('#btn-filter').click(function() {
                table.draw();
            });

            let inputYear = $('#input-year');
            let inputMonth = $('#input-month');

            inputYear.datepicker({
                format: 'yyyy',
                viewMode: 'years',
                minViewMode: 'years',
                autoclose: true
            });

            inputMonth.datepicker({
                format: 'mm-yyyy',
                viewMode: 'months',
                minViewMode: 'months',
                autoclose: true
            });

            let selectDateMode = $('#select-date-mode').select2();

            /* BAR CHARTS */

            // SIGN-UP
            let signUpData = [];
            let signUpXAxis = [];

            $.ajax({
                url: '{{ url('reports/students-bar-data-by-year') }}',
                data: {
                    year: $('#input-year').val()
                },
                async: false
            }).done(function(response) {
                $.each(response.signUp.data, function(key, value) {
                    signUpData.push([parseInt(key), parseInt(value)]);
                });

                $.each(response.signUp.xAxis, function(key, value) {
                    signUpXAxis.push([parseInt(key), value]);
                });
            });

            let barSignUpData = {
                data : signUpData,
                bars: { show: true }
            }

            let signUpPlot = $.plot('#bar-sign-up', [barSignUpData], {
                grid  : {
                    borderWidth: 1,
                    borderColor: '#f3f3f3',
                    tickColor  : '#f3f3f3'
                },
                series: {
                    bars: {
                        show: true, barWidth: 0.5, align: 'center',
                    }
                },
                colors: ['#3c8dbc'],
                xaxis : {
                    ticks: signUpXAxis
                }
            });

            function getSignUpBarDataByMonth() {
                let signUpData = [];
                let signUpXAxis = [];

                $.ajax({
                    url: '{{ url('reports/students-bar-data-by-month') }}',
                    data: {
                        month: $('#input-month').val()
                    },
                    async: false
                }).done(function(response) {
                    $.each(response.signUp.data, function(key, value) {
                        signUpData.push([parseInt(key), parseInt(value)]);
                    });

                    signUpXAxis = response.signUp.xAxis;
                });

                signUpPlot.setData([{
                    data : signUpData,
                    bars: { show: true }
                }]);

                let signUpTicks = signUpPlot.getAxes().xaxis.options.ticks;

                signUpTicks.length = 0;

                $.each(signUpXAxis, function(key, value) {
                    signUpTicks.push([parseInt(key), value]);
                });

                signUpPlot.setupGrid();
                signUpPlot.draw();
            }

            function getSignUpBarDataByYear() {
                let signUpData = [];
                let signUpXAxis = [];

                $.ajax({
                    url: '{{ url('reports/students-bar-data-by-year') }}',
                    data: {
                        year: $('#input-year').val()
                    },
                    async: false
                }).done(function(response) {
                    $.each(response.signUp.data, function(key, value) {
                        signUpData.push([parseInt(key), parseInt(value)]);
                    });

                    signUpXAxis = response.signUp.xAxis;
                });

                signUpPlot.setData([{
                    data : signUpData,
                    bars: { show: true }
                }]);

                let signUpTicks = signUpPlot.getAxes().xaxis.options.ticks;

                signUpTicks.length = 0;

                $.each(signUpXAxis, function(key, value) {
                    signUpTicks.push([parseInt(key), value]);
                });

                signUpPlot.setupGrid();
                signUpPlot.draw();
            }

            // ORDER
            let orderData = [];
            let orderXAxis = [];

            $.ajax({
                url: '{{ url('reports/students-bar-data-by-year') }}',
                data: {
                    year: $('#input-year').val()
                },
                async: false
            }).done(function(response) {
                $.each(response.order.data, function(key, value) {
                    orderData.push([parseInt(key), parseInt(value)]);
                });

                $.each(response.order.xAxis, function(key, value) {
                    orderXAxis.push([parseInt(key), value]);
                });
            });

            let barOrderData = {
                data : orderData,
                bars: { show: true }
            }

            let orderPlot = $.plot('#bar-order', [barOrderData], {
                grid  : {
                    borderWidth: 1,
                    borderColor: '#f3f3f3',
                    tickColor  : '#f3f3f3'
                },
                series: {
                    bars: {
                        show: true, barWidth: 0.5, align: 'center',
                    }
                },
                colors: ['#3c8dbc'],
                xaxis : {
                    ticks: orderXAxis
                }
            });

            function getOrderBarDataByMonth() {
                let orderData = [];
                let orderXAxis = [];

                $.ajax({
                    url: '{{ url('reports/students-bar-data-by-month') }}',
                    data: {
                        month: $('#input-month').val()
                    },
                    async: false
                }).done(function(response) {
                    $.each(response.order.data, function(key, value) {
                        orderData.push([parseInt(key), parseInt(value)]);
                    });

                    orderXAxis = response.order.xAxis;
                });

                orderPlot.setData([{
                    data : orderData,
                    bars: { show: true }
                }]);

                let orderTicks = orderPlot.getAxes().xaxis.options.ticks;

                orderTicks.length = 0;

                $.each(orderXAxis, function(key, value) {
                    orderTicks.push([parseInt(key), value]);
                });

                orderPlot.setupGrid();
                orderPlot.draw();
            }

            function getOrderBarDataByYear() {
                let orderData = [];
                let orderXAxis = [];

                $.ajax({
                    url: '{{ url('reports/students-bar-data-by-year') }}',
                    data: {
                        year: $('#input-year').val()
                    },
                    async: false
                }).done(function(response) {
                    $.each(response.order.data, function(key, value) {
                        orderData.push([parseInt(key), parseInt(value)]);
                    });

                    orderXAxis = response.order.xAxis;
                });

                orderPlot.setData([{
                    data : orderData,
                    bars: { show: true }
                }]);

                let orderTicks = orderPlot.getAxes().xaxis.options.ticks;

                orderTicks.length = 0;

                $.each(orderXAxis, function(key, value) {
                    orderTicks.push([parseInt(key), value]);
                });

                orderPlot.setupGrid();
                orderPlot.draw();
            }

            // CART
            let cartData = [];
            let cartXAxis = [];

            $.ajax({
                url: '{{ url('reports/students-bar-data-by-year') }}',
                data: {
                    year: $('#input-year').val()
                },
                async: false
            }).done(function(response) {
                $.each(response.cart.data, function(key, value) {
                    cartData.push([parseInt(key), parseInt(value)]);
                });

                $.each(response.cart.xAxis, function(key, value) {
                    cartXAxis.push([parseInt(key), value]);
                });
            });

            let barCartData = {
                data : cartData,
                bars: { show: true }
            }

            let cartPlot = $.plot('#bar-cart', [barCartData], {
                grid  : {
                    borderWidth: 1,
                    borderColor: '#f3f3f3',
                    tickColor  : '#f3f3f3'
                },
                series: {
                    bars: {
                        show: true, barWidth: 0.5, align: 'center',
                    }
                },
                colors: ['#3c8dbc'],
                xaxis : {
                    ticks: cartXAxis
                }
            });

            function getCartBarDataByMonth() {
                let cartData = [];
                let cartXAxis = [];

                $.ajax({
                    url: '{{ url('reports/students-bar-data-by-month') }}',
                    data: {
                        month: $('#input-month').val()
                    },
                    async: false
                }).done(function(response) {
                    $.each(response.cart.data, function(key, value) {
                        cartData.push([parseInt(key), parseInt(value)]);
                    });

                    cartXAxis = response.cart.xAxis;
                });

                cartPlot.setData([{
                    data : cartData,
                    bars: { show: true }
                }]);

                let cartTicks = cartPlot.getAxes().xaxis.options.ticks;

                cartTicks.length = 0;

                $.each(cartXAxis, function(key, value) {
                    cartTicks.push([parseInt(key), value]);
                });

                cartPlot.setupGrid();
                cartPlot.draw();
            }

            function getCartBarDataByYear() {
                let cartData = [];
                let cartXAxis = [];

                $.ajax({
                    url: '{{ url('reports/students-bar-data-by-year') }}',
                    data: {
                        year: $('#input-year').val()
                    },
                    async: false
                }).done(function(response) {
                    $.each(response.cart.data, function(key, value) {
                        cartData.push([parseInt(key), parseInt(value)]);
                    });

                    cartXAxis = response.cart.xAxis;
                });

                cartPlot.setData([{
                    data : cartData,
                    bars: { show: true }
                }]);

                let cartTicks = cartPlot.getAxes().xaxis.options.ticks;

                cartTicks.length = 0;

                $.each(cartXAxis, function(key, value) {
                    cartTicks.push([parseInt(key), value]);
                });

                cartPlot.setupGrid();
                cartPlot.draw();
            }

            selectDateMode.change(function() {
                if ($(this).val() === 'm') {
                    inputYear.css('display', 'none');
                    inputMonth.css('display', 'block');

                    // SIGN-UP
                    getSignUpBarDataByMonth();
                    // ORDER
                    getOrderBarDataByMonth();
                    // CART
                    getCartBarDataByMonth();
                } else {
                    inputMonth.css('display', 'none');
                    inputYear.css('display', 'block');

                    // SIGN-UP
                    getSignUpBarDataByYear();
                    // ORDER
                    getOrderBarDataByYear();
                    // CART
                    getCartBarDataByYear();
                }
            });

            inputMonth.change(function() {
                // SIGN-UP
                getSignUpBarDataByMonth();
                // ORDER
                getOrderBarDataByMonth();
                // CART
                getCartBarDataByMonth();
            });

            inputYear.change(function() {
                // SIGN-UP
                getSignUpBarDataByYear();
                // ORDER
                getOrderBarDataByYear();
                // CART
                getCartBarDataByYear();
            });

            $('#btn-clear').click(function() {
                $('#sign-up-date').val('').change();
                table.draw();
            });

            $('#button-export').click(function() {
                $('#export-sign-up-date').val($('#sign-up-date').val());
                $('#form-export').submit();
            });


            /* END BAR CHARTS */
        });
    </script>
@stop
