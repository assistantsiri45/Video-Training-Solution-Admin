@extends('adminlte::page')

@section('title', 'Create Courier')

@section('content_header')
    <h1 class="m-0 text-dark">Create Courier</h1>
@stop

@section('css')
    <style>
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('couriers.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Courier Name</label>
                                <input type="text" name="name" class="form-control" id="name" @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Courier Name">
                                @error('name')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="url">Courier URL</label>
                                <input type="text" name="url" class="form-control" id="url" @error('url') is-invalid @enderror" value="{{ old('url') }}" placeholder="Courier URL">
                                @error('url')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('url') }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#create').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 80,
                        //lettersandspace: true
                    },
                    url: {
                        required: true,
                        maxlength: 200
                    }
                }
            });
        });
    </script>
@stop
