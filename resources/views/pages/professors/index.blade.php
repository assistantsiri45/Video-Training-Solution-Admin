@extends('adminlte::page')

@section('title', 'Professors')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Professors</h1>
        </div>
        <div class="col text-right">
            @if(\Auth::user()->role!=9)
            <a href="{{ route('professors.create') }}" type="button" class="btn btn-success">Create</a>
            @endif
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
