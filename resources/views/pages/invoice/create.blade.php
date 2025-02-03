@extends('adminlte::page')

@section('title', 'Invoice Regenerate')

@section('content_header')
    <h1 class="m-0 text-dark">Invoice</h1>
@stop

@section('css')
    <style>
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
        }
        .mail-success {
    color: #000 !important ;
    background: #00FF00 !important;
    border-color: #23923d;
    opacity: 0.6;
}
    </style>
@stop

@section('content')
    <div class="row">
    <div class="col-12">
            <div class="card card-primary">
            
                 
                    <div class="card-body">
                      
                        <div class="row">
                       
                        <div class="col-md-3">
                            <div class="input-group">
                                
                                <!-- <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div> -->
                                <input id="start_date" name="start_date" type="datetime-local" class="form-control float-right" placeholder="Start Date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <!-- <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div> -->
                                <input id="end_date"  name="end_date" type="datetime-local" class="form-control float-right" placeholder="End Date">
                            </div>
                        </div>

                        <!-- <div class="col-sm-3">
                        <div class="input-group">
                                    <select class="form-control" id="package_type" name="transaction_type">
                                    <option value="1">Success</option>
                                <option value="2">Failure</option>
                                    </select>
                                </div>
                            </div> -->

                            <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary" id="btn-filter">Search</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                            </div>

                        
                       
                     
                       
                        </div>
                    </div>
                   
               
                @if(@$html)
                <div class="col text-right">
           
           <button id="btn-invoice" class="btn btn-primary">Generate Invoice</button>
        
       </div>
      
                {!! @$html->table(['id' => 'datatable1']) !!}
                @endif
            </div>
        </div>
    </div>
@stop

@section('js')
@if(@$html)
    {!! @$html->scripts() !!}
    @endif
    <script>
            $(document).ready(function () {
              
                let table = $('#datatable1').DataTable();
               table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val()
                }
            });

            $('#btn-filter').click(function() {
               table.draw();
                        });
                var selected = [];
            if(selected.length<=0){
                $("#btn-invoice").attr('disabled', true);
            }
            else {
                $("#btn-invoice").attr('disabled', false);
            }
              
                $('#log_e_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('#log_s_date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
                $('#create').validate({
                    rules: {
                        start_date: {
                            required: true,
                          
                            //lettersandspace: true
                        },
                        end_date: {
                            required: true
                        }
                    }
                });
                $('#datatable1').on('click', 'thead input[name="select_all"]',function(e){
               
               $("#btn-invoice").attr('disabled', false);
               var $table = $(this).closest('table');

               if(this.checked){
                   $table.find('tbody input[type="checkbox"]:not(:checked)').trigger('click');
               } else {
                   $table.find('tbody input[type="checkbox"]:checked').trigger('click');
               }

               // Prevent click event from propagating to parent
               e.stopPropagation();
           });
                var selected_unpublished_videos = [];
                $('#datatable1').on('click', 'tbody input[type="checkbox"]', function(e){
                var $row = $(this).closest('tr');
                var $table = $(this).closest('table');

                // Get row data
                let table = $table.DataTable();
                var data = table.row($row).data();

                // Get row ID
                var rowId = data.id;

                if ($table.is('#datatable1')) {
                    selected = selected_unpublished_videos;
                } 

                // Determine whether row ID is in the list of selected row IDs
                var index = $.inArray(rowId, selected);

                // If checkbox is checked and row ID is not in list of selected row IDs
                if(this.checked && index === -1) {
                    selected.push(rowId);

                    // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
                } else if (!this.checked && index !== -1){
                    selected.splice(index, 1);
                }
                if(selected.length >=1){
                    $("#btn-invoice").attr('disabled', false);
                }
                else {
                    $("#btn-invoice").attr('disabled', true);
                }
                if(this.checked){
                    $row.addClass('selected');
                } else {
                    $row.removeClass('selected');
                }

                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(table);

                // Prevent click event from propagating to parent
                e.stopPropagation();
                
            });

            $("#btn-invoice").click(function (){
            
             
                let confirm = window.confirm('Are you sure to regenrate the invoice for selected student?');
               
                if (confirm) {
                    $("#btn-invoice").attr('disabled', true);
             
             $("#btn-invoice").html("Processing  <i class='fa fa-spinner fa-spin'>");
            
                    $.ajax({
                        url: '{{ url('invoice/update') }}',
                        type: 'POST',
                        data: {
                            "selected": selected,
                            "transaction_type":1,
                        }
                    }).done(function (response) {
                        toastr.success('Invoice success fully generated');
                       // location.reload();
                      
                       table.draw();
                       $("#btn-invoice").attr('disabled', false);
                        $("#btn-invoice").html("Generate Invoice");
                        selected=[];
                        selected_unpublished_videos=[];
                        
                    });
                }
            });

            $('#btn-clear').click(function() {
                $('#start_date').val('');
                $('#end_date').val('');
                table.draw();
            });

            });
            function updateDataTableSelectAllCtrl(table){
                var $table             = table.table().node();
                var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
                var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
                var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

                // If none of the checkboxes are checked
                if($chkbox_checked.length === 0){
                    chkbox_select_all.checked = false;
                    if('indeterminate' in chkbox_select_all){
                        chkbox_select_all.indeterminate = false;
                    }

                    // If all of the checkboxes are checked
                } else if ($chkbox_checked.length === $chkbox_all.length){
                    chkbox_select_all.checked = true;
                    if('indeterminate' in chkbox_select_all){
                        chkbox_select_all.indeterminate = false;
                    }

                    // If some of the checkboxes are checked
                } else {
                    chkbox_select_all.checked = true;
                    if('indeterminate' in chkbox_select_all){
                        chkbox_select_all.indeterminate = true;
                    }
                }
            }
            
            $('#start_date').change(function(){
                document.getElementById('end_date').min = $('#start_date').val();
            });
             
          

    </script>
@stop
