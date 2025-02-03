@extends('adminlte::page')

@section('title', 'Blog Tags')

@section('content_header')
    <div class="row mt-2">
        <div class="col">
            <h1 class="m-0 text-dark">Blog Tags</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('blogs.tags.create') }}" type="button" class="btn btn-success">Create</a>
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
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $html->table(['id' => 'tableBlogTags'], true) !!}
                </div>

            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function  () {
            $('.buttons-html5').remove();

            $('#tableBlogTags').on('click', '.button-delete', function (e) {
                e.preventDefault();
                let confirm = window.confirm('Delete Blog?');
                let url = $(this).attr('href');

                if (confirm) {
                    $.ajax({
                        url: url,
                        type: 'DELETE'
                    }).done(function (response) {
                        toastr.success('Successfully deleted');
                        $('#tableBlogTags').DataTable().draw();
                    });
                }
            });

            let table = $('#tableBlogTags').DataTable();

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    search: $('#search').val()
                }
            });

            $('#button-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {

                $('#search').val('');
                table.draw();
            });


        });
    </script>
@stop
