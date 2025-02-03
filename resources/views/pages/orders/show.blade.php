@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
<style>
    table.dataTable td.dt-control {
    text-align: center;
    cursor: pointer;
}
table.dataTable td.dt-control:before {
    height: 1em;
    width: 1em;
    margin-top: -9px;
    display: inline-block;
    color: white;
    border: 0.15em solid white;
    border-radius: 1em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New",Courier,monospace;
    line-height: 1em;
    content: "+";
    background-color: #31b131;
}
</style>
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
    <script id="details-template" type="text/x-handlebars-template">
        <div id="sss"></div>
    
</script>

    <script>
          function format(d) {
          
          // `d` is the original data object for the row
          return (
              '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;width:100%" >' +
              '<tr>' +
              '<td><b>Full name:</b></td>' +
              '<td>' +
              d.package.name +
              '</td>' +
              '</tr>' +
              '<tr>' +
              '<td><b>Course:</b></td>' +
              '<td>' +
              d.package.course +
              '</td>' +
              '</tr>' +
              '<tr>' +
              '<td><b>Level:</b></td>' +
              '<td>' +
              d.package.level  +
              '</td>'+
              '</tr>' +
              '<tr>' +
              '<td><b>Subject:</b></td>' +
              '<td>' +
              d.package.subject  +
              '</td>'+
              '</tr>' +
              '<tr>' +
              '<td><b>Chapter:</b></td>' +
              '<td>' +
              d.package.chapter  +
              '</td>'+
              '</tr>' +
              '<tr>' +
              '<td><b>Validity:</b></td>' +
              '<td>' +
              d.package.expire_at  +
              '</td>'+
              '</tr>' +
              '<tr>' +
              '<td><b>Language:</b></td>' +
              '<td>' +
              d.package.language  +
              '</td>'+
              '</tr>' +
              '</table>'
          );
      }
        $(function() {
            var table = $('#tbl-orderItems').DataTable();
            $('#tbl-orderItems tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
        var row = table.row(tr);
 
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });


    
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

        //let paymentsTable = $('#tbl-payments').DataTable();
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
