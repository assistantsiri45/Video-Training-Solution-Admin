@extends('adminlte::page')

@section('title', 'Create Level')

@section('content_header')
    <h1 class="m-0 text-dark">Create Level</h1>
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
                <form role="form" id="create" method="POST" action="{{ route('levels.store') }}">
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
                                    <label for="name">Level Name</label>
                                    <input type="text" name="name" class="form-control" id="level" @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Level Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input name="display" value="0" type="hidden">
                                <input class="custom-checkbox" id="display" name="display"
                                       type="checkbox" value="1" checked />
                                <label for="display">Display</label>
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
            $('#create').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255,
                        //lettersandspace: true
                    },
                    course_id: {
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
