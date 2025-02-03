@extends('adminlte::page')

@section('title', 'Package Study Materials')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Package Study Materials</h1>
        </div>
{{--        <div class="col text-right">--}}
{{--            <a href="{{ route('package-study-materials.create') }}" type="button" class="btn btn-success">Create</a>--}}
{{--        </div>--}}
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <select id="select-package" class="form-control">
                                <option></option>
                                @foreach(\App\Models\Package::all() as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="button-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                            <button id="button-export" class="btn btn-primary ml-2">Export</button>
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
                    package: $('#select-package').val()
                }
            });

            $('#button-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {
                package: $('#select-package').val('')
                table.draw();
            });
        });
        $('#select-package').select2({
            placeholder: 'Package'
        });
    </script>
@stop

