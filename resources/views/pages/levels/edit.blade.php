@extends('adminlte::page')

@section('title', 'Edit Level')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Level</h1>
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
                <form role="form" id="create" method="POST" action="{{ route('levels.update', $level->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Course</label>
                                    <x-inputs.course id="course_id" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                        @if(!empty(old('course_id', $level->course_id)))
                                            <option value="{{ old('course_id', $level->course_id) }}" selected>{{ old('course_id_text', empty($level->course) ? '' : $level->course->name) }}</option>
                                        @endif
                                    </x-inputs.course>

                                    @if ($errors->has('course_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Level Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Level Name"
                                           @error('name') is-invalid @enderror" value="{{ old('name', $level->name) }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input name="display" value="0" type="hidden">
                                <input class="custom-checkbox" id="display" name="display"
                                       type="checkbox" value="1" @if($level->display == 1)checked @endif/>
                                <label for="display">Display</label>
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
            $('#create').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255,
                        //lettersandspace: true
                    },
                    course: {
                        required: true,
                    }
                }
            });

            $("#course").select2({
                placeholder: 'Please choose a Course'
            });
        });
    </script>
@stop
