@extends('adminlte::page')

@section('title', 'Professor Payouts')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Reports - Professor Payouts</h1>
        </div>
    </div>
@stop

@section('content')
    <form role="form" id="filter" >
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-2">
                                    <input id="date" type="text" class="form-control" placeholder="Date">
                                </div>
                                <div class="col-md-2">
                                    <select id="professor" class="form-control">
                                        <option></option>
                                        @foreach (\App\Models\Professor::get() as $professor)
                                            <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="package" class="form-control">
                                        <option></option>
                                        @foreach (\App\Models\Package::where('is_approved',1)->get() as $package)
                                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <input id="order-id" type="text" class="form-control" placeholder="Order ID">
                                </div>
                                <div class="col-md-1">
                                    <input id="amount" type="text" class="form-control" placeholder="Amount">
                                </div>
                                <div class="col-md-1">
                                    <button id="button-filter" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </div>
                        </div>
                        {!! $html->table(['id' => 'professor-payout-table'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')

    {!! $html->scripts() !!}

    <script type="text/javascript">
        $(function () {
            $('#date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('#professor').select2({
                placeholder: 'Professor'
            });

            $('#package').select2({
                placeholder: 'Package'
            });

            $("#select_total_sales").select2({ });
            $("#select_total_sale_amount").select2({ });

            $('#filter').submit(function(e) {
                e.preventDefault();
                // To filter the datatable
                var $table = $('#professor-payout-table');
                $table.on('preXhr.dt', function ( e, settings, data ) {
                    data.filter = {
                        date: $('#date').val(),
                        professor_id: $('#professor').find(":selected").val(),
                        package_id: $('#package').find(":selected").val(),
                        order_id: $('#order-id').val(),
                        amount: $('#amount').val()
                    };
                });
                $table.DataTable().draw();
            });
        });
    </script>
@stop

