@extends('adminlte::page')

@section('title', 'Edit Professor')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Professor</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form role="form" id="edit" method="POST" action="{{ route('professors.update', $professor->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div id="hidden-inputs-container">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                                                   @error('name') is-invalid @enderror value="{{ old('name', $professor->name) }}">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" class="form-control" id="title" placeholder="Title"
                                                   @error('title') is-invalid @enderror value="{{ old('title', $professor->title) }}">
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
                                            <input type="text" id="email" name="email" class="form-control" placeholder="Email" @error('email') is-invalid @enderror value="{{ old('email', $professor->email) }}">
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
                                                    <select id="mobile-code" class="custom-select"  name="mobile_code">
                                                        <option  value="+91">+91</option>
                                                        <option value="+971">+971</option>
                                                    </select>
                                                </div>
                                                <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Mobile" @error('mobile') is-invalid @enderror value="{{ old('mobile', $professor->mobile) }}">
                                                @error('name')
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
                                            <select name="password_option" id="password_option" required class="form-control select2 select2-hidden-accessible @error('password_option') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                                <option></option>
                                                <option data-select2-id="{{ \App\Models\Professor::MAIL}}" value="{{  \App\Models\Professor::MAIL }}"  @if ($professor->password_option == \App\Models\Professor::MAIL) selected @endif>{{  \App\Models\Professor::MAIL_TEXT }}</option>
                                                <option data-select2-id="{{ \App\Models\Professor::MANUAL}}" value="{{  \App\Models\Professor::MANUAL }}"  @if ($professor->password_option == \App\Models\Professor::MANUAL) selected @endif>{{  \App\Models\Professor::MANUAL_TEXT }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" id="password"  name="password" class="form-control" @error('password') is-invalid @enderror value="{{ old('password',$professor->password) }}"
                                                   @if($professor->password_option==\App\Models\Professor::MAIL) disabled @endif placeholder="Password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('password') }}</span>
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
                                                <option data-select2-id="{{ \App\Models\Professor::MANUAL_UPLOAD}}" value="{{  \App\Models\Professor::MANUAL_UPLOAD }}"
                                                        @if($professor->video_type== \App\Models\Professor::MANUAL_UPLOAD) selected @endif>{{  \App\Models\Professor::MANUAL_UPLOAD_TEXT }}</option>
                                                <option data-select2-id="{{ \App\Models\Professor::YOUTUBE}}" value="{{  \App\Models\Professor::YOUTUBE }}"
                                                        @if($professor->video_type== \App\Models\Professor::YOUTUBE) selected @endif>{{  \App\Models\Professor::YOUTUBE_TEXT }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="video_file">Update Video</label>
                                        <div class="input-group mb-3">
                                            <input id="video_file" name="video_file" type="text" class="form-control" placeholder="Video file"  readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="choose_video_folder" data-toggle="modal" data-target="#modal-choose-folder">Choose</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($professor->video_type== \App\Models\Professor::MANUAL_UPLOAD)
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                            <label for="video_file">Uploaded Video URL</label>
                                                <p class="ml-2" id="uploaded_video" for="video">{{$professor->video}}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="video">Video URL</label>
                                            <input type="text" id="video_url" name="video_url" class="form-control"
                                                   @if($professor->video_type== \App\Models\Professor::YOUTUBE) value="{{$professor->video}}" @endif placeholder="Video URL"  >
                                        </div>
                                    </div>
{{--                                    <div class="col-sm-3">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label for="experience">Experience</label>--}}
{{--                                            <input type="text" id="experience" name="experience" class="form-control" placeholder="Experience" @error('experience') is-invalid @enderror value="{{ old('experience', $professor->experience) }}">--}}
{{--                                            @error('experience')--}}
{{--                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('experience') }}</span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="career_start_at">Career Start At</label>
                                            <input type="date" id="career_start_at" name="career_start_at" class="form-control @error('career_start_at') is-invalid @enderror"
                                                   value="{{ old('career_start_at',$professor->career_start_at ? \Carbon\Carbon::parse($professor->career_start_at)->toDateString() : '' ) }}" >
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
                                            <input type="number" id="revenue" name="revenue" class="form-control" @error('revenue') is-invalid @enderror value="{{$professor->professor_revenue }}" placeholder="Professor Revenue">
                                            @error('revenue')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('revenue') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <input type="text" id="description" name="description" class="form-control" @error('description') is-invalid @enderror value="{{$professor->description }}" placeholder="Description">
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
                                            <textarea class="form-control" id="introduction" name="introduction" required rows="3" placeholder="Introduction" @error('introduction') is-invalid @enderror>{{ old('introduction', $professor->introduction) }}</textarea>

                                            @error('introduction')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('introduction') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="custom-checkbox" id="is-published" name="is_published"
                                               type="checkbox" @if ($professor->is_published) checked @endif />
                                        <label for="is-published">Is Published</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12 ">
                                <div class="crop-tool">
                                    <div class="card-body"  id="image-card" >
                                        <div id="upload-demo" ></div>
                                        <div id="upload-demo-i" name="image_viewport"></div>
                                    </div>
                                </div>
                                <div id="hidden-inputs-container"></div>
                                <div class="col-md-10 offset-1">
                                    <div class="input-group mb-3">
                                        <input  class="form-control" type="file"  id="upload" name="file" @error('file') is-invalid @enderror  >
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="button" id="crop-btn" >Upload Image</button>
                                        </div>
                                    </div>
                                    @error('file')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('file') }}</span>
                                    @enderror
                                    <input  hidden type="text"  id="image-src" value="{{$professor['image'] }}">
                                </div>
                                <div class="col-md-10 offset-1">
                                    <div class="form-group">
                                        <label for="alt">Alt</label>
                                        <input class="form-control @error('alt') is-invalid @enderror" id="alt"
                                               name="alt" type="text" value="{{ old('alt', $professor->alt) }}"
                                               placeholder="Alt">
                                        @error('title')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('alt') }}</span>
                                        @enderror
                                    </div>
                                </div>
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

    @include('pages.image_crop.image-upload')
    @include('pages.professors.choose-video-file')
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
                    // $('#video_url').attr('value',null);
                    $("#choose_video_folder").prop( "disabled", false );
                }
            });

            $('#edit').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
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
                    video_file:{
                        required: function(element){
                            if ($('#uploaded_video').val()) {
                                $("#video_type").val()==1;
                                return true;
                            } else {
                                return false
                            }
                        }

                    },
                    video_url:{
                        required: function(element){
                            return $("#video_type").val()==2;
                        }
                    },
                    career_start_at: {
                        required: true,
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
                    height:300
                }

            });
            uploadCrop.croppie('bind', {
                url: $("#image-src").val()
            });

            $('#crop-btn').on('click', function (){
                uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp) {
                    $(".crop-tool").attr("hidden",false);
                    $('#photo').attr('src',resp);
                    $('#edit').on('submit', function () {
                        $('#hidden-inputs-container').html(`<input type="hidden" name="image" value="${resp}">`);
                    });
                });
            });

            $('#upload').on('change', function () {
                var filename = $(this).val().split('\\').pop();
                $('#file-text').val(filename);
                var reader = new FileReader();
                reader.onload = function (e) {
                    uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        $( "#crop-btn" ).trigger( "click" );
                    });
                };
                reader.readAsDataURL(this.files[0]);
            });

            $('#modal-choose-folder').on('folder_choose', function (e, path,contents) {
                $('#video_file').val(path);
            });

        });
    </script>
@stop
