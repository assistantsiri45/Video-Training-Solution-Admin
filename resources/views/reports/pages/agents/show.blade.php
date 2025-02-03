@extends('adminlte::page')

@section('title', $associate->user->name)

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">{{ $associate->user->name }}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-2">
            <div class="card text-white bg-info mb-3">
                <div class="card-header text-center">NO. OF SALES</div>
                <div class="card-body">
                    <h1 class="text-center">{{ $associate->sales_count }}</h1>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-white bg-info mb-3">
                <div class="card-header text-center">TOTAL AMOUNT GAINED</div>
                <div class="card-body">
                    <h1 class="text-center">₹ {{ $associate->commission }}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-widget bg-primary">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-6 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Email</h5>
                                <span>{{ $associate->email }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="description-block">
                                <h5 class="description-header">Phone</h5>
                                <span class="description-text">{{ $associate->phone }}</span>
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
                            <a class="nav-link active" id="tab-students" data-toggle="pill" href="#tab-students-content" role="tab" aria-controls="tab-students-content" aria-selected="true">STUDENTS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-orders" data-toggle="pill" href="#tab-orders-content" role="tab" aria-controls="tab-orders-content" aria-selected="false">ORDERS</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="tab-students-content" role="tabpanel" aria-labelledby="tab-students">
                            {!! $tableStudents->table(['id' => 'table-students'], true) !!}
                        </div>
                        <div class="tab-pane fade" id="tab-orders-content" role="tabpanel" aria-labelledby="tab-orders">
                            <div class="card-header">
                                <input id="checkbox-new-students" type="checkbox">
                                <label for="checkbox-new-students">New Students</label>
                            </div>
                            {!! $tableOrders->table(['id' => 'table-orders'], true) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $tableStudents->scripts() !!}
    {!! $tableOrders->scripts() !!}

    <script>
        $(function() {
            let tableOrders = $('#table-orders').DataTable();

            tableOrders.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    new_students: $('#checkbox-new-students').is(':checked') ? 'true' : 'false'
                }
            });

            $('#checkbox-new-students').change(function() {
                tableOrders.draw();
            });
        });
    </script>
@endsection

