@extends('adminlte::page')

@section('title', 'Professors Revenue')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Professors Revenue</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('professor-revenues.create') }}" type="button" class="btn btn-success">Create</a>
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
                            <select class="form-control" id="package">
                                <option value=""></option>
                                @foreach (\App\Models\Package::all() as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="professor">
                                <option value=""></option>
                                @foreach (\App\Models\Professor::all() as $professor)
                                    <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input class="form-control" id="search-by-date" type="date" placeholder="Search">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" id="button-search">Search</button>
                            <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
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
            let table = $('#datatable');

            table.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    package: $('#package').val(),
                    professor: $('#professor').val(),
                    date:$('#search-by-date').val(),
                }
            });

            $('#professor').select2({
                placeholder: 'Professor'
            });

            $('#package').select2({
                placeholder: 'package'
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#package').val('').change();
                $('#professor').val('').change();
                table.DataTable().draw();
            });
        });
    </script>
@stop
