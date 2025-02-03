@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
    <div class="row">
        <div class="col">
        <a class="nav-link" href="{{ route('orders.index') }}">{{ __('Total Orders') }}</a>
       
            <h1 class="m-0 text-dark">Order Details  - #{{str_pad($orders->id, 6, "0", STR_PAD_LEFT)}}</h1>
        </div>
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Ordered Items</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $tableOrderItems->table(['id' => 'tbl-orderItems'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Payments</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        {!! $tablePayments->table(['id' => 'tbl-payments'], true) !!}
                    </div>
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
                    <button type="button" class="btn btn-default assign-courses" >ASSIGN</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
                </div>
            </div>

        </div>
    </div>
@stop

@section('js')
    {!! $tableOrderItems->scripts() !!}
    {!! $tablePayments->scripts() !!}

    <script>
        $(function() {
            $('#tbl-payments').on('click', '.no-response', function() {
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
        });

        $('#tbl-payments').on('click', '.a-response', function() {
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

        let paymentsTable = $('#tbl-payments').DataTable();
        $( ".assign-courses" ).click(function() {
            $.ajax({
                url: '{{ url('assign-packages') }}',
                method: "post",
                data: {
                    id: $('.no-response').data('id')
                }
            }).done(function(response) {
                if(response){
                    $('#modal-no-response').modal('hide');
                    paymentsTable.draw();
                    toastr.options = {
                        "preventDuplicates": true,
                        "preventOpenDuplicates": true
                    };
                    toastr.success("Payment status updated successfully");
                }
            });
        });

        </script>
@stop
