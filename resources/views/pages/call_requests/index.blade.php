@extends('adminlte::page')

@section('title', 'Call Requests')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Call Requests</h1>
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
                        <input id="date" type="text" class="form-control" placeholder="Date">
                        </div>
                        <div class="col-md-2">
                        <select id="status" class="form-control">
                            <option></option>
                            <option value=""></option>
                            <option value="1">New</option>
                            <option value="2">Updated</option>
                        </select>
                        </div>
                        <div class="col-md-3">
                            <input id="search" type="text" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-1">
                            <button id="button-filter" class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-1">
                            <button id="btn-clear" class="btn btn-primary">Clear</button>
                        </div>
                        <div class="col-md-1">
                            <button id="btn-export" class="btn btn-primary">Export</button>
                        </div>

                    </div>
                </div>
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
    <form id="form-export" method="POST" action="{{ url('export-call-requests') }}">
        @csrf
        <input id="export-status" type="hidden" name="export_status">
        <input id="export-search" type="hidden" name="export_search">
        <input id="export-created-at" type="hidden" name="export_created_at">
    </form>
@stop

@section('js')
    {!! $html->scripts() !!}
    <script type="text/javascript">
        $(function() {
            //Date picker
            // $('#datepicker').daterangepicker();
           $('.buttons-csv').hide();
            $('.buttons-pdf').hide();
            $('#date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('#status').select2({
                placeholder: 'Course'
            });
            let table = $('#datatable').DataTable();
            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    date: $('#date').val(),
                    status: $('#status').val(),
                    search: $('#search').val()
                }
            });
            $('#button-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {
                search: $('#search').val('');
                date: $('#date').val('');
                status: $('#status').val('').trigger('change');
                table.draw();
            });

            $('#btn-export').click(function() {
                $('#export-status').val($('#status').val());
                $('#export-search').val($('#search').val());
                $('#export-created-at').val($('#date').val());
                $('#form-export').submit();
            });
        });
    </script>
@stop
