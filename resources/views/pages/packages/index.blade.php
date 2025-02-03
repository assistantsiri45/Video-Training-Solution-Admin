@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Packages</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="tbl-packages-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-status="unpublished" id="tab-unpublished" data-toggle="pill" href="#">Draft</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-status="published" id="tab-published" data-toggle="pill" href="#">Published</a>
                        </li>
                    </ul>
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" id="search" type="text" placeholder="Search" title="Package name or Subject name">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="type">
                                    <option value=""></option>
                                    <option value="{{ \App\Models\Package::TYPE_CHAPTER_LEVEL }}">{{ \App\Models\Package::TYPE_CHAPTER_LEVEL_VALUE }}</option>
                                    <option value="{{ \App\Models\Package::TYPE_SUBJECT_LEVEL }}">{{ \App\Models\Package::TYPE_SUBJECT_LEVEL_VALUE }}</option>
                                    <option value="{{ \App\Models\Package::TYPE_CUSTOMIZED }}">{{ \App\Models\Package::TYPE_CUSTOMIZED_VALUE }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="language">
                                    <option value=""></option>
                                    @foreach (\App\Models\Language::all() as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" id="button-search">Search</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>
                        </div>
                    </div>
                    {!! $html->table(['id' => 'tbl-packages'], true) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-publish">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Publish</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Type <strong>publish</strong> to continue</p>
                    <div class="form-group">
                        <input type="hidden" id="confirmation-url">
                        <input type="text" class="form-control" id="publish-confirmation" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-publish">Publish</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-un-publish">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Un-Publish</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Type <strong>un-publish</strong> to continue</p>
                    <div class="form-group">
                        <input type="hidden" id="confirmation-url">
                        <input type="text" class="form-control" id="un-publish-confirmation" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-un-publish">Un-Publish</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')

    <script>
        $(function () {
            $("#tbl-packages").on('preXhr.dt', function (e, settings, data) {
                let status = $('#tbl-packages-tab').find('.nav-link.active').first().data('status');
                data.filter = {
                    status: status,
                    search: $('#search').val(),
                    type: $('#type').val(),
                    language: $('#language').val()
                }
            });

        });
    </script>

    {!! $html->scripts() !!}

    <script>
        $(document).ready(function () {
            $("#tbl-packages").on('click', '.btn-delete', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let confirmation = confirm("Delete this item?");
                let url = $(this).attr('href');
                let table = $('#tbl-packages');

                if (confirmation) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        success: function(result) {
                            if (result) {
                                toastr.success(result.message);
                                table.DataTable().draw();
                            }
                        }
                    });
                }
            });

            $("#tbl-packages").on('click', '.btn-publish', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $('#modal-publish').modal('toggle');

                $('.modal-body #confirmation-url').val($(this).attr('href'));
            });

            $('#btn-publish').click(function() {
                let table = $('#tbl-packages');
                let confirmation = $('#publish-confirmation');

                if (confirmation.val() === 'publish') {
                    $.ajax({
                        url: $('#confirmation-url').val(),
                        type: 'POST'
                    }).done(function(response) {
                        if (response) {
                            $('#modal-publish').modal('toggle');
                            confirmation.val('');
                            toastr.success(response.message);
                            table.DataTable().draw();
                        }
                    });
                } else {
                    confirmation.addClass('is-invalid');
                    $('.form-group').append('<small class="text-danger invalid-confirmation">Invalid Confirmation</small>');
                }

                confirmation.keyup(function() {
                    confirmation.removeClass('is-invalid');
                    $('.invalid-confirmation').remove();
                });
            });

            $("#tbl-packages").on('click', '.btn-un-publish', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $('#modal-un-publish').modal('toggle');

                $('.modal-body #confirmation-url').val($(this).attr('href'));
            });

            $("#tbl-packages").on('click', '.btn-un-publish', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $('#modal-un-publish').modal('toggle');

                $('.modal-body #confirmation-url').val($(this).attr('href'));
            });

            $('#btn-un-publish').click(function() {
                let table = $('#tbl-packages');
                let confirmation = $('#un-publish-confirmation');

                if (confirmation.val() === 'un-publish') {
                    $.ajax({
                        url: $('#confirmation-url').val(),
                        type: 'POST'
                    }).done(function(response) {
                        if (response) {
                            $('#modal-un-publish').modal('toggle');
                            confirmation.val('');
                            toastr.success(response.message);
                            table.DataTable().draw();
                        }
                    });
                } else {
                    confirmation.addClass('is-invalid');
                    $('.form-group').append('<small class="text-danger invalid-confirmation">Invalid Confirmation</small>');
                }

                confirmation.keyup(function() {
                    confirmation.removeClass('is-invalid');
                    $('.invalid-confirmation').remove();
                });
            });

            $('#tbl-packages-tab').on('shown.bs.tab', function (e) {
                table.DataTable().draw();
            });

            let table = $('#tbl-packages');
            table.DataTable().draw();

            $('.buttons-csv').remove();
            $('.buttons-pdf').remove();

            $('#type').select2({
                placeholder: 'Type'
            });

            $('#language').select2({
                placeholder: 'Language'
            });


            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#type').val('').change();
                $('#language').val('').change();
                table.DataTable().draw();
            });
        });
    </script>
@stop

