@extends('adminlte::page')

@section('title', 'Sales')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Sales</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <input id="search" type="text" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-2">
                            <input id="date" type="text" class="form-control" placeholder="Date">
                        </div>
                        <div class="col-md-2">
                            <select id="status" class="form-control">
                                <option></option>
                                <option value="1">Success</option>
                                <option value="0">Failed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="button-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                            <button id="btn-export" class="btn btn-primary ml-2">Export</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    {!! $table->table(['id' => 'table-sales'], true) !!}
                </div>
            </div>
        </div>
    </div>
    <div id="modal-row-details" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" id="m-header">
                    <!-- <h4 class="modal-title"></h4> -->
                </div>
                <div class="modal-body">
                <table class="table td-order-detail">
                        <thead>
                        <tr>
                            <th><b>Package</b></th>
                            <th><b>Course</b></th>
                            <th><b>Level</b></th>
                            <th><b>Package type</b></th>
                            <th><b>Subject</b></th>
                            <th><b>Chapter</b></th>
                            <th><b>Language</b></th>
                            <th><b>Professors</b></th>
                            <th><b>Mode of Lecture</b></th>
                            <th><b>Package Duration</b></th>
                            <th><b>Package Validity</b></th>
                            <th><b>Expire At</b></th>
                            <th><b>Study Material</b></th>
                            <th><b>Study Material Fees</b></th>
                            <th><b>Pen Drive</b></th>
                            <th><b>Pen Drive Fees</b></th>
                            <th><b>Gross Amount </b></th>
                            <th><b>Discount</b></th>
                            <th><b>J-Koins</b></th>
                            <th><b>Coupons</b></th>
                            <th><b>Net Fees</b></th>
                            <th><b>Address</b></th>
                            <!-- <th><b>Transaction Id</b></th> -->
                            <th><b>Invoice Number</b></th>
                            <th><b>Date & Time</b></th>
                        </tr>
                        </thead>
                        <tbody id="order-detail-items">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

    <form id="form-export" method="POST" action="{{ url('export-sales-report') }}">
        @csrf
        <input id="export-search" type="hidden" name="export_search">
        <input id="export-created-at" type="hidden" name="export_created_at">
        <input id="export-status" type="hidden" name="export_status">
    </form>
@stop

@section('js')
    {!! $table->scripts() !!}

    <script>
        $(function() {

            $('.buttons-csv').hide();
            $('.buttons-pdf').hide();

            $('#date').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - '
                },
                dateLimit: {
                    'months': 2,
                    'days': -1
                 },
                autoUpdateInput: false
            }, function (startDate, endDate) {
                $('#date').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
            });

            let table = $('#table-sales').DataTable();

            table.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    date: $('#date').val(),
                    status: $('#status').val()
                }
            });

            $('#button-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {
               $('#search').val('');
               $('#date').val('');
               $('#status').val('').change();
               table.draw();
            });

            $('#btn-export').click(function() {
                $('#export-search').val($('#search').val());
                $('#export-created-at').val($('#date').val());
                $('#export-status').val($('#status').val());
                $('#form-export').submit();
            });

            $('#status').select2({
                placeholder: 'Status'
            });
        });
        $(function(){

            $('#table-sales').on('click', '.a-response', function() {
                $('#modal-response').modal('toggle');

                $.ajax({
                    url: '{{ url('get-order-response') }}',
                    data: {
                        id: $(this).data('id')
                    }
                }).done(function(response) {
                    $('.modal-response-container').html(response);
                });
            });

$('#table-sales').on('click', '.a-row-details', function() {

    $.ajax({
            url: '{{ url('fetch-sales-details') }}',
            data: {
                id: $(this).data('id')
            }
        }).done(function(items) {
           
            var table_row = $('<tr>'+
                '<td>'+items['package'] +'</td>'+
                '<td>'+items['course'] +'</td>'+
                '<td>'+items['level'] +'</td>'+
                '<td>'+items['packagetype'] +'</td>'+
                '<td>'+items['subject'] +'</td>'+
                '<td>'+items['chapter'] +'</td>'+
                '<td>'+items['language'] +'</td>'+
                '<td>'+items['professors']+'</td>'+
                '<td>'+items['mode_of_lecture']+'</td>'+
                '<td>'+items['package_duration']+'</td>'+
                '<td>'+items['package_validity']+'</td>'+
                '<td>'+items['expiry_date']+'</td>'+
                '<td>'+items['study_material']+'</td>'+
                '<td>'+items['study_material_price']+'</td>'+
                '<td>'+items['is_pendrive']+'</td>'+
                '<td>'+items['pendrive_price']+'</td>'+
                '<td>'+items['gross_amount']+'</td>'+
                '<td>'+items['holiday_offer_amount']+'</td>'+
                '<td>'+items['reward_amount']+'</td>'+
                '<td>'+items['coupon_amount']+'</td>'+
                '<td>'+items['net_amount']+'</td>'+
                '<td>'+items['address']+'</td>'+
               // '<td>'+items['transaction_id']+'</td>'+
                '<td>'+items['invoice_no']+'</td>'+
                '<td>'+items['created_at']+'</td>'+
                '</tr>'
                );
            var modal_head = $('<h4 class="modal-title">Order Details - #'+items['order_id']+'</h4>');
                $("#order-detail-items").empty().append(table_row);
                $("#m-header").empty().append(modal_head);
           

            $('#modal-row-details').modal('toggle');
        });
});

});
    </script>
@stop
