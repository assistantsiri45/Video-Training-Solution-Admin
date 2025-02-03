@extends('adminlte::page')

@section('title', 'Edit Courier')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Courier</h1>
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
                <form role="form" id="edit" method="POST" action="{{ route('couriers.update', $courier->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Courier Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Courier Name"
                                       @error('name') is-invalid @enderror" value="{{ old('name', $courier->name) }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="url">Courier URL</label>
                                <input type="text" name="url" class="form-control" id="name" placeholder="Courier Name"
                                       @error('url') is-invalid @enderror" value="{{ old('url', $courier->url) }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('url') }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Courier Status</label>
                                <select name="status" class="form-control" id="status" >
                                <option value="">Select</option>
                                        @if($courier->status==1)
                                        <option value="1" selected>Enable</option>
                                        <option value="0" >Disable</option>
                                        @else
                                        <option value="1" >Enable</option>
                                        <option value="0" selected>Disable</option>
                                        @endif
                                </select>
                                @error('status')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('status') }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#edit').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255,
                       // lettersandspace: true
                    },
                    url: {
                        required: true,
                        maxlength: 255
                    },
                    status: {
                        required: true

                    }
                }
            });
        });
    </script>
@stop
