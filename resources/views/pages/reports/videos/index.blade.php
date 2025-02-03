@extends('adminlte::page')

@section('title', 'Reports - Videos')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Reports - Videos</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $publishedVideoCount }}</h3>
                    <p>PUBLISHED</p>
                </div>
                <div class="icon">
                    <i class="fas fa-photo-video"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $draftVideoCount }}</h3>
                    <p>DRAFT</p>
                </div>
                <div class="icon">
                    <i class="fas fa-photo-video"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <input class="form-control" id="search" type="text" placeholder="Search">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" id="button-search">Search</button>
                            <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            <button class="btn btn-primary ml-2" id="button-export">Export</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $html->table(['id' => 'table'], true) !!}
                </div>
            </div>
        </div>
    </div>

    <div id="modal-view-packages" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Packages</h3>
                </div>
                <div class="modal-body">
                    <div class="package-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <form id="form-export" method="POST" action="{{ url('reports/videos/export') }}">
        @csrf
        <input id="export-search" type="hidden" name="export_search">
    </form>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            $('.buttons-csv').remove();
            $('.buttons-pdf').remove();

            let table = $('#table');

            table.on('click', '.a-modal-view-package', function () {
                let packages = $(this).data('packages');
                let component = '<ul>';

                packages.forEach(function(package) {
                    component += '<li>' + package + '</li>';
                });

                component += '</ul>';

                $('.package-container').html(component);
            });

            table.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    search: $('#search').val()
                }
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                table.DataTable().draw();
            });

            $('#button-export').click(function() {
                $('#export-search').val($('#search').val());
                $('#form-export').submit();
            });
        });
    </script>
@stop
