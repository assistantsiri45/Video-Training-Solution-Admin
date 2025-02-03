@extends('adminlte::page')

@section('title', 'Custom Testimonials')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Custom Testimonials</h1>
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
                <form role="form"  method="POST" action="{{ route('custom-testimonials.update',$testimonial->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name" @error('name') is-invalid @enderror value="{{$testimonial->name}}" placeholder="Name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="testimonial">Testimonial</label>
                                        <textarea  id="testimonial" name="testimonial" rows="4" required class="form-control @error('testimonial') is-invalid @enderror">{{$testimonial->testimonial}}</textarea>
                                        @error('testimonial')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('testimonial') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="status">&nbsp;</label>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="status" name="status"
                                            @if($testimonial->publish==\App\Models\CustomTestimonial::PUBLISHED) checked @endif>
                                            <label for="status" class="custom-control-label">Publish</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="image_file">Image</label>
                                    <input id="image_file" name="image_file" type="file" data-plugin="dropify"
                                           class="form-control dropify{{ $errors->has('image_file') ? ' is-invalid' : '' }}"  value="{{ old('image') }}"
                                           data-default-file="{{ old('image', $testimonial->image_url) }}"  autocomplete="off">

                                    <input id="image" name="image"  value="{{ old('image', $testimonial->image) }}" type="hidden">
                                </div>
                                @if ($errors->has('image_file'))
                                    <span class="invalid-feedback" role="alert">{{ $errors->first('image_file') }}</span>
                                @endif
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
            $('.dropify').dropify();

            $('#image_file').on('dropify.afterClear', function (event, element){
                $('#image').val("")
            });
        });
    </script>
@stop
