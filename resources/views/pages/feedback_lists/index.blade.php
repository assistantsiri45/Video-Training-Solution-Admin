@extends('adminlte::page')

@section('title', 'Feedback')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Feedback List</h1>
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
                            <input class="form-control" id="search" placeholder="Search" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" id="button-filter" type="button">Filter</button>
                            <button class="btn btn-primary ml-2" id="button-clear" type="button">Clear</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                </div>
                {!! $table->table(['id' => 'table'], true) !!}
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $table->scripts() !!}

    <script>
        $(function  () {
            $('.buttons-html5').remove();

            let table = $('#table').DataTable();

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    search: $('#search').val()
                }
            });

            $('#button-filter').click(function () {
                table.draw();
            });

            $('#button-clear').click(function () {
                $('#search').val('');
                table.draw();
            });

            table.on('click', '.button-status-accepted', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let confirmation = confirm("Do You Want to change status to Accepted?");
                let url = $(this).attr('href');

                if (confirmation) {
                    $.ajax({
                        url: url,
                        method: "POST",
                        success: function(result) {
                            if (result) {
                                toastr.success("Review Status Changed");
                                location.reload();
                            }
                        }
                    });
                }
            });
            table.on('click', '.button-status-rejected', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let confirmation = confirm("Do You Want to change status to Rejected?");
                let url = $(this).attr('href');

                if (confirmation) {
                    $.ajax({
                        url: url,
                        method: "POST",
                        success: function(result) {
                            if (result) {
                                toastr.success("Review Status Changed");
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    </script>
@stop
