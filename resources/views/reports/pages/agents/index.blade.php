@extends('adminlte::page')

@section('title', 'Agents')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Agents</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input id="purchase-date" type="text" class="form-control float-right" placeholder="Purchase Date">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input id="purchase-count" type="text" class="form-control" placeholder="No. Of Purchase">
                        </div>
                        <div class="col-md-2">
                            <input id="purchase-amount" type="text" class="form-control" placeholder="Purchase Amount">
                        </div>
                        <div class="col-md-1">
                            <button id="btn-filter" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            let table = $('#datatable').DataTable();

            $('#purchase-date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    purchase_date: $('#purchase-date').val(),
                    purchase_count: $('#purchase-count').val(),
                    purchase_amount: $('#purchase-amount').val()
                }
            });

            $('#btn-filter').click(function() {
                table.draw();
            });
        });
    </script>
@stop
