@extends('adminlte::page')

@section('title', 'Edit Study Materials')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Study Materials</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('study-materials.update', $studyMaterial->id) }}"  enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Course</label>
                                    <x-inputs.course id="course_id" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                        @if(!empty(old('course_id', $studyMaterial->course_id)))
                                            <option value="{{ old('course_id', $studyMaterial->course_id ?? '') }}" selected>{{ old('course_id_text', $studyMaterial->course->name ?? '') }}</option>
                                        @endif
                                    </x-inputs.course>

                                    @if ($errors->has('course_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Level</label>
                                    <x-inputs.level id="level_id" related="#course_id">
                                        @if(!empty(old('level_id', $studyMaterial->level_id)))
                                            <option value="{{ old('level_id', $studyMaterial->level_id) }}" selected>{{ old('level_id_text', $studyMaterial->level->name) }}</option>
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
                                    <label>Package Type</label>
                                    <select class="form-control" id="package_type" name="package_type">
                                    <option value="">Choose Type</option>
                                        @foreach($types as $type)
                                        @if(!empty($type->packagetype))
                                        @if($studyMaterial->package_type_id == $type->packagetype->id))
                                            <option value="{{ old('package_type_id', $studyMaterial->package_type->id) }}" selected>{{ old('level_id_text', $studyMaterial->package_type->name) }}</option>
                                       @else
                                        <option value="{{$type->id}}">{{$type->packagetype->name}}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Subject</label>
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
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Chapter</label>
                                    <x-inputs.chapter id="chapter_id" related="#subject">
                                        @if(!empty(old('chapter_id',$studyMaterial->chapter_id)))
                                            <option value="{{ old('chapter_id', $studyMaterial->chapter_id) }}" selected>{{ old('chapter_id_text', $studyMaterial->chapter->name ?? ' ') }}</option>
                                        @endif
                                    </x-inputs.chapter>

                                    @if ($errors->has('chapter_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('chapter_id') }}</span>
                                    @endif
                                </div>
                            </div>
                       
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="language_id">Language</label>
                                    <select id="language_id" class="form-control select2 video_language" name="language_id" required>
                                        <option value="" selected disabled hidden>Select Language</option>
                                        @foreach (\App\Models\Language::all() as $language)
                                            <option @if( old('language_id') == $language->id ) selected @endif value="{{ $language->id }}"
                                                {{ $language->id == $studyMaterial->language_id ? 'selected' : '' }}>{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="professor_id">Professor</label>
                                    <select id="professor_id" class="form-control select2  professor_id" name="professor_id" required>
                                        <option value="" selected disabled hidden>Select Professor</option>
                                        @foreach (\App\Models\Professor::all() as $professor)
                                            <option  @if( old('professor_id') == $professor->id ) selected @endif value="{{ $professor->id }}"
                                                {{ $professor->id == $studyMaterial->professor_id ? 'selected' : '' }} >{{ $professor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Type</label>
                                    <select id="type" class="form-control  type" name="type" required>
                                        <option value="" selected disabled hidden>Select Type</option>
                                        <option @if( old('type') ==  \App\Models\StudyMaterialV1::STUDY_MATERIALS ) selected @endif value="{{ \App\Models\StudyMaterialV1::STUDY_MATERIALS }}"
                                            {{ $studyMaterial->type == \App\Models\StudyMaterialV1::STUDY_MATERIALS ? 'selected' : '' }} >{{\App\Models\StudyMaterialV1::STUDY_MATERIALS_TEXT}}</option>
                                        <option @if( old('type') ==  \App\Models\StudyMaterialV1::STUDY_PLAN ) selected @endif value="{{ \App\Models\StudyMaterialV1::STUDY_PLAN }}"
                                            {{ $studyMaterial->type == \App\Models\StudyMaterialV1::STUDY_PLAN ? 'selected' : '' }} >{{\App\Models\StudyMaterialV1::STUDY_PLAN_TEXT}}</option>
                                        <option @if( old('type') ==  \App\Models\StudyMaterialV1::TEST_PAPER ) selected @endif value="{{ \App\Models\StudyMaterialV1::TEST_PAPER }}"
                                            {{ $studyMaterial->type == \App\Models\StudyMaterialV1::TEST_PAPER ? 'selected' : '' }} >{{\App\Models\StudyMaterialV1::TEST_PAPER_TEXT}}</option>
                                    </select>
                                </div>
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Study Material</label>
                                    <input type="file" name="study_material"  accept="application/pdf" class="form-control study_materials" id="study_material" @error('study_material') is-invalid
                                           @enderror value="{{ old('study_material', $studyMaterial->file_name) }}">
                                    @error('study_material')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('study_material') }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if( $studyMaterial->file_name)
                            <div class="col-sm-2">
                                <div class="form-group mt-4">
                                    <a href="{{ url('storage/study_materials', $studyMaterial->file_name) }}" >
                                        {{$studyMaterial->file_name}}</a>

                                </div>
                            </div>
                            @endif
                        </div>
                          
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Title</label>
                                    <input type="text" name="title" class="form-control title" placeholder="Title" id="title" @error('title') is-invalid @enderror value="{{ old('title', $studyMaterial->title) }}">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">

                                <div id="file-details-form" ></div>
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
        var package_type='{{$studyMaterial->package_type_id}}';
        var level_id='{{$studyMaterial->level_id}}';
        var subject_id='{{$studyMaterial->subject_id}}';
        getSubject(package_type,level_id);
        $(document).ready(function () {
            $('#subject').select2({
                placeholder: 'Subject'
            });
            $('#create').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    course_id: {
                        required: true
                    },
                    level_id: {
                        required: true
                    },
                    subject_id: {
                        required: true
                    },
                    language_id: {
                        required: true
                    },
                    professor_id: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                }
            });
        });
        $("#professor_id").select2({
            placeholder: 'Select Professor'
        });

        $("#language_id").select2({
            placeholder: 'Select Language'
        });
        $("#type").select2({
            placeholder: 'Select Type'
        });


        $('#create input[type="file"]').change(function(){
            var title = '{{ $studyMaterial->title}}';
            if($(this).val()){
                for(var i=0; i< this.files.length; i++) {
                    var file = this.files[i];
                    name = file.name.toLowerCase();
                    size = file.size;
                    type = file.type;
                    var file_details_row = $(
                        '<div class="panel border mt-3">'+
                        '<div class="panel-body p-4">' +
                        '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<div class="form-group">' +
                        '<label for="file">File</label>' +
                        '<input type="text" class="form-control" id="file_name" readonly value="' + name + '" name="file_name" placeholder="File">' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>');
                    $("#file-details-form").append(file_details_row);
                }

                $('#file-details-form').removeAttr('hidden');
            }
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
@stop
