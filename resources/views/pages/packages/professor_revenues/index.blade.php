@extends('adminlte::page')

@section('title', 'Professor Revenue')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Professor Revenue</h1>
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
                            <button id="button-export" class="btn btn-primary ml-2">Export</button>
                            <button data-toggle="modal" data-target="#exampleModal" class="btn btn-primary ml-2">Import</button>
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
    <form id="form-export" method="POST" action="{{ url('packages/professor/revenues/export') }}">
        @csrf
    </form>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form role="form" id="create" method="POST" action="{{ url('packages/professor-revenue/store') }}" enctype="multipart/form-data">
                @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="custom-file">
                                    <input class="custom-file-input" id="file" name="file" type="file">
                                    <label class="custom-file-label" id="file-label" for="file">Choose file</label>
                                </div>
{{--                                <a href="{{ url('downloads/sample.csv') }}">Sample File</a>--}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}
    <script>
        $(function()
        {
            $('#button-export').click(function() {
                $('#form-export').submit();
            });

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
                alert('okay');
                console.log("clicked");
            });

            $('#datatable').on('mouseover', '.total-professors-count', function (e) {
                e.preventDefault();
                let name = $(this).attr('data-name');
                $(this).attr('title', name);
            });
        });
    </script>
@stop
