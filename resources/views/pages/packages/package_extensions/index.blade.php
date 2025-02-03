@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Package Extensions</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" id="order_id" type="text" placeholder="Order ID" title="Please enter order ID">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" id="receipt_no" type="text" placeholder="Receipt No." title="Please enter receipt no.">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" id="dop" type="date"  title="Please enter date of payment">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" id="student_name" type="text" placeholder="Student Name"  title="Please enter student name">
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

@stop

@section('js')

    <script>
        $(function () {
            $("#tbl-packages").on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    order_id: $('#order_id').val(),
                    receipt_no: $('#receipt_no').val(),
                    dop: $('#dop').val(),
                    student_name: $('#student_name').val(),
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
            let table = $('#tbl-packages');
            table.DataTable().draw();

            $('.buttons-csv').remove();
            $('.buttons-pdf').remove();



            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#order_id').val('');
                $('#receipt_no').val('');
                $('#dop').val('');
                $('#student_name').val('');

                table.DataTable().draw();
            });
        });
    </script>
@stop

