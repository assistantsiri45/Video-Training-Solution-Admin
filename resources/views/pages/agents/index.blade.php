@extends('adminlte::page')

@section('title', 'Associates')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Associates</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('agents.create') }}" type="button" class="btn btn-success">Create</a>
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
@stop
