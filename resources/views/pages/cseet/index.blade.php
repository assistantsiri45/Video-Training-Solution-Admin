@extends('adminlte::page')

@section('title', 'CSEET Students')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">CSEET Applied Students </h1>
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
    </script>
@stop
