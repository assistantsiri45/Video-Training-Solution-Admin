@extends('adminlte::page')

@section('title', 'Edit Course')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Course</h1>
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
                <form role="form" id="edit" method="POST" action="{{ route('courses.update', $course->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Course Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Course Name"
                                       @error('name') is-invalid @enderror" value="{{ old('name', $course->name) }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input name="display" value="0" type="hidden">
                            <input class="custom-checkbox" id="display" name="display"
                                   type="checkbox" value="1" @if($course->display == 1)checked @endif/>
                            <label for="display">Display</label>
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
            $('#edit').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255,
                        //lettersandspace: true
                    }
                }
            });
        });
    </script>
@stop
