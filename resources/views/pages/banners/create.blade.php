@extends('adminlte::page')

@section('title', 'Create Banner')

@section('content_header')
    <h1 class="m-0 text-dark">Create Banner</h1>
@stop

@section('content')
    <form role="form" id="create" method="POST" action="{{ route('banners.store') }}" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                        <div class="cr-viewport cr-vp-rectangle" tabindex="0" style="width: 100%;margin-bottom: 35px;">
                    <img id="blah" src="" style="width: 100%;height: 400px;object-fit: contain;"/>
                    </div>
                            <div class="col-md-8 offset-2">
                                <div class="form-group">
                                    
                                        <div class="input-group">
                                            <input  class="form-control" type="file"  id="upload" name="file" @error('image') is-invalid @enderror required onchange="readURL(this);">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="button" id="crop-btn" >Upload Image</button>
                                            </div>
                                            @error('image')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('image') }}</span>
                                            @enderror
                                       
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" class="form-control"  id="title" @error('title') is-invalid @enderror value="{{ old('title') }}" placeholder="Title">
                                            @error('title')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="alt">Alt</label>
                                            <input class="form-control @error('alt') is-invalid @enderror" id="alt"
                                                   name="alt" type="text" value="{{ old('alt') }}"
                                                   placeholder="Alt">
                                            @error('title')
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('alt') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="link">Link</label>
                                            <input type="text" name="link" class="form-control"  id="link" @error('link') is-invalid @enderror value="{{ old('link') }}" placeholder="Link">
                                            @error('link')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('link') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="youtubeId">Youtube ID</label>
                                            <input type="text" name="youtube_id" class="form-control"  id="youtube_id" @error('youtube_id') is-invalid @enderror value="{{ old('youtube_id') }}" placeholder="Youtube ID">
                                            @error('youtube_id')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('youtube_id') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="card-footer banner-details">
                    <button type="submit" class="btn btn-primary banner-upload-btn">Create</button>
                </div>

            </div>
        </div>
       
    </div>
    </form>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#create').validate({
                rules: {
                    file: {
                        required: true
                    },
//                    title: {
//                        required: true
//                    },
//                    link: {
//                        required: true,
//                        url: true
//                    },
                }
            });
           
        });
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@stop
