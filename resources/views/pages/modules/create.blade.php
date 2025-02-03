@extends('adminlte::page')

@section('title', 'Create Module')

@section('content_header')
    <h1 class="m-0 text-dark">Create Module</h1>
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
                <form role="form" id="create" method="POST" action="{{ route('modules.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Course</label>

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
                                    <label>Level</label>
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
                                        <!-- @foreach($types as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach -->
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
                                        @if(!empty(old('chapter_id')))
                                            <option value="{{ old('chapter_id') }}" selected>{{ old('chapter_id_text') }}</option>
                                        @endif
                                    </x-inputs.chapter>

                                    @if ($errors->has('chapter_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('chapter_id') }}</span>
                                    @endif
                                </div>
                            </div>
                       
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Module</label>
                                    <input type="text" name="name" class="form-control" id="module" @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Module Name">
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
                }

            });


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
                  
                    $('#subject').empty();
                    if(response.length>0){
                        $('#subject').append('<option disabled selected>  Choose Subject </option>');
                       
                        $.each(response, function( index, value ) {
                            var item = value.id;
                           
                           
                            $('#subject').append('<option value="' + value.id + '">' + value.name + '</option>');

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
