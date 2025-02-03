@extends('adminlte::page')

@section('title', 'Custom Testimonials')

@section('content_header')
    <h1 class="m-0 text-dark">Create Custom Testimonials</h1>
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
                <form role="form" id="create" method="POST" action="{{ route('custom-testimonials.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name" @error('name') is-invalid @enderror value="{{ old('name') }}" placeholder="Name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="testimonial">Testimonial</label>
                                        <textarea  id="testimonial" name="testimonial" rows="4" required class="form-control @error('testimonial') is-invalid @enderror">{{ old('testimonial') }}</textarea>
                                        @error('testimonial')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('testimonial') }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="image_file">Image</label>
                                    <input id="image_file" name="image_file" type="file" data-plugin="dropify" data-default-file=""
                                    class="form-control dropify{{ $errors->has('image_file') ? ' is-invalid' : '' }}" value="{{ old('image_file') }}" autocomplete="off">

{{--                                    <input id="image" name="image" type="hidden">                                   --}}
                                </div>
                            </div>
                            @error('image_file')
                                <span class="invalid-feedback" role="alert">{{ $errors->first('image_file') }}</span>
                            @endif
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
            $('.dropify').dropify();

            $('#image_file').on('dropify.afterClear', function (event, element){
                $('#image').val("")
            });
        });
    </script>
@stop
