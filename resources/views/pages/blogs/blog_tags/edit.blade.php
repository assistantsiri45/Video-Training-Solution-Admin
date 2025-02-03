@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Tag</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('blogs.tags.update',$tag->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{$tag->name}}" placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Update</button>
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
                        required: true
                    },
                }
            });
        });
    </script>
@stop

@push('css')
    <style>
        .ce-block__content,
        .ce-toolbar__content {
            max-width: unset;
            padding-left: 12px;
            padding-right: 12px;
        }
    </style>
@endpush
