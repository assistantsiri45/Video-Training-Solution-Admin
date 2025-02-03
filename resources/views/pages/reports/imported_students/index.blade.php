@extends('adminlte::page')

@section('title', 'Imported Students')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Imported Students</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <input class="form-control" id="search" name="search" placeholder="Search">
                        </div>
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
                            <button id="button-export" class="btn btn-primary ml-2">Export</button>
                        </div>
                    </div>
                </div>
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
    <form id="form-export" method="POST" action="{{ url('reports/imported-students/export') }}">
        @csrf
        <input id="export-sign-up-date" type="hidden" name="export_sign_up_date">
        <input id="export-search" type="hidden" name="export_search">
    </form>


    <div class="modal fade" id="modalPackages" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="package-container">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            $('#datatable').on('click', '.a-show-packages', function () {
                let userID = $(this).data('user-id');

                $.ajax({
                    url: '{{ url('api/student-packages') }}',
                    data: {
                        'user_id': userID
                    }
                }).done(function (response) {
                    $('.package-container').html('');
                    response.forEach(function (res) {
                        $('.package-container').append(`<ul><li>${res.name} (Expire At: ${res.expire_at})</li></ul>`);
                    });

                    $('#modalPackages').modal('toggle');
                });
            });

            let table = $('#datatable').DataTable();

            $('#sign-up-date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    sign_up_date: $('#sign-up-date').val()
                }
            });

            $('#btn-filter').click(function() {
                table.draw();
            });

            $('#btn-clear').click(function() {
                $('#search').val('');
                $('#sign-up-date').val('');
                table.draw();
            });
            $('#button-export').click(function() {
                $('#export-sign-up-date').val($('#sign-up-date').val());
                $('#export-search').val($('#search').val());
                $('#form-export').submit();
            });
        });
    </script>
@stop
