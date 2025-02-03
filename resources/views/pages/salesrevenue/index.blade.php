@extends('adminlte::page')

@section('title', 'Sales-Revenue')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Sales - Revenue</h1>
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
                            <input id="search" type="text" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-2">
                            <input id="date" type="text" class="form-control" placeholder="Date">
                        </div>
                        <div class="col-md-2">
                            <select id="status" class="form-control">
                                <option></option>
                                <option value="1">Success</option>
                                <option value="0">Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="button-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                            <button id="btn-export" class="btn btn-primary ml-2">Export</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    {!! $table->table(['id' => 'table-sales'], true) !!}
                </div>
            </div>
        </div>
    </div>
    <form id="form-export" method="POST" action="{{ url('export-salesrevenue-report') }}">
        @csrf
        <input id="export-search" type="hidden" name="export_search">
        <input id="export-created-at" type="hidden" name="export_created_at">
        <input id="export-status" type="hidden" name="export_status">
    </form>
@stop

@section('js')
    {!! $table->scripts() !!}

    <script>
        $(function() {

            $('.buttons-csv').hide();
            $('.buttons-pdf').hide();

            $('#date').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - '
                },
                autoUpdateInput: false
            }, function (startDate, endDate) {
                $('#date').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
            });

            let table = $('#table-sales').DataTable();

            table.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    date: $('#date').val(),
                    status: $('#status').val()
                }
            });

            $('#button-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {
               $('#search').val('');
               $('#date').val('');
               $('#status').val('').change();
               table.draw();
            });

            $('#btn-export').click(function() {
                $('#export-search').val($('#search').val());
                $('#export-created-at').val($('#date').val());
                $('#export-status').val($('#status').val());
                $('#form-export').submit();
            });

            $('#status').select2({
                placeholder: 'Status'
            });
        });
    </script>
@stop
