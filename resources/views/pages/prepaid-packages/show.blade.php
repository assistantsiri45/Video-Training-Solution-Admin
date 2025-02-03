@extends('adminlte::page')

@section('title', $student->name)

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">{{ $student->name }}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-widget bg-primary">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Email</h5>
                                <span>{{ $student->email }}</span>
                            </div>
                        </div>
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Phone</h5>
                                <span class="description-text">{{ $student->phone }}</span>
                            </div>
                        </div>
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Course</h5>
                                <span class="description-text"> @if($student->course){{ $student->course->name }}@else - @endif</span>
                            </div>
                        </div>
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Level</h5>
                                <span class="description-text"> @if($student->level){{ $student->level->name }}@else - @endif</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">Address</h5>
                                @if ($student->address)
                                    <span>{{ $student->address }}</span><br>
                                @endif
                                <span>{{ $student->city ? $student->city . ', ' : '' }}{{ $student->state ? $student->state->name . ', ' : '' }}{{ $student->country ? $student->country->name . ' - ' : '' }}{{ $student->pin ? $student->pin : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs">

                        <li class="nav-item">
                            <a class="nav-link active" id="tab-packages" data-toggle="pill" href="#tab-packages-content" role="tab" aria-controls="tab-packages-content" aria-selected="false">PACKAGES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="tab-orders" data-toggle="pill" href="#tab-orders-content" role="tab" aria-controls="tab-orders-content" aria-selected="true">TRANSACTIONS</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade" id="tab-orders-content" role="tabpanel" aria-labelledby="tab-orders">
{{--                            <div class="row">--}}
{{--                                <div class="col-12">--}}
{{--                                    <div class="card">--}}
{{--                                        <div class="card-header">--}}
{{--                                            <h4>Ordered Items</h4>--}}
{{--                                        </div>--}}
{{--                                        <div class="card-body">--}}
{{--                                            <div class="table-responsive">--}}
{{--                                                {!! $tableOrderItems->table(['id' => 'tbl-studentOrderItems'], true) !!}--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Payments</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                {!! $tablePayments->table(['id' => 'tbl-studentPayments'], true) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade active show" id="tab-packages-content" role="tabpanel" aria-labelledby="tab-cart-items">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input id="search" type="text" class="form-control" placeholder="Search">
                                    </div>
                                    <div class="col-md-1">
                                        <button id="btn-filter" class="btn btn-primary">Filter</button>
                                    </div>
                                    <div class="col-md-1">
                                        <button id="btn-clear" class="btn btn-primary">Clear</button>
                                    </div>
                                </div>
                            </div>
                            {!! $tablePackages->table(['id' => 'table-packages'], true) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-assign">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Assign Package</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p  id="package_assigned_message" hidden><strong>Package Already Assigned ! </strong></p>
                    <p>Type <strong>assign</strong> to continue</p>
                    <div class="form-group">
                        <input type="hidden" id="confirmation-url">
                        <input type="text" class="form-control" id="assign-confirmation" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <input type="hidden"  name="package_id" id="package_id">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-assign">Assign</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-response" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">RESPONSE</h4>
                </div>
                <div class="modal-body">
                    <pre class="modal-response-container"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-no-response" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ordered Items</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Package Name</th>
                            <th scope="col">Price</th>
                        </tr>
                        </thead>
                        <tbody id="order-items">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default assign-courses" data-dismiss="modal">ASSIGN</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
                </div>
            </div>

        </div>
    </div>
@stop

@section('js')
    {!! $tableOrderItems->scripts() !!}
    {!! $tablePayments->scripts() !!}
    {!! $tablePackages->scripts() !!}

    <script>
        $(function() {
            $('.buttons-csv').hide();
            $('.buttons-pdf').hide();

            $('#table-packages').on('click', '.a-assign', function() {
                $('#modal-assign').modal('toggle');
                $('#package_id').val($(this).data('package-id'));
                $('#btn-assign').attr('data-package-id', $(this).data('package-id'));
                $.ajax({
                    url: '{{ url('check-if-package-assigned') }}',
                    type: 'POST',
                    data: {
                        package_id: $(this).data('package-id'),
                        user_id: '{{ $student->user_id }}'
                    }
                }).done(function(response) {
                    if (response==1) {
                        $('#package_assigned_message').attr('hidden',false);
                    }else{
                        $('#package_assigned_message').attr('hidden',true);
                    }
                });
            });


            let table_orders = $('#tbl-studentOrderItems');
            let table_payments = $('#tbl-studentPayments');

            let table = $('#table-packages');
            $('#tbl-studentPayments').on('click', '.no-response', function() {
                $.ajax({
                    url: '{{ url('fetch-order-items') }}',
                    data: {
                        id: $(this).data('id')
                    }
                }).done(function(items) {
                    $.each(items, function (index, item) {
                        var table_row = $('<tr>'+
                            // '<th scope="row">'+index+1+'</th>'+
                            '<td>'+item["package"]["name"] +'</td>'+
                            '<td>'+item["price"]+'</td>'+
                            '</tr>');
                        $("#order-items").empty().append(table_row);
                    });

                    $('#modal-no-response').modal('toggle');
                });
            });

            $( ".assign-courses" ).click(function() {
                $.ajax({
                    url: '{{ url('assign-packages') }}',
                    method: "post",
                    data: {
                        id: $('.no-response').data('id')
                    }
                }).done(function(response) {
                    table.draw();
                    toastr.options = {
                        "preventDuplicates": true,
                        "preventOpenDuplicates": true
                    };
                    toastr.success("Package assigned successfully");
                });
            });

            $('#tbl-studentPayments').on('click', '.a-response', function() {
                $('#modal-response').modal('toggle');

                $.ajax({
                    url: '{{ url('get-payment-response') }}',
                    data: {
                        id: $(this).data('id')
                    }
                }).done(function(response) {
                    $('.modal-response-container').html(response);
                });
            });

            $('#btn-assign').click(function() {
                let confirmation = $('#assign-confirmation');

//                alert($(this).data('package-id'));
                if (confirmation.val() === 'assign') {
                    $('#btn-assign').prop("disabled",true);
                    $.ajax({
                        url: '{{ route('prepaid-packages.store') }}',
                        type: 'POST',
                        data: {
                            package_id: $('#package_id').val(),
                            user_id: '{{ $student->user_id }}'
                        }
                    }).done(function(response) {
                        if (response) {
                            $('#btn-assign').prop("disabled",false);

                            confirmation.val('');
                            $('#package_id').val('');
                            $('#modal-assign').modal('toggle');
                            toastr.success(response);
                            table.DataTable().draw();
                            table_orders.DataTable().draw();
                            table_payments.DataTable().draw();
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

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    search: $('#search').val()
                }
            });
            $('#btn-filter').click(function() {
                table.DataTable().draw();
            });
            $('#btn-clear').click(function() {
                search: $('#search').val('');
                table.DataTable().draw();
            });
        });
    </script>
@stop

