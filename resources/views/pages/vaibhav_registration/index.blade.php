@extends('adminlte::page')

@section('title', 'Vaibhav Registration')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Vaibhav Registration</h1>
        </div>
        <div class="col text-right">
           
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
                            <input class="form-control" id="search" type="text" placeholder="Search">
                        </div>
                     
                        <div class="col-md-2">
                        <input id="date" type="text" class="form-control" placeholder="Date">
                        </div>
                        <div class="col-md-3">
                            <button id="button-search" class="btn btn-primary">Search</button>
                            <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                        </div>
                    </div>

                    </div>
                <div class="table-responsive" >
                {!! $table->table(['id' => 'datatable'], true) !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $table->scripts() !!}

    <script>
       $(function() {
            $('#date').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - '
                },
                autoUpdateInput: false
            }, function (startDate, endDate) {
                $('#date').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
            });
            let table = $('#datatable');

            table.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    date: $('#date').val(),
                   
                  
                }
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#date').val('');
                
              
                table.DataTable().draw();
            });
            });
    </script>
@stop
