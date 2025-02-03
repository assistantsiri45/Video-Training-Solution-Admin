@extends('adminlte::page')

@section('title', 'Spin Wheel Campaigns')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Spin Wheel Campaigns</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('spin-wheel-campaigns.create') }}" type="button" class="btn btn-success">Create</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <input id="search" type="text" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-3">
                            <button id="button-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
{{--                            <button id="button-export" class="btn btn-primary ml-2">Export</button>--}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $html->table(['id' => 'datatable'], true) !!}
                </div>
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
            $('#web-url').click(function (){
                // alert('okay');
                console.log("clicked");
            });
        });
    </script>
@stop
