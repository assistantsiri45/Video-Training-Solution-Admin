@extends('adminlte::page')

@section('title', 'Email Support')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Email Support</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
               
                <div class="card-body">
              
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-status="inprogress"   data-toggle="tab" href="#tab-inprogress">In Progress</a></li>
                        <li><a class="nav-link" data-status="complete"   data-toggle="tab" href="#tab-complete">Complete</a></li>
                       
                    </ul>
                    <div class="tab-content">
                        <div id="tab-inprogress" class="tab-pane active">
                            <div class="card-header">
                                
                            </div>

                            <div class="table-responsive">
                            {!! $pending->table(['id' => 'tbl-inprogress'], true) !!} 
                            </div>

                        </div>

                        <div id="tab-complete" class="tab-pane ">
                            <div class="card-header">
                                <div class="card-header">
                                    
                                </div>


                            </div>

                            <div class="table-responsive">
                            {!! $completed->table(['id' => 'tbl-complete'], true) !!} 
                            </div>

                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
   

    {!! $pending->scripts() !!}
    {!! $completed->scripts() !!}
   
   

    <script>
        // $(function () {
        //     let tableAnswered = $('#tbl-inprogress').DataTable();

        //     tableAnswered.on('preXhr.dt', function(e, settings, data) {
        //         data.filter = {
        //             search: $('#search').val()
        //         }
        //     });

        //     $('#form-filter').submit(function(e) {
        //         e.preventDefault();
        //         tableAnswered.draw();
        //     });



        //     let tablePending = $('#tbl-pending-questions').DataTable();

        //     tablePending.on('preXhr.dt', function(e, settings, data) {
        //         data.filter = {
        //             search: $('#search2').val()
        //         }
        //     });

        //     $('#form-filter2').submit(function(e) {
        //         e.preventDefault();
        //         tablePending.draw();
        //     });
        // });
    </script>
@stop
