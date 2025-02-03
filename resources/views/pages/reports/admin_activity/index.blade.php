@extends('adminlte::page')

@section('title', 'Reports - Admin Activiteis')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Reports - Admin Activities</h1>
        </div>
    </div>
@stop
<style>
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 3% !important;
    }
</style>

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <input class="form-control" id="search" type="text" placeholder="Search">
                        </div>
                        <div class="col-md-2">
                        
                           <select class="form-control" name="role" id="role">
                            <option value="">Role</option>
                            @foreach($role as $key=>$row)
                            <option value="{{$key}}">{{$row}}</option>
                            @endforeach

                           </select>
                        </div>
                        <div class="col-md-2">
                        <input id="date" type="text" class="form-control" placeholder="Date">
                        </div>
                       
                    </div>
                    <br>
                    
                <div class="row">
                        <div class="col-md-3">
                            <button id="button-search" class="btn btn-primary">Search</button>
                            <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                        </div>
</div>
                    </div>
                
                <div class="table-responsive">
                    {!! $table->table(['id' => 'analytics-table'], true) !!}
                </div>
            </div>
        </div>
    </div>
    <div id="modal-response" class="modal fade" role="dialog">
        <div class="modal-dialog" style="max-width: 75%;"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Server Variables</h4>
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
    <div id="modal-response-details" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width:80%;"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Logs</h4>
                </div>
                <div class="modal-body">
                    <pre class="modal-response-details"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
   
@stop

@push('js')
    {!! $table->scripts() !!}

    <script>
        $(function() {
            $('#date').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - '
                },
                autoUpdateInput: false
            }, function (startDate, endDate) {
                $('#date').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
            });
            let table = $('#analytics-table');

            table.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    date: $('#date').val(),
                    role: $('#role').val()
                  
                }
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#date').val('');
                $('#role').val('');
              
                table.DataTable().draw();
            });

            // $('#log_s_date').datepicker({
            //     // format: 'dd-mm-yyyy',
            //     // autoclose: true 
            // });

            // $('#log_e_date').datepicker({
            //     // format: 'dd-mm-yyyy',
            //     // greaterThan: "#log_s_date" ,
            //     // autoclose: true
            // });

            $('#analytics-table').on('click', '.a-response', function() {
            $('#modal-response').modal('toggle');

            $.ajax({
                url: '{{ url('get-server_var') }}',
                data: {
                    id: $(this).data('id')
                }
            }).done(function(response) {
                $('.modal-response-container').html(response);
            });
        });
        $('#analytics-table').on('click', '.updated_value', function() {
            $('.modal-response-details').html('');
             $('#modal-response-details').modal('toggle');

            $.ajax({
                url: '{{ url('get-edit-log') }}',
                data: {
                    id: $(this).data('id')
                }
            }).done(function(response) {
             
                 $('.modal-response-details').html(response);
            });
        });
        });
    </script>
     
@endpush
