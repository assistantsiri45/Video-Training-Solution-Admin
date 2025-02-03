@extends('adminlte::page')

@section('title', 'Purchases')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Dispatch</h1>
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
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $orderReceivedCount }}</h3>
                                    <p>Order Received Count (Last 7 Days)</p>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                <h3>{{$orderAcceptedCount}}</h3>
                                    <p>Order Accepted Count(Last 7 Days)</p>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                <h3>{{$orderShippedCount}}</h3>
                                    <p>Order Shipped Count (Last 7 Days)</p>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h3>{{$orderDeliveredCount}}</h3>
                                    <p>Order Delivered Count (Last 7 Days)</p>
                                </div>
                            </div>
                        </div>
                    </div>





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
                        <div class="col-md-2">
                                <select class="form-control" id="order_status">
                                    <option value=""></option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_PLACED }}">{{ \App\Models\OrderItem::STATUS_ORDER_PLACED_TEXT }}</option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_ACCEPTED }}">{{ \App\Models\OrderItem::STATUS_ORDER_ACCEPTED_TEXT }}</option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_SHIPPED }}">{{ \App\Models\OrderItem::STATUS_ORDER_SHIPPED_TEXT }}</option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_DELIVERED }}">{{ \App\Models\OrderItem::STATUS_ORDER_DELIVERED_TEXT }}</option>
                                </select>
                            </div>
                        <div class="col-md-2">
                            <select class="form-control" id="order_type">
                                <option value=""></option>
                                <option value="study-material">Study Material</option>
                                <option value="pendrive">Pendrive</option>
                            </select>
                        </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" id="button-search-order">Search</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>

                    </div>
                </div>
                <div class="card-body">
                <!-- <div id="showentry">rtt</div> -->
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
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="select-status">Status</label>
                                <select class="form-control" id="select-status" >
                                    <option value=""></option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_PLACED }}">{{ \App\Models\OrderItem::STATUS_ORDER_PLACED_TEXT }}</option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_ACCEPTED }}">{{ \App\Models\OrderItem::STATUS_ORDER_ACCEPTED_TEXT }}</option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_SHIPPED }}">{{ \App\Models\OrderItem::STATUS_ORDER_SHIPPED_TEXT }}</option>
                                    <option value="{{ \App\Models\OrderItem::STATUS_ORDER_DELIVERED }}">{{ \App\Models\OrderItem::STATUS_ORDER_DELIVERED_TEXT }}</option>
                                </select>
                                <input id="hidden-id" type="hidden">
                            </div>
                        </div >


                        <div class="col-md-6" >
                            <div class="form-group" id="couriername" style="display: none;">

                            </div>

                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group" id="dispatch_detailshow" style="display: none;">
                                <label for="dispatch_detail">Tracking ID</label>
                                <input type="text" placeholder="Tracking ID 25 characters limit" class="form-control" id="dispatch_detail" >
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
    <div id="modal-show-orderhistory" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Order History</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id='orderhistoryshow'>
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
                // alert ($('#table-purchases_info').html());
                data.filter = {
                    search: $('#text-search').val(),
                    order_status: $('#order_status').val()

                }
            });

            $('#button-search').click(function() {
                table.draw();

            });
            $('#order_type').select2({
                placeholder: 'Choose Type'
            });
            $('#select-status').select2({
                placeholder: 'Choose'
            });

            $('#order_type').select2({
                placeholder: 'Choose Type'
            });

            table.on('click', '.a-change-status', function() {
                let id = $(this).data('id');
                let status = $(this).data('status');

                $('#select-status').val(status).change();
                $('#hidden-id').val(id);


            });

            table.on('click', '.a-change-status', function() {
                let id = $(this).data('id');
                let status = $(this).data('status');

                $('#select-status').val(status).change();
                $('#hidden-id').val(id);


            });



            $('#select-status').on('change',function() {

                let status = $(this).val();

                if(status==3){
                    $('#couriername').show();
                    $('#dispatch_detailshow').show();
                    $('#dispatch_detail').val('');
                    $.ajax({
                    url: '{{ url('courier') }}' ,
                    method: 'GET',

                    }).done(function(items) {

                        var items=items['data'];
                        $('#couriername').html('');
                            var html='';
                            html+='<label for="courier_id">Courier Name</label>';
                            html+='<select class="form-control" id="courier_id" >';
                            html+='<option value="">Select</option>';
                            $.each(items, function (index, item) {
                                if(item['status']==1){
                                    html+='<option value="'+item["id"]+'">'+item["name"]+'</option>';
                                }

                            });
                            html+='</select>';
                        $('#couriername').append(html);
                    })
                }else{
                    $('#couriername').hide();
                    $('#dispatch_detailshow').hide();
                }
            });

            $('#button-save').click(function() {
               var  courier_id=0;
               var  dispatch_detail=0;
                if($('#select-status').val()==3){
                    $('#courier_id').val();
                    if($('#courier_id').val()==''){
                        alert("Please select courier name");
                        return;
                    }

                    if($('#dispatch_detail').val()=='' || $('#dispatch_detail').val().length > 25){
                        alert("Tracking ID is empty or length is greater than 25 characters");
                        return;
                    }
                    var courier_id=$('#courier_id').val();
                    var dispatch_detail=$('#dispatch_detail').val();

                }
                $.ajax({
                    url: '{{ url('purchases') }}' + '/' + $('#hidden-id').val(),
                    method: 'PUT',
                    data: {
                        status: $('#select-status').val(), courier_id:courier_id,dispatch_detail:dispatch_detail
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

            $('#order_status').select2({
                placeholder: 'Order Status'
            });

            $('#button-search-order').click(function() {
                table.draw();
            });

            $('#button-clear').click(function() {

                $('#order_status').val('').change();
                $('#order_type').val('').change();

                table.draw();
            });

            table.on('click', '.a-show-orderhistory', function() {
                let order = $(this).data('order');

                var  i=1;
                var html="<table class='table table-bordered'><th>Sr.No</th><th>Order Status</th><th>Created At</th><th>Updated At</th><th>Changed By</th>";
                $.each(order, function (index, item) {


                    html+='<tr><td>';
                    html+=i;
                    html+='</td>'

                    html+='<td>';
                    switch (item.status) {
                        case 1:
                            html+='Order Received';
                            break;
                        case 2:
                            html+='Order Accepted';
                            break;
                        case 3:
                            html+='Order Shipped';
                            break;
                        case 4:
                            html+='Order Delivered';
                        break;

                        default:
                            break;
                    }

                    html+='</td>'

                    html+='<td>';
                    var d=new Date(item.created_at);
                    html+=d.getDate()+'-'+(d.getMonth()+1)+'-'+d.getFullYear();
                    html+='</td>'


                    html+='<td>';
                    var d=new Date(item.updated_at);
                    html+=d.getDate()+'-'+(d.getMonth()+1)+'-'+d.getFullYear();
                    html+='</td>'


                    html+='<td>';
                    html+=item.user.name;
                    html+='</td>'
                    html+='</tr>';

                    i++;
                });

                html+='</table>';
                $('#orderhistoryshow').html(html);


            });
        });
    </script>
@stop
