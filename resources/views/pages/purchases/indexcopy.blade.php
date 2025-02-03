@extends('adminlte::page')

@section('title', 'Purchases')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Study Materials</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3 ml-3">
                            <div class="input-group">
                                <label for="text-search"></label>
                                <input class="form-control" id="text-search" type="text" placeholder="Search" />
                                <span class="input-group-append">
                                    <button class="btn btn-primary btn-flat" id="button-search">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $html->table(['id' => 'table-purchases'], true) !!}
                </div>
            </div>
        </div>
    </div>
    <div id="modal-change-status" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="select-status">Status</label>
                                <select class="form-control" id="select-status" style="width: 100%">
                                    <option value=""></option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_PLACED }}">{{ \App\Models\OrderItem::STATUS_ORDER_PLACED_TEXT }}</option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_ACCEPTED }}">{{ \App\Models\OrderItem::STATUS_ORDER_ACCEPTED_TEXT }}</option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_SHIPPED }}">{{ \App\Models\OrderItem::STATUS_ORDER_SHIPPED_TEXT }}</option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_DELIVERED }}">{{ \App\Models\OrderItem::STATUS_ORDER_DELIVERED_TEXT }}</option>
                                </select>
                                <input id="hidden-id" type="hidden">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="button-save" class="btn btn-primary" type="button">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-address" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Address</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row p-2">
                            <div class="col-md-3">
                                Name:
                            </div>
                            <div class="col-md-9">
                                <b><div id="order-name"></div></b>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-3">
                                Phone:
                            </div>
                            <div class="col-md-9">
                                <b><div id="order-phone"></div></b>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-3">
                                Address:
                            </div>
                            <div class="col-md-9">
                                <b><div id="order-address"></div></b>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-3">
                                Area:
                            </div>
                            <div class="col-md-9">
                                <b><div id="order-area"></div></b>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-3">
                                Landmark:
                            </div>
                            <div class="col-md-9">
                                <b><div id="order-landmark"></div></b>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-3">
                                City:
                            </div>
                            <div class="col-md-9">
                                <b><div id="order-city"></div></b>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-3">
                                State:
                            </div>
                            <div class="col-md-9">
                                <b><div id="order-state"></div></b>
                            </div>
                        </div>
                        <div class="row p-2">
                            <div class="col-md-3">
                                Pin:
                            </div>
                            <div class="col-md-9">
                                <b><div id="order-pin"></div></b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="button-save" class="btn btn-primary" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            $('.buttons-html5').remove();

            let table = $('#table-purchases').DataTable();

            table.on('preXhr.dt', function ( e, settings, data ) {
                data.filter = {
                    search: $('#text-search').val()
                }
            });

            $('#button-search').click(function() {
                table.draw();
            });

            $('#select-status').select2({
                placeholder: 'Choose'
            });

            table.on('click', '.a-change-status', function() {
                let id = $(this).data('id');
                let status = $(this).data('status');

                $('#select-status').val(status).change();
                $('#hidden-id').val(id);
            });

            $('#button-save').click(function() {
                $.ajax({
                    url: '{{ url('purchases') }}' + '/' + $('#hidden-id').val(),
                    method: 'PUT',
                    data: {
                        status: $('#select-status').val()
                    }
                }).done(function(response) {
                    $('#modal-change-status').modal('toggle');
                    table.draw();
                })
            });

            table.on('click', '.a-view-address', function() {
                let order = $(this).data('order');
                $('#order-name').text(order.name);
                $('#order-phone').text(order.phone);
                $('#order-address').text(order.address);
                $('#order-area').text(order.area);
                $('#order-landmark').text(order.landmark);
                $('#order-city').text(order.city);
                $('#order-state').text(order.state);
                $('#order-pin').text(order.pin);
            });
        });
    </script>
@stop
