@extends('adminlte::page')

@section('title', 'Upload Video')

@section('content_header')
    <h1 class="m-0 text-dark">Upload Video</h1>
@stop

@section('content')
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form role="form" id="form-video-create" method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Course</label>

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
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Level</label>
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
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                <label>Type</label>
                                    <select class="form-control" id="package_type" name="package_type">
                                        <option value="">Choose Type</option>
                                        
                                    </select>
                                </div>
                            </div>
                        
                        
                        
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">    
                                    <label>Subject</label>
                                    <select class="form-control select2" id="subject_id" name="subject_id">
                                        <option value="">Choose Subject</option>
                                       
                                    </select>

                                    @if ($errors->has('subject_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('subject_id') }}</span>
                                    @endif
                                </div>
                            </div>
                                
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Chapter</label>
                                    <x-inputs.chapter id="chapter_id" related="#subject_id">
                                        @if(!empty(old('chapter_id')))
                                            <option value="{{ old('chapter_id') }}" selected>{{ old('chapter_id_text') }}</option>
                                        @endif
                                    </x-inputs.chapter>

                                    @if ($errors->has('chapter_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('chapter_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Professor</label>
                                    <x-inputs.professor id="professor_id">
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
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="version_number">Version Number</label>
                                    <input id="version_number" name="version_number" type="number" class="form-control" placeholder="Version Number"  value="{{ old('version_number') }}">
                                    @if ($errors->has('version_number'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('version_number') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="applicable_for">Applicable For</label>
                                    <input id="applicable_for" name="applicable_for" type="text" class="form-control" placeholder="Applicable For"  value="{{ old('applicable_for') }}">
                                    @if ($errors->has('applicable_for'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('applicable_for') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="applicable_for">Video Category</label>
                                    <select id="video_cat" name="video_cat" class="form-control" placeholder="Choose category">
                                        
                                        <option value="1">Core Video</option>
                                        <option value="2">Bonus Video</option>
                                    </select>
                                    <!-- <input id="video_cat" name="video_cat" type="text" class="form-control" placeholder="Applicable For"  value="{{ old('applicable_for') }}"> -->
                                    @if ($errors->has('video_cat'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('video_cat') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input id="has-media-id" name="has_media_id" type="checkbox"> Has Media ID?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="media-container d-none">

                        </div>
                        <div class="folder-container">
                            <div class="row">
                                {{--                            <div class="col-sm-6 col-md-4">--}}
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label class="form-control-label" for="thumbnail">Image</label>--}}
                                {{--                                    <input id="thumbnail" name="thumbnail" type="file" data-plugin="dropify" data-default-file=""--}}
                                {{--                                    class="form-control dropify{{ $errors->has('thumbnail') ? ' is-invalid' : '' }}" value="{{ old('thumbnail') }}" autocomplete="off">--}}
                                {{--                                </div>--}}
                                {{--                                @if ($errors->has('thumbnail'))--}}
                                {{--                                    <span class="invalid-feedback" role="alert">{{ $errors->first('thumbnail') }}</span>--}}
                                {{--                                @endif--}}
                                {{--                            </div>--}}
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="folder_name">Folder</label>
                                        {{--<select id="folder_name" name="folder_name" class="form-control select2" readonly>
                                            <option value="" selected>Select folder</option>
                                        </select>--}}
                                        <div class="input-group">
                                            <input id="folder_name" name="folder_name" type="text" class="form-control" placeholder="Video folder" readonly value="{{ old('folder_name') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="button-addon2" data-toggle="modal" data-target="#modal-choose-folder">Choose</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="video_count">Video Count</label>
                                        <input type="text" id="video_count" name="video_count" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <span id="video-loading" style="display: none;">Loading...</span>

                            <div id="videos" class="videos-container panel-group"
                                 data-old='@json(old('videos'))'
                                 data-errors='@json(Arr::unDot($errors->get('videos.*')))'>
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

    <div id="template-video-form" class="panel border mt-3" style="display: none;">
        <div class="panel-heading px-4 pt-4 ">
            <h5 class="panel-title mb-0">
                <label class="m-0">
                    <input class="video_checked" type="checkbox" name="video[1][checked]"> <span class="video_path">Video 1</span>
                    <input class="video_url" type="hidden" name="video[1][checked]">
                </label>
            </h5>
        </div>
        <div class="panel-body p-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="video_title">Title</label>
                        <input type="text" class="form-control video_title" name="video_title[]"
                               placeholder="Title">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="video_description">Description</label>
                        <input type="text" class="form-control video_description" name="video_description[]"
                               placeholder="Description">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="video_tags">Tags</label>
                        <input type="text" class="form-control video_tags" name="video_tags[]"
                               placeholder="Tags">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="video_language">Language</label>
                        <select id="video_language" class="form-control video_language" name="video_language[]">
                            <option value="" selected disabled hidden>Language</option>
                            @foreach (\App\Models\Language::all() as $language)
                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="video_module">Module</label>
                        <select id="video_module" class="form-control video_module" name="video_module[]"></select>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="video_is_demo is_demo" name="is_demo[]"> Demo?
                        </label>
                    </div>
                </div>
            </div>
            <div class="row demo_time" style="display: none;">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="video_start_time">Start time</label>
                        <input type="time" class="form-control video_start_time" name="video_start_time[]" step="1">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="video_end_time">End time</label>
                        <input type="time" class="form-control video_end_time" name="video_end_time[]" step="1">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="media-template d-none">
        <div class="removable-media-container">
            <div class="row mt-3">
                <div class="col-md-8">
                    {button}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="media-id-{index}">Media ID</label>
                        <input class="form-control media-id" id="media-id-{index}" name="media_id[{index}]" placeholder="Media ID" autocomplete="off" required>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="media-title-{index}">Title</label>
                        <input class="form-control media-title" id="media-title-{index}" name="media_title[{index}]" type="text" placeholder="Title" autocomplete="off" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="media-description-{index}">Description</label>
                        <input class="form-control media-description" id="media-description-{index}" name="media_description[{index}]" type="text" placeholder="Description" autocomplete="off" required>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="media-tags-{index}">Tags</label>
                        <input class="form-control media-tags" id="media-tags-{index}" name="media_tags[{index}]" type="text" placeholder="Tags" autocomplete="off" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="media-language-{index}">Language</label>
                        <select id="media-language-{index}" class="form-control media-language" name="media_language[{index}]" required>
                            <option value="" selected disabled hidden>Language</option>
                            @foreach (\App\Models\Language::all() as $language)
                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="form-group">
                        <label for="media-module-{index}">Module</label>
                        <select class="form-control media-module" id="media-module-{index}" name="media_module[{index}]" required></select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.videos.choose-folder-modal')
@stop

@section('js')
    <script>
        $(document).ready(function () {

            var addVideo = function (index, file, video) {
                var $template = $('#template-video-form').clone();
                $template.show();

                $template.find('.video_path').text(file.split(/(\\|\/)/g).pop());
                $template.find('.video_url').val(file);

                $template.find('.video_checked').attr('name', 'videos['+index+'][checked]');
                $template.find('.video_url').attr('name', 'videos['+index+'][url]');
                $template.find('.video_title').attr('name', 'videos['+index+'][title]');
                $template.find('.video_description').attr('name', 'videos['+index+'][description]');
                $template.find('.video_tags').attr('name', 'videos['+index+'][tags]');
                $template.find('.video_language').attr('name', 'videos['+index+'][language]');
                $template.find('.video_module').attr('name', 'videos['+index+'][module]');
                $template.find('.video_is_demo').attr('name', 'videos['+index+'][is_demo]');
                $template.find('.video_start_time').attr('name', 'videos['+index+'][start_time]');
                $template.find('.video_end_time').attr('name', 'videos['+index+'][end_time]');

                if (video) {
                    $template.find('.video_checked').prop( "checked", video['checked'] ? true : false );
                    $template.find('.video_url').val(video['url']);
                    $template.find('.video_title').val(video['title']);
                    $template.find('.video_description').val(video['description']);
                    $template.find('.video_tags').val(video['tags']);
                    $template.find('.video_language').val(video['language']);
                    $template.find('.video_module').val(video['module']);
                    $template.find('.video_is_demo').prop( "checked", video['is_demo'] ? true : false );
                    $template.find('.video_start_time').val(video['start_time']);
                    $template.find('.video_end_time').val(video['end_time']);

                    if (video['is_demo']) {
                            $template.find('.demo_time').show();
                    } else {
                        $template.find('.demo_time').hide();
                    }
                }

                $('#videos').append($template);

                $template.find('[name="videos['+index+'][title]"]').rules("add", {
                    required: function () {
                        return $template.find('[name="videos['+index+'][checked]"]').prop("checked")
                    }
                });

                $template.find('[name="videos['+index+'][module]"]').rules("add", {
                    required: function () {
                        return $template.find('[name="videos['+index+'][checked]"]').prop("checked")
                    }
                });

                $template.find('[name="videos['+index+'][start_time]"]').rules("add", {
                    required: function () {
                        return $template.find('[name="videos['+index+'][checked]"]').prop("checked") &&
                                $template.find('[name="videos['+index+'][is_demo]"]').prop("checked")
                    }
                });

                $template.find('[name="videos['+index+'][end_time]"]').rules("add", {
                    required: function () {
                        return $template.find('[name="videos['+index+'][checked]"]').prop("checked") &&
                                $template.find('[name="videos['+index+'][is_demo]"]').prop("checked")
                    }
                });

            };


            $('#form-video-create').validate({
                rules: {
                    course_id: {
                        required: true
                    },
                    level_id: {
                        required: true
                    },
                    subject_id: {
                        required: true
                    },
                    chapter_id: {
                        required: true
                    },
                    professor_id: {
                        required: true
                    },
                    folder_name: {
                        required: true
                    },
                    video_count: {
                        required: true,
                        min: 1
                    },
                    version_number:{
                        required: false,
                        number: true
                    },
                },
                messages: {
                    video_count: {
                        required: "There are no videos based on your selections in course, level, subject, chapter, professor and folder",
                        min: "There are no videos based on your selections in course, level, subject, chapter, professor and folder"
                    }
                }

            });


            $(document).on('change', ".is_demo", function () {
                var $demo_time = $(this).closest('.panel').find('.demo_time');

                if ($(this).is(":checked")) {
                    $demo_time.show();
                } else {
                    $demo_time.hide();
                }
            });

            $('#modal-choose-folder').on('folder_choose', function (e, path) {

                $('#folder_name').val(path);
                $('#videos').empty();

                if (path) {
                    $("#video-loading").show();

                    $.ajax({
                        url: '{{ route('api.videos.files.index') }}',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            path: path
                        },
                        success: function (response) {
                            var files = response.data;

                            $.each(files, function (index, file) {
                                addVideo(index, file, null, null)
                            });

                            $('#video_count').val(files.length);
                            $('#video_count').valid();

                            if (files.length > 0) {
                                $('#videos').show();
                            }
                        },
                        complete: function () {
                            $("#video-loading").hide();

                            if ($('#chapter_id').val()) {
                                $.ajax({
                                    url: '{{ route('api.modules.index') }}',
                                    data: {
                                        chapter_id: $('#chapter_id').val()
                                    }
                                }).done(function(response) {
                                    $('.video_module').empty();
                                    $('.video_module').append(`<option value="" selected disabled hidden>Module</option>`);

                                    $.each(response.data, function(key, val) {
                                        $('.video_module').append(`<option value="${val.id}">${val.name}</option>`);
                                    });

                                    $('.media-module').empty();
                                    $('.media-module').append(`<option value="" selected disabled hidden>Module</option>`);

                                    $.each(response.data, function(key, val) {
                                        $('.media-module').append(`<option value="${val.id}">${val.name}</option>`);
                                    });
                                });
                            }
                        }
                    });
                }
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

                $('#video_count').val(videos.length);
            }

            $('.dropify').dropify();

            $('#image_file').on('dropify.afterClear', function (event, element){
                $('#image').val("")
            });

//            $('#professor_id').change();

            $('#chapter_id').change(function() {
                if ($(this).val()) {
                    $.ajax({
                        url: '{{ route('api.modules.index') }}',
                        data: {
                            chapter_id: $(this).val()
                        }
                    }).done(function(response) {
                        $('.video_module').empty();
                        $('.video_module').append(`<option value="" selected disabled hidden>Module</option>`);

                        $.each(response.data, function(key, val) {
                            $('.video_module').append(`<option value="${val.id}">${val.name}</option>`);
                        });

                        $('.media-module').empty();
                        $('.media-module').append(`<option value="" selected disabled hidden>Module</option>`);

                        $.each(response.data, function(key, val) {
                            $('.media-module').append(`<option value="${val.id}">${val.name}</option>`);
                        });
                    });
                }
            });

            $('#has-media-id').change(function() {
                if ($(this).is(':checked')) {
                    $('.media-container').removeClass('d-none');
                    $('.folder-container').addClass('d-none');
                } else {
                    $('.media-container').addClass('d-none');
                    $('.folder-container').removeClass('d-none');
                }
            });


            let mediaTemplateIndex = 0;

            function appendTemplate() {
                let template = $('.media-template').clone();
                template.removeClass('media-template');
                template.removeClass('d-none');

                if (mediaTemplateIndex === 0) {
                    template = $(template.html().replaceAll('{index}', mediaTemplateIndex).replaceAll('{button}', '<button class="btn btn-success float-right button-add-media-template" type="button"><i class="fas fa-plus"></i></button>'));
                } else {
                    template = $(template.html().replaceAll('{index}', mediaTemplateIndex).replaceAll('{button}', '<button class="btn btn-danger float-right button-remove-media-template" type="button"><i class="fas fa-trash"></i></button>'));
                }

                $('.media-container').append(template);
                mediaTemplateIndex++;
            }

            appendTemplate();

            $('.button-add-media-template').click(function () {
                appendTemplate();
            });

            $(document).on('click', '.button-remove-media-template', function () {
                $(this).closest('.removable-media-container').remove();
            });
            
            var package_type;
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
                        getSubject(package_type,LevelID);
                    }
                });
              

                } else {
                    $('#package_type').empty();
                }
            });
            $('#package_type').on('change', function () {
                var package_type = $(this).val();
                var level_id=$("#level_id").val();
                if(package_type && level_id){
                    getSubject(package_type,level_id);

                }
            });
            function getSubject(package_type,level_id){
               
                let url = '{{ url('get-subjects-by-level') }}';

                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: 'json',
                    data: {
                       
                        "level_ids" : level_id ,
                        "type_id"  : package_type   ,
                    }
                }).done(function (response) {
                  
                    $('#subject_id').empty();
                    if(response.length>0){
                        $('#subject_id').append('<option disabled selected>  Choose Subject </option>');
                       
                        $.each(response, function( index, value ) {
                            var item = value.id;
                           
                           
                            $('#subject_id').append('<option value="' + value.id + '">' + value.name + '</option>');

                        });
                        
                    }
                    else{
                       
                    }

                });
            }
        });
    </script>
@stop
