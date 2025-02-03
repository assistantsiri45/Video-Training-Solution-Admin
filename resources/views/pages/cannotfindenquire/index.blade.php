@extends('adminlte::page')

@section('title', 'Enquiry')

@section('content_header')
<div class="row">
    <div class="col">
        <h1 class="m-0 text-dark">Enquiry </h1>
    </div>

</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-2">
        <input id="date" type="text" class="form-control" placeholder="Date">
    </div>
    <div class="col-md-4">
        <input id="search" type="text" class="form-control" placeholder="Search">
    </div>
    <div class="col-md-3">
        <button id="button-filter" class="btn btn-primary">Filter</button>
        <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
        <!-- <button id="button-export" class="btn btn-primary ml-2">Export</button> -->
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            {!! $html->table(['id' => 'datatable'], true) !!}
        </div>
    </div>
</div>
<div id="modal-response" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enquiry</h4>
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
@stop

@section('js')
{!! $html->scripts() !!}
<script>
    $(function() {
        $('#date').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - '
            },
            autoUpdateInput: false
        }, function(startDate, endDate) {
            $('#date').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
        });
        let table = $('#datatable').DataTable();

        table.on('preXhr.dt', function(e, settings, data) {
            data.filter = {

                date: $('#date').val(),
                search: $('#search').val()
            }
        });
        $('#button-filter').click(function() {
            table.draw();
        });
        $('#btn-clear').click(function() {

            $('#date').val('').change();
            $('#search').val('').change();

            table.draw();
        });
        $('#datatable').on('click', '.a-response', function() {
            $('#modal-response').modal('toggle');
            $.ajax({
                url: "{{ url('can-not-find-enquire') }}/" + $(this).data('id'),

            }).done(function(response) {
                $('.modal-response-container').html(response);
            });
        });
    });
</script>
@stop