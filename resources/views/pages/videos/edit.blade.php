@extends('adminlte::page')

@section('title', 'Edit Video')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Video</h1>
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
                <form role="form" id="form-edit-video" method="POST" action="{{ route('videos.update', $video->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Course</label>
                                    <x-inputs.course id="course_id" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                        @if(!empty(old('course_id', $video->course_id ?? '')))
                                            <option value="{{ old('course_id', $video->course_id ?? '') }}" selected>{{ old('course_id_text', $video->course->name ?? '') }}</option>
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
                                        @if(!empty(old('level_id', $video->level_id)))
                                            <option value="{{ old('level_id', $video->level_id) }}" selected>{{ old('level_id_text', $video->level->name ) }}</option>
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
                                        @foreach($types as $type)
                                        @if(!empty($type->packagetype))
                                        <option value="{{$type->packagetype->id}}" @if($video->package_type_id == $type->packagetype->id) selected @endif>{{$type->packagetype->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>                        
                       
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Subject</label>
                                    <select class="form-control select2" id="subject" name="subject_id">
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
                                    <x-inputs.chapter id="chapter_id" related="#subject">
                                        @if(!empty(old('chapter_id', $video->chapter_id)))
                                            <option value="{{ old('chapter_id', $video->chapter_id) }}" selected>{{ old('chapter_id_text', $video->chapter->name) }}</option>
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
                                        @if(!empty(old('professor_id', $video->professor_id)))
                                            <option value="{{ old('professor_id', $video->professor_id) }}" selected>{{ old('professor_id_text', $video->professor->name) }}</option>
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
                                    <label for="title">Title</label>
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Title" value="{{ old('title', $video->title) }}">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                    @enderror
                                </div>
                            </div>
                     
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" placeholder="Description" value="{{ old('description', $video->description) }}">
                                    @error('description')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('description') }}</span>
                                    @enderror
                                </div>
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="tags">Tags</label>
                                    <input id="tags" type="text" class="form-control @error('tags') is-invalid @enderror" name="tags" placeholder="Tags" value="{{ old('tags', $video->tags) }}">
                                    @error('tags')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('tags') }}</span>
                                    @enderror
                                </div>
                            </div>
                       
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="language">Language</label>
                                    <select id="language" class="form-control" name="language">
                                        <option value=""></option>
                                        @foreach (\App\Models\Language::all() as $language)
                                            <option value="{{ $language->id }}" @if ($language->id == $video->language_id) selected @endif>{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="module">Module</label>
                                    <select id="module" class="form-control" name="module"></select>
                                </div>
                            </div>
                       
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="video">Video</label>
                                    <select id="video" class="form-control" name="video">
                                        <option value=""></option>
                                        @foreach (\App\Models\Video::where('is_published', 1)->get() as $publishedVideo)
                                            <option value="{{ $publishedVideo->id }}">{{ $publishedVideo->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="version_number">Version Number</label>
                                    <input id="version_number" name="version_number" type="number" class="form-control" placeholder="Version Number"  value="{{ old('version_number', $video->version_number) }}">
                                    @if ($errors->has('version_number'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('version_number') }}</span>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="applicable_for">Applicable For</label>
                                    <input id="applicable_for" name="applicable_for" type="text"  class="form-control" placeholder="Applicable For"
                                           value="{{ old('applicable_for', $video->applicable_for) }}">
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
                                        
                                        <option value="1" @if($video->video_category=='1') selected @endif>Core Video</option>
                                        <option value="2" @if ($video->video_category=='2') selected @endif>Bonus Video</option>
                                    </select>
                                    <!-- <input id="video_cat" name="video_cat" type="text" class="form-control" placeholder="Applicable For"  value="{{ old('applicable_for') }}"> -->
                                    @if ($errors->has('video_cat'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('video_cat') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="duration">Video Duration</label>
                                    <input id="duration" name="duration" type="text" class="form-control" placeholder="Duration in seconds"
                                           value="{{ old('duration', $video->duration) }}">
                                    @if ($errors->has('duration'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('duration') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        <input id="has-demo" type="checkbox" name="has_demo" @if ($video->has_demo) checked @endif> Demo?
                                    </label>
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
@endsection

@push('js')
    <script>
         var package_type='{{$video->package_type_id}}';
        var level_id='{{$video->level_id}}';
        var subject_id='{{$video->subject_id}}';
        getSubject(package_type,level_id);
        $(function() {
            $('#form-edit-video').validate({
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
                    title: {
                        required: true
                    },
                    module: {
                        required: true
                    },
                    duration: {
                        required: true
                    }

                }
            });

            $('#language').select2({
                placeholder: 'Language'
            });

            $('#module').select2({
                placeholder: 'Module'
            });

            $('#video').select2({
                placeholder: 'Video'
            });
            $('#package_type').select2({
                placeholder: 'Type'
            });

            function appendModule(chapterID) {
                $.ajax({
                    url: '{{ route('api.modules.index') }}',
                    data: {
                        chapter_id: chapterID
                    }
                }).done(function(response) {
                    $('#module').empty();
                    $('#module').append(`<option value=""></option>`);

                    $.each(response.data, function(key, val) {
                        let moduleID = '{{ $video->module_id }}';
                        moduleID = parseInt(moduleID);

                        $('#module').append(`<option value="${val.id}" ${ val.id === moduleID ? 'selected' : '' }>${val.name}</option>`);
                    });
                });
            }

            appendModule($('#chapter_id').val());

            $('#chapter_id').change(function() {
                if($(this).val()) {
                    appendModule($(this).val());
                }
            });
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
                
                   $('#subject').empty();
                   if(response.length>0){
                       $('#subject').append('<option disabled selected>  Choose Subject </option>');
                      
                       $.each(response, function( index, value ) {
                           var item = value.id;
                           if(item==subject_id){
                            exist=true;
                           }else{
                            exist=false;
                           }
                          console.log(exist);
                          
                           $('#subject').append('<option value="' + value.id + '" ' + ( exist ? 'selected':'') + ' >' + value.name + '</option>');

                       });
                       // $("#no_subjects_available").addClass('d-none');
                   }
                   else{
                       // if(subjectsArray.length==0){
                           // $("#subject-container").empty();
                           // $("#no_levelt_selected").addClass('d-none');
                           // $("#no_subjects_available").removeClass('d-none');
                       // }
                   }

               });
           }
    </script>
@endpush
