@extends('adminlte::page')

@section('title', 'Third Party Orders')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Third Party Orders</h1>
        </div>
        {{--        <div class="col text-right">--}}
        {{--            <a href="{{ route('third-party-agents.create') }}" type="button" class="btn btn-success">Create</a>--}}
        {{--        </div>--}}
    </div>
@stop
<style>
#datatable_filter{
    display: none;
}
</style>
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <input id="search" type="text" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-3">
                        <input id="name" type="text" class="form-control" placeholder="Name">
                        </div>
                        <div class="col-md-3">
                            <input id="date" type="text" class="form-control" placeholder="Date">
                        </div>
                        <div class="col-md-3">
                            <button id="button-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                            {{--                            <button id="button-export" class="btn btn-primary ml-2">Export</button>--}}
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

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    name:$('#name').val(),
                    date:$('#date').val()
                }
            });

            $('#button-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {
                search: $('#search').val('');
                name:$('#name').val('');
                date:$('#date').val('');
                table.draw();
            });
            
            $('#date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });
    </script>
@stop
