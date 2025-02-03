@extends('adminlte::page')

@section('title', 'Free Resources')

@section('content_header')
    <h1 class="m-0 text-dark">Create Free Resources</h1>
@stop

@section('content')
<style>
    .dt-buttons{
    display: none;
}
</style>
    <div class="row">
        <div class="col-md-9">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('free-resource.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Course &ensp; &ensp;</label>

                                    <x-inputs.course id="course_id" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                        @if(!empty(old('course_id')))
                                            <option value="{{ old('course_id') }}" selected>{{ old('course_id_text') }}</option>
                                        @endif
                                    </x-inputs.course>

                                    @if ($errors->has('course_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Level &ensp; &ensp;</label>
                                    <x-inputs.level id="level_id" related="#course_id">
                                        @if(!empty(old('level_id')))
                                            <option value="{{ old('level_id') }}" selected>{{ old('level_id_text') }}</option>
                                        @endif
                                    </x-inputs.level>

                                    @if ($errors->has('level_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Type</label>
                                    <select class="form-control" id="package_type" name="package_type">
                                        <option value="">Choose Type</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Professor</label>
                                    <x-inputs.professor  id="professor_id">
                                        @if(!empty(old('professor_id')))
                                            <option value="{{ old('professor_id') }}" selected>{{ old('professor_id_text') }}</option>
                                        @endif
                                    </x-inputs.professor>

                                    @if ($errors->has('professor_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('professor_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" required  id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Title">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea required  class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" value="{{ old('description') }}" placeholder="Description">{{ old('description') }}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Select Type of Resource</label>
                                <select name="type" id="type" required class="form-control select2 select2-hidden-accessible type @error('amount_type') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                    <option></option>
                                    <option data-select2-id="{{ \App\Models\FreeResource::YOUTUBE_ID}}" value="{{  \App\Models\FreeResource::YOUTUBE_ID }}">{{  \App\Models\FreeResource::YOUTUBE_ID_TEXT }}</option>
{{--                                    <option data-select2-id="{{ \App\Models\FreeResource::IMAGE}}" value="{{  \App\Models\FreeResource::IMAGE }}">{{  \App\Models\FreeResource::IMAGE_TEXT }}</option>--}}
                                    <option data-select2-id="{{ \App\Models\FreeResource::NOTES}}" value="{{  \App\Models\FreeResource::NOTES }}">{{  \App\Models\FreeResource::NOTES_TEXT }}</option>
{{--                                    <option data-select2-id="{{ \App\Models\FreeResource::AUDIO_FILES}}" value="{{  \App\Models\FreeResource::AUDIO_FILES }}">{{  \App\Models\FreeResource::AUDIO_FILES_TEXT }}</option>--}}
{{--                                    <option data-select2-id="{{ \App\Models\FreeResource::JW_VIDEO}}" value="{{  \App\Models\FreeResource::JW_VIDEO }}">{{  \App\Models\FreeResource::JW_VIDEO_TEXT }}</option>--}}
                                </select>
                            </div>
                            <div class="col-sm-6 youtube-div" hidden>
                                <label>Youtube Id</label>
                                <input type="text"  id="youtube_id" name="youtube_id" class="form-control @error('youtube_id') is-invalid @enderror" value="{{ old('youtube_id') }}" placeholder="Youtube ID">
                                @error('youtube_id')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('youtube_id') }}</span>
                                @enderror
                                
                            </div>
                            <!---Added BY TE-->
                            <div class="row">
                            <div class="col-sm-12 youtube-div-package mt-3" hidden >
                            <button type="button" class="btn btn-warning sel-pkg" onclick="show_pack()">Select Packages</button>
                            </div>
                            <input name="demo_package_id" id="demo_package_id" type="hidden" value=""/>
                            <div class="pkg-list" hidden>
                           
                            {!! $html->table(['id' => 'demopack'], true) !!}
                           
                            </div>
                            </div>
                            <!----end TE Modification-->
                            <div class="col-sm-6 image-div" hidden>
                                <label>Upload Image (JPG,JPEG,PNG)</label>
                                <input type="file"  id="image" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror" value="{{ old('image') }}" >
                                @error('image')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('image') }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6 notes-div" hidden>
                                <label>Upload Document (PDF,PPT,DOC,DOCX)</label>
                                <input type="file"  id="notes" name="notes"  class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes') }}" >
                                @error('notes')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('notes') }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6 audio-div" hidden>
                                <label>Audio  (MP3,WAV)</label>
                                <input type="file"  id="audio" accept="audio/*" name="audio" class="form-control @error('audio') is-invalid @enderror" value="{{ old('audio') }}" >
                                @error('audio')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('audio') }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6 jw-div" hidden>
                                <label>JW Video </label>
                                <div class="input-group mb-3">
                                    <input id="video_file" name="video_file" type="text" class="form-control" placeholder="Video file"  readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="choose_video_folder" data-toggle="modal" data-target="#modal-choose-folder">Choose</button>
                                    </div>
                                </div>
                                @error('folder_name')
                                  <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('folder_name') }}</span>
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

    @include('pages.professors.choose-video-file')

@stop

@section('js')
{!! $html->scripts() !!}
    <script>
        $(document).ready(function () {
            $('#create').validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255,
                    },
                    description: {
                        required: true,
                    },
                    type: {
                        required: true,
                    },
                    course_id: {
                        required: true
                    },
                    level_id: {
                        required: true
                    }
                }
            });

            $("#type").select2({
                placeholder: 'Select resource type'
            });
            let table = $('#demopack');
            table.DataTable().draw();
            $( ".type" ).change(function() {
               if($(this).val() == 1 ) {
                   $(".youtube-div").attr('hidden',false);
                   $(".youtube-div-package").attr('hidden',false);
                   $(".pkg-list").attr('hidden',true);
                   $("#youtube_id").attr('required',true);
                   $(".image-div").attr('hidden',true);
                   $(".notes-div").attr('hidden',true);
                   $(".audio-div").attr('hidden',true);
                   $(".jw-div").attr('hidden',true);
               }
               else if($(this).val() == 2){
                   $(".youtube-div").attr('hidden',true);
                   $(".pkg-list").attr('hidden',true);
                   $(".youtube-div-package").attr('hidden',true);
                   $(".image-div").attr('hidden',false);
                   $("#image").attr('required',true);
                   $(".notes-div").attr('hidden',true);
                   $(".audio-div").attr('hidden',true);
                   $(".jw-div").attr('hidden',true);
               }
               else if($(this).val() == 3){
                   $(".youtube-div").attr('hidden',true);
                   $(".pkg-list").attr('hidden',true);
                   $(".youtube-div-package").attr('hidden',true);
                   $(".image-div").attr('hidden',true);
                   $("#notes").attr('required',true);
                   $(".notes-div").attr('hidden',false);
                   $(".audio-div").attr('hidden',true);
                   $(".jw-div").attr('hidden',true);
               }
               else if($(this).val() == 4){
                   $(".youtube-div").attr('hidden',true);
                   $(".pkg-list").attr('hidden',true);
                   $(".youtube-div-package").attr('hidden',true);
                   $(".image-div").attr('hidden',true);
                   $(".notes-div").attr('hidden',true);
                   $(".audio-div").attr('hidden',false);
                   $("#audio").attr('required',true);
                   $(".jw-div").attr('hidden',true);
               }
               else if($(this).val() == 5){
                   $(".youtube-div").attr('hidden',true);
                   $(".pkg-list").attr('hidden',true);
                   $(".youtube-div-package").attr('hidden',true);
                   $(".image-div").attr('hidden',true);
                   $(".notes-div").attr('hidden',true);
                   $(".audio-div").attr('hidden',true);
                   $(".jw-div").attr('hidden',false);
                   $("#folder_name").attr('required',true);
               }
            });
        });
        function show_pack(){
         $(".pkg-list").attr('hidden',false);
          
        }

        $(document).on('click', '[name="packages"]', function () {
            var p_id= $(this).val();
            $("#demo_package_id").val(p_id);
        });
        
        $('#level_id').on('change', function () {
                var LevelID = $(this).val();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#package_type').empty();
                        $('#package_type').append('<option disabled selected>  Choose Type </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#package_type').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                    
                        });

                    }
                });
                } else {
                    $('#package_type').empty();
                }
            });
    </script>
   
@stop

