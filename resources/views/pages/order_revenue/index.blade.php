@extends('adminlte::page')

@section('title', 'Orders-Revenue')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Orders - Revenue</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
        </div>
    </div>

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
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        {{--<div class="col-md-2">--}}
                            {{--<input id="date" type="text" class="form-control" placeholder="Date">--}}
                        {{--</div>--}}
                        {{--<div class="col-md-2">--}}
                            {{--<select id="course" class="form-control">--}}
                                {{--<option></option>--}}
                                {{--@foreach (\App\Models\Course::all() as $course)--}}
                                    {{--<option value="{{ $course->id }}">{{ $course->name }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-2">--}}
                            {{--<select id="package" class="form-control">--}}
                                {{--<option></option>--}}
                                {{--@foreach (\App\Models\Package::all() as $package)--}}
                                    {{--<option value="{{ $package->id }}">{{ $package->name }}</option>--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-2">--}}
                            {{--<input id="location" type="text" class="form-control" placeholder="Location">--}}
                        {{--</div>--}}
                        {{--<div class="col-md-1">--}}
                            {{--<input id="order-id" type="text" class="form-control" placeholder="Order ID">--}}
                        {{--</div>--}}
                        {{--<div class="col-md-1">--}}
                            {{--<input id="amount" type="text" class="form-control" placeholder="Amount">--}}
                        {{--</div>--}}
                        {{--<div class="col-md-1">--}}
                            {{--<select id="repeat" class="form-control">--}}
                                {{--<option></option>--}}
                                {{--@for ($i = 1; $i <= 10; $i++)--}}
                                    {{--<option value="{{ $i }}">{{ $i }}</option>--}}
                                {{--@endfor--}}
                            {{--</select>--}}
                        {{--</div>--}}
                        <div class="col-md-4">
                            <input id="search" type="text" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-3">
                            <button id="button-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                            <button id="button-export" class="btn btn-primary ml-2">Export</button>
                        </div>
                        {{--<div class="col-md-2">--}}
                            {{--<button id="btn-clear" class="btn btn-primary" data-toggle="modal" data-target="#updateOrderModal">Update Order</button>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <div class="table-responsive">
                    {!! $html->table(['id' => 'datatable'], true) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="updateOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form id="create" method="POST" action="{{ url('update-order') }}">
            @csrf
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="form" value="signup">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="order_id">Order ID</label>
                                        <input type="number"  class="form-control{{ $errors->has('order_id') ? ' is-invalid' : '' }}" required id="order_id" name="order_id" value="{{ old('order_id') }}" placeholder="Order ID">
                                        @if ($errors->has('order_id'))
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('order_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
        </form>
    </div>

    <div id="modal-response" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">RESPONSE</h4>
                </div>
                <div class="modal-body">
                    <pre class="modal-response-container"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div id="modal-no-response" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ordered Items</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
{{--                            <th scope="col">#</th>--}}
                            <th scope="col">Package Name</th>
                            <th scope="col">Price</th>
                        </tr>
                        </thead>
                        <tbody id="order-items">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default assign-courses" data-dismiss="modal">ASSIGN</button>
                </div>
            </div>

        </div>
    </div>
    <form id="form-export" method="POST" action="{{ url('order-revenue/export') }}">
        @csrf
        <input id="export-search" type="hidden" name="export_search">
    </form>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            $('#date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('#course').select2({
                placeholder: 'Course'
            });

            $('#package').select2({
                placeholder: 'Package'
            });

            $('#repeat').select2({
                placeholder: 'Repeat'
            });

            let table = $('#datatable').DataTable();

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
//                    date: $('#date').val(),
//                    course: $('#course').val(),
//                    package: $('#package').val(),
//                    location: $('#location').val(),
//                    order_id: $('#order-id').val(),
//                    amount: $('#amount').val(),
//                    repeat: $('#repeat').val(),
                    search: $('#search').val()
                }
            });

            $('#button-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {
                search: $('#search').val('');
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

            // COUNT
            let countData = [];
            let countXAxis = [];

            $.ajax({
                url: '{{ url('reports/orders-bar-data-by-year') }}',
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
                    url: '{{ url('reports/orders-bar-data-by-month') }}',
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
                    url: '{{ url('reports/orders-bar-data-by-year') }}',
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
                url: '{{ url('reports/orders-bar-data-by-year') }}',
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
                    url: '{{ url('reports/orders-bar-data-by-month') }}',
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
                    url: '{{ url('reports/orders-bar-data-by-year') }}',
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

            $('#datatable').on('click', '.a-response', function() {
                $('#modal-response').modal('toggle');

                $.ajax({
                    url: '{{ url('get-order-response') }}',
                    data: {
                        id: $(this).data('id')
                    }
                }).done(function(response) {
                    $('.modal-response-container').html(response);
                });
            });

            $('#datatable').on('click', '.no-response', function() {
                $.ajax({
                    url: '{{ url('fetch-order-items') }}',
                    data: {
                        id: $(this).data('id')
                    }
                }).done(function(items) {
                    $.each(items, function (index, item) {
                        var table_row = $('<tr>'+
                            // '<th scope="row">'+index+1+'</th>'+
                            '<td>'+item["package"]["name"] +'</td>'+
                            '<td>'+item["price"]+'</td>'+
                        '</tr>');
                        $("#order-items").empty().append(table_row);
                    });

                    $('#modal-no-response').modal('toggle');
                });


            });


            $( ".assign-courses" ).click(function() {
                $.ajax({
                    url: '{{ url('assign-packages') }}',
                    method: "post",
                    data: {
                        id: $('.no-response').data('id')
                    }
                }).done(function(response) {
                    table.draw();
                    toastr.options = {
                        "preventDuplicates": true,
                        "preventOpenDuplicates": true
                    };
                    toastr.success("Package assigned successfully");
                });
            });

            $('#button-export').click(function() {
                $('#export-search').val($('#search').val());
                $('#form-export').submit();
            });
        });
    </script>
@stop
