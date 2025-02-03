@extends('adminlte::page')

@section('title', 'Rulebook')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Rulebook</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('rule-book.create') }}" type="button" class="btn btn-success">Create</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" >
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" id="search" type="text" placeholder="Search" title="Package name or Subject name">
                            </div>
                            <div class="col-md-2 mt-2">
                                <button class="btn btn-primary" id="button-search">Search</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>
                        </div>
                    </div>
                     <div style="overflow-x: auto">
                         {!! $html->table(['id' => 'datatable'], true) !!}
                     </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            let table = $('#datatable');

            table.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                }
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                table.DataTable().draw();
            });
        });
    </script>
@stop
