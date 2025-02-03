@extends('adminlte::page')

@section('title', $package->name)

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">{{ $package->name }}</h1>
        </div>
    </div>
@stop

@section('content')
{{--    <div class="row">--}}
{{--        <div class="col-md-12">--}}
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
{{--                                <p class="text-center"><strong>SALES</strong></p>--}}
{{--                                <div id="bar-count" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;" width="728" height="300"></canvas><canvas class="flot-overlay" width="728" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;"></canvas><div class="flot-svg" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; pointer-events: none;"><svg style="width: 100%; height: 100%;"><g class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="153.14772727272725" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">February</text><text x="284.14559659090907" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">March</text><text x="409.1200284090909" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">April</text><text x="532.1257102272727" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">May</text><text x="34.59517045454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">January</text><text x="649.7251420454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">June</text></g><g class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="7.953125" y="269" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">0</text><text x="7.953125" y="205.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">5</text><text x="1" y="15" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">20</text><text x="1" y="142" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">10</text><text x="1" y="78.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">15</text></g></svg></div></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}
{{--                            <div class="m-1 p-5 border">--}}
{{--                                <p class="text-center"><strong>SALES AMOUNT (₹)</strong></p>--}}
{{--                                <div id="bar-amount" style="height: 300px; padding: 0px; position: relative;"><canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;" width="728" height="300"></canvas><canvas class="flot-overlay" width="728" height="300" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 728.5px; height: 300px;"></canvas><div class="flot-svg" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; pointer-events: none;"><svg style="width: 100%; height: 100%;"><g class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="153.14772727272725" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">February</text><text x="284.14559659090907" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">March</text><text x="409.1200284090909" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">April</text><text x="532.1257102272727" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">May</text><text x="34.59517045454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">January</text><text x="649.7251420454545" y="294" class="flot-tick-label tickLabel" style="position: absolute; text-align: center;">June</text></g><g class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px;"><text x="7.953125" y="269" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">0</text><text x="7.953125" y="205.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">5</text><text x="1" y="15" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">20</text><text x="1" y="142" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">10</text><text x="1" y="78.5" class="flot-tick-label tickLabel" style="position: absolute; text-align: right;">15</text></g></svg></div></div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="row">
        <div class="col-md-2">
            <div class="card text-white bg-info mb-3">
                <div class="card-header text-center">SALES</div>
                <div class="card-body">
                    <h1 class="text-center">{{ $packageSales }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-info mb-3">
                <div class="card-header text-center">SALES AMOUNT</div>
                <div class="card-body">
                    <h1 class="text-center">₹ {{ $packageSalesAmount }}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
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

            // COUNT
            let countData = [];
            let countXAxis = [];

            $.ajax({
                url: '{{ url('reports/orders-bar-data-by-year?package_id=') . $package->id }}',
                data: {
                    year: $('#input-year').val()
                },
                async: false
            }).done(function(response) {
                $.each(response.count.data, function(key, value) {
                    countData.push([parseInt(key), parseInt(value)]);
                });

                $.each(response.count.xAxis, function(key, value) {
                    countXAxis.push([parseInt(key), value]);
                });
            });

            let barCountData = {
                data : countData,
                bars: { show: true }
            }

            let countPlot = $.plot('#bar-count', [barCountData], {
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
                    ticks: countXAxis
                }
            });

            function getCountBarDataByMonth() {
                let countData = [];
                let countXAxis = [];

                $.ajax({
                    url: '{{ url('reports/orders-bar-data-by-month?package_id=') . $package->id }}',
                    data: {
                        month: $('#input-month').val()
                    },
                    async: false
                }).done(function(response) {
                    $.each(response.count.data, function(key, value) {
                        countData.push([parseInt(key), parseInt(value)]);
                    });

                    countXAxis = response.count.xAxis;
                });

                countPlot.setData([{
                    data : countData,
                    bars: { show: true }
                }]);

                let countTicks = countPlot.getAxes().xaxis.options.ticks;

                countTicks.length = 0;

                $.each(countXAxis, function(key, value) {
                    countTicks.push([parseInt(key), value]);
                });

                countPlot.setupGrid();
                countPlot.draw();
            }

            function getCountBarDataByYear() {
                let countData = [];
                let countXAxis = [];

                $.ajax({
                    url: '{{ url('reports/orders-bar-data-by-year?package_id=') . $package->id }}',
                    data: {
                        year: $('#input-year').val()
                    },
                    async: false
                }).done(function (response) {
                    $.each(response.count.data, function (key, value) {
                        countData.push([parseInt(key), parseInt(value)]);
                    });

                    countXAxis = response.count.xAxis;
                });

                countPlot.setData([{
                    data: countData,
                    bars: {show: true}
                }]);

                let countTicks = countPlot.getAxes().xaxis.options.ticks;

                countTicks.length = 0;

                $.each(countXAxis, function (key, value) {
                    countTicks.push([parseInt(key), value]);
                });

                countPlot.setupGrid();
                countPlot.draw();
            }

            // AMOUNT
            let amountData = [];
            let amountXAxis = [];

            $.ajax({
                url: '{{ url('reports/orders-bar-data-by-year?package_id=') . $package->id }}',
                data: {
                    year: $('#input-year').val()
                },
                async: false
            }).done(function(response) {
                $.each(response.amount.data, function(key, value) {
                    amountData.push([parseInt(key), parseInt(value)]);
                });

                $.each(response.count.xAxis, function(key, value) {
                    amountXAxis.push([parseInt(key), value]);
                });
            });

            let barAmountData = {
                data : amountData,
                bars: { show: true }
            }

            let amountPlot = $.plot('#bar-amount', [barAmountData], {
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
                    ticks: amountXAxis
                }
            });

            function getAmountBarDataByMonth() {
                let amountData = [];
                let amountXAxis = [];

                $.ajax({
                    url: '{{ url('reports/orders-bar-data-by-month?package_id=') . $package->id }}',
                    data: {
                        month: $('#input-month').val()
                    },
                    async: false
                }).done(function(response) {
                    $.each(response.amount.data, function(key, value) {
                        amountData.push([parseInt(key), parseInt(value)]);
                    });

                    amountXAxis = response.amount.xAxis;
                });

                amountPlot.setData([{
                    data : amountData,
                    bars: { show: true }
                }]);

                let amountTicks = amountPlot.getAxes().xaxis.options.ticks;

                amountTicks.length = 0;

                $.each(amountXAxis, function(key, value) {
                    amountTicks.push([parseInt(key), value]);
                });

                amountPlot.setupGrid();
                amountPlot.draw();
            }

            function getAmountBarDataByYear() {
                let amountData = [];
                let amountXAxis = [];

                $.ajax({
                    url: '{{ url('reports/orders-bar-data-by-year?package_id=') . $package->id }}',
                    data: {
                        year: $('#input-year').val()
                    },
                    async: false
                }).done(function (response) {
                    $.each(response.amount.data, function (key, value) {
                        amountData.push([parseInt(key), parseInt(value)]);
                    });

                    amountXAxis = response.amount.xAxis;
                });

                amountPlot.setData([{
                    data: amountData,
                    bars: {show: true}
                }]);

                let amountTicks = amountPlot.getAxes().xaxis.options.ticks;

                amountTicks.length = 0;

                $.each(amountXAxis, function (key, value) {
                    amountTicks.push([parseInt(key), value]);
                });

                amountPlot.setupGrid();
                amountPlot.draw();
            }

            selectDateMode.change(function() {
                if ($(this).val() === 'm') {
                    inputYear.css('display', 'none');
                    inputMonth.css('display', 'block');

                    // COUNT
                    getCountBarDataByMonth();
                    // AMOUNT
                    getAmountBarDataByMonth();
                } else {
                    inputMonth.css('display', 'none');
                    inputYear.css('display', 'block');

                    // COUNT
                    getCountBarDataByYear();
                    // AMOUNT
                    getAmountBarDataByYear();
                }
            });

            inputMonth.change(function() {
                // COUNT
                getCountBarDataByMonth();
                // AMOUNT
                getAmountBarDataByMonth();
            });

            inputYear.change(function() {
                // COUNT
                getCountBarDataByYear();
                // COUNT
                getAmountBarDataByYear();
            });

            /* END BAR CHARTS */
        });
    </script>
@stop
