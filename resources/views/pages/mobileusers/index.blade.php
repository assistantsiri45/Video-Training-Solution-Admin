@extends('adminlte::page')

@section('title', 'Mobile Sign-Up Users')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Mobile Sign-Up Users</h1>
        </div>
    </div>
@stop
<style>
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 5% !important;
    }
</style>
@section('content')
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

            $('#btn-clear').click(function() {
                $('#sign-up-date').val('').change();
                table.draw();
            });
        });
    </script>
@stop
