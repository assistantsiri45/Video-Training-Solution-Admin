@extends('adminlte::page')

@section('title', 'Edit Chapter')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Module</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('modules.update', $module->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Course</label>
                                    <x-inputs.course id="course_id" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                        @if(!empty(old('course_id', $module->course_id)))
                                            <option value="{{ old('course_id', $module->course_id) }}" selected>{{ old('course_id_text', $module->course->name) }}</option>
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
                                        @if(!empty(old('level_id', $module->level_id)))
                                            <option value="{{ old('level_id', $module->level_id) }}" selected>{{ old('level_id_text', $module->level->name) }}</option>
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
                                        @foreach($types as $type)
                                        @if(!empty($type->packagetype))
                                        @if($module->package_type_id == $type->packagetype->id))
                                            <option value="{{ old('package_type_id', $module->package_type->id) }}" selected>{{ old('level_id_text', $module->package_type->name) }}</option>
                                       @else
                                        <option value="{{$type->packagetype->id}}">{{$type->packagetype->name}}</option>
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
                                        @if(!empty(old('chapter_id', $module->chapter_id)))
                                            <option value="{{ old('chapter_id', $module->chapter_id) }}" selected>{{ old('chapter_id_text', $module->chapter->name) }}</option>
                                        @endif
                                    </x-inputs.chapter>

                                    @if ($errors->has('chapter_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('chapter_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Module Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Chapter Name"
                                           @error('name') is-invalid @enderror" value="{{ old('name', $module->name) }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
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
@stop

@section('js')
    <script>
        var package_type='{{$module->package_type_id}}';
        var level_id='{{$module->level_id}}';
        var subject_id='{{$module->subject_id}}';
        getSubject(package_type,level_id);
        $(document).ready(function () {
            $('#subject').select2({
                placeholder: 'Subject'
            });
            $(document).ready(function () {
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
                        }
                    }
                });
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
@stop
