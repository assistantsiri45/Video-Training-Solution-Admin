@extends('adminlte::page')

@section('title', 'Create Professor')

@section('content_header')
    <h1 class="m-0 text-dark">Create Professor</h1>
@stop

@section('content')
        <form role="form" id="create" method="POST" action="{{ route('professors.store') }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        @csrf
                        <div id="hidden-inputs-container">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" @error('name') is-invalid @enderror value="{{ old('name') }}" class="form-control" placeholder="Name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" id="title" name="title" @error('title') is-invalid @enderror value="{{ old('title') }}" class="form-control" placeholder="Title">
                                        @error('title')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" id="email" name="email" @error('email') is-invalid @enderror value="{{ old('email') }}" class="form-control" placeholder="Email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('email') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <div class="input-group-prepend">
                                            <div class="col-md-4">
                                                <select id="mobile-code" class="custom-select "  name="mobile_code">
                                                    <option @if(old('mobile_code')=='+91') selected @endif value="+91">+91</option>
                                                    <option  @if(old('mobile_code')=='+971') selected @endif value="+971">+971</option>
                                                </select>
                                            </div>
                                            <input type="text" id="mobile" name="mobile" class="form-control " @error('mobile') is-invalid @enderror value="{{ old('mobile') }}" placeholder="Mobile">
                                            @error('mobile')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('mobile') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="password_option">Password Option</label>
                                        <select name="password_option" id="password_option" required class="form-control select2 select2-hidden-accessible @error('password_option') is-invalid @enderror"  value="{{ old('password_option') }}"  style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option data-select2-id="{{ \App\Models\Professor::MAIL}}" @if(old('password_option')==\App\Models\Professor::MAIL) selected @endif value="{{  \App\Models\Professor::MAIL }}">{{  \App\Models\Professor::MAIL_TEXT }}</option>
                                            <option data-select2-id="{{ \App\Models\Professor::MANUAL}}" @if(old('password_option')==\App\Models\Professor::MANUAL) selected @endif value="{{  \App\Models\Professor::MANUAL }}">{{  \App\Models\Professor::MANUAL_TEXT }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" required name="password" class="form-control" @error('password') is-invalid @enderror value="{{ old('password') }}" placeholder="Password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('password') }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="upload">Image</label>
                                        <div class="input-group mb-3">
                                        <input  class="form-control" accept="image/*"  value="{{old('image')}}"  type="text" placeholder="Choose Image" id="file-text"  name="file" @error('file') is-invalid @enderror required >
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="choose_image" data-toggle="modal"  data-target="#modal-choose-image">Choose</button>
                                            </div>
                                        </div>
                                        @error('file')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('file') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
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
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="video_type">Video Type</label>
                                        <select name="video_type" id="video_type" class="form-control select2 select2-hidden-accessible @error('video_type') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                            <option></option>
                                            <option data-select2-id="{{ \App\Models\Professor::MANUAL_UPLOAD}}" @if(old('video_type')==\App\Models\Professor::MANUAL_UPLOAD) selected @endif value="{{  \App\Models\Professor::MANUAL_UPLOAD }}">{{  \App\Models\Professor::MANUAL_UPLOAD_TEXT }}</option>
                                            <option data-select2-id="{{ \App\Models\Professor::YOUTUBE}}" @if(old('video_type')==\App\Models\Professor::YOUTUBE) selected @endif value="{{  \App\Models\Professor::YOUTUBE }}">{{  \App\Models\Professor::YOUTUBE_TEXT }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="video_file">Select Video</label>
                                    <div class="input-group mb-3">
                                        <input id="video_file" name="video_file" type="text" class="form-control" placeholder="Video file"  readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="choose_video_folder" data-toggle="modal" data-target="#modal-choose-folder">Choose</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="video">Video URL</label>
                                        <input type="text" id="video_url" name="video_url"  value="{{old("video_url")}}" class="form-control" placeholder="Video URL" disabled>
                                    </div>
                                </div>
{{--                                <div class="col-sm-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="experience">Experience</label>--}}
{{--                                        <input type="text" id="experience" name="experience" class="form-control" @error('experience') is-invalid @enderror value="{{ old('experience') }}" placeholder="Experience">--}}
{{--                                        @error('experience')--}}
{{--                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('experience') }}</span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="career_start_at">Career Start At</label>
                                        <input type="date" id="career_start_at" name="career_start_at" class="form-control @error('career_start_at') is-invalid @enderror" value="{{ old('career_start_at') }}" >
                                        @error('career_start_at')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('career_start_at') }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="revenue">Revenue</label>
                                        <input type="number" id="revenue" name="revenue" class="form-control" @error('revenue') is-invalid @enderror value="{{ old('revenue') }}" placeholder="Professor Revenue">
                                        @error('revenue')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('revenue') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input type="text" id="description" name="description" class="form-control" @error('description') is-invalid @enderror value="{{ old('description') }}" placeholder="Description">
                                        @error('description')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('description') }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="introduction">Introduction</label>
                                        <textarea class="form-control" id="introduction" name="introduction" required rows="3" value="{{ old('introduction') }}" placeholder="Introduction">{{ old('introduction') }}</textarea>
                                    </div>
                                    @error('introduction')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('introduction') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input class="custom-checkbox" id="is-published" name="is_published"
                                           type="checkbox" />
                                    <label for="is-published">Is Published</label>
                                </div>
                            </div>
                            <span id="video-loading" style="display: none;">Loading...</span>

                            <div id="videos" class="videos-container panel-group"
                                 data-old='@json(old('videos'))'
                                 data-errors='@json(Arr::unDot($errors->get('videos.*')))'>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12 crop-tool" hidden >
                   <div class="card card-primary">
                       <div class="edit-pic p-3" >
                           <img width="100%"   id="photo" class="img-thumbnail" >
                       </div>
                   </div>
                </div>
            </div>
            @include('pages.image_crop.image-upload')
            @include('pages.professors.choose-video-file')
        </form>

@stop

@section('js')
    <script>
        $(document).ready(function () {

            $("#password_option").select2({
                placeholder: 'Select Password Option'
            });

            $("#video_type").select2({
                placeholder: 'Select Video Type'
            });

            $("#password_option").change(function () {
                if($(this).val()==2){
                    $("#password").prop( "disabled", false );
                }
                else{
                    $("#password").prop( "disabled", true );
                }
            });

            $("#video_type").change(function () {
                if($(this).val()==2){
                    $("#video_url").prop( "disabled", false );
                    $("#choose_video_folder").prop( "disabled", true );
                }
                else{
                    $("#video_url").prop( "disabled", true );
                    $("#choose_video_folder").prop( "disabled", false );
                }
            });

            $('#create').validate({
                rules: {
                    name: {
                        required: true,
                        accept: "[a-zA-Z]+"
                    },
                    title:{
                        required: true,
                    },
                    email: {
                        required: true,
                        maxlength: 255,
                        email: true
                    },
                    mobile_code: {
                        required: true,
                    },
                    mobile: {
                        required: true,
                        maxlength: function() {
                            if ($('#mobile-code').val() === '+91') {
                                return 10;
                            } else {
                                return 9;
                            }
                        },
                        minlength: function() {
                            if ($('#mobile-code').val() === '+91') {
                                return 10;
                            } else {
                                return 9;
                            }
                        },
                        remote: {
                            url: '{{ url('validate-phone') }}',
                            type: 'POST',
                            data: {
                                mobile: function() {
                                    if ($('#mobile-code').val() === '+91') {
                                        return '+91' + $('#phone').val();
                                    } else {
                                        return '+971' + $('#phone').val();
                                    }
                                }
                            }
                        }
                    },
                    password_option:{
                        required: true,
                    },
                    file: {
                        required: true,
                    },
                    career_start_at: {
                        required: true,
                    },
                    description:{
                        required: true
                    }
                }
            });

            var uploadCrop = $('#upload-demo').croppie({
                enableExif: true,
                viewport: {
                    width: 350,
                    height: 250,
                    type: 'rectangle',
                    enableZoom : true,
                    enableResize: true,
                },
                boundary: {
                    width: 400,
                    height: 300
                }
            });
            uploadCrop.croppie('bind', {
                url: '/images/placeholder.png',
            });


            $('#upload').on('change', function () {
                var filename = $(this).val().split('\\').pop();
                $('#file-text').val(filename);
                var reader = new FileReader();
                reader.onload = function (e) {
                    uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        $('#crop-btn').on('click', function (){
                            uploadCrop.croppie('result', {
                                type: 'canvas',
                                size: 'viewport'
                            }).then(function (resp) {
                                $('#hidden-inputs-container').html(`<input type="hidden" name="image" value="${resp}">`);
                                $(".crop-tool").attr("hidden",false);
                                $('#photo').attr('src',resp);
                                // $('#create').on('submit', function () {
                                //     $('#hidden-inputs-container').html(`<input type="hidden" name="image" value="${resp}">`);
                                //     $('#hidden-inputs-container').html(`<input type="hidden" name="file" value="${$("#upload".val())}">`);
                                // });
                            });
                        });

                    });
                };
                reader.readAsDataURL(this.files[0]);
            });

            $('#modal-choose-folder').on('folder_choose', function (e, path,contents) {
                $('#video_file').val(path);
                // console.log( "path"+contents);
            });

            var videos = $('#videos').data('old');
            var errors = $('#videos').data('errors')['videos'];
            var jvErrors = {};

            if (videos) {
                $.each(videos, function (index, video) {
                    addVideo(index, video['url'], video);

                    if (errors) {
                        let error = errors[index];
                        if (error) {
                            $.each(error, function (key, value) {
                                if (value.length == 0) return;
                                jvErrors['videos[' + index + '][' + key + ']'] = value[0];
                            });
                        }
                    }
                });

                var validator = $('#form-video-create').validate();
                validator.showErrors(jvErrors);
            }
        });
    </script>
@stop
