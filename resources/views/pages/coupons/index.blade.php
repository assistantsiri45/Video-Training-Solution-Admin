@extends('adminlte::page')

@section('title', 'Coupons')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Coupons</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}
    <script type="text/javascript">
        $(function() {
            //Date picker
            // $('#datepicker').daterangepicker();
            $('.buttons-csv').hide();
            $('.buttons-pdf').hide();
        });
    </script>
@stop
