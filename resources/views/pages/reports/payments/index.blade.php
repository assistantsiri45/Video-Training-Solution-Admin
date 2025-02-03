@extends('adminlte::page')

@section('title', 'Reports - Payments')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Reports - Payments</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $successPaymentCount }}</h3>
                    <p>SUCCESS PAYMENTS</p>
                </div>
                <div class="icon">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>
{{--        <div class="col-md-3">--}}
{{--            <div class="small-box bg-danger">--}}
{{--                <div class="inner">--}}
{{--                    <h3>{{ $failedPaymentCount }}</h3>--}}
{{--                    <p>FAILED PAYMENTS</p>--}}
{{--                </div>--}}
{{--                <div class="icon">--}}
{{--                    <i class="fas fa-credit-card"></i>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>₹{{ $totalSuccessfulAmount }}</h3>
                    <p>SUCCESSFUL TRANSACTION AMOUNT</p>
                </div>
                <div class="icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <input class="form-control" id="search" type="text" placeholder="Search">
                        </div>
                        <div class="col-md-3">
                            <button id="button-search" class="btn btn-primary">Search</button>
                            <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            <button class="btn btn-primary ml-2" id="button-export">Export</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $html->table(['id' => 'table'], true) !!}
                </div>
            </div>
        </div>
    </div>
    <form id="form-export" method="POST" action="{{ url('reports/payments/export') }}">
        @csrf
        <input id="export-search" type="hidden" name="export_search">
    </form>
@stop

@push('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            let table = $('#table');

            table.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val()
                }
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                table.DataTable().draw();
            });

            $('#button-export').click(function() {
                $('#export-search').val($('#search').val());
                $('#form-export').submit();
            });
        });
    </script>
@endpush
