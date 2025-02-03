@extends('adminlte::page')

@section('title', 'Create Type')

@section('content_header')
    <h1 class="m-0 text-dark">Create Type</h1>
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
                <form role="form" id="create" method="POST" action="{{ route('type.store') }}">
                    @csrf
                    <div class="card-body">
                    <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Course</label>

                                    <select class="form-control " name="course_id[]" id="course_id"  style="width: 100% !important;" multiple>
                                                    <option value=""></option>
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                    @endforeach
                                                </select>

                                    @if ($errors->has('course_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Level</label>
                                    <select name="level_id[]" id="level"  class="form-control select-level" style="width: 100% !important;" multiple>
                                                </select>

                                    @if ($errors->has('level_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Type Name</label>
                                <input type="text" name="name" class="form-control" id="name" @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Type Name">
                                @error('name')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
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
                        required: true
                    },
                    "level_id[]": {
                            required: true
                    }
                }
            });
        });
        $('#level').select2({
                placeholder: 'Level'
            });
            $('#course_id').select2({
                placeholder: 'Choose Course'
            });
        $(function () {

// Course wise Levels

$('#course_id').on('change', function () {
    var CourseID = $(this).val();

    if (CourseID) {
        $.ajax({
            url: '{{ url('/getlevels/ajax') }}' + '/' + CourseID,
            type: "GET",
            dataType: "json",
            success: function (data) {
                $('#level').empty();
                $('#level').append('<option disabled selected>  Choose Level </option>');
                $.each(data, function (key, value) {
                    $('#level').append('<option value="' + value.id + '|' + value.course_id + '">' + value.name + '</option>');
                });

            }
        });
    } else {
        $('#level').empty();
    }
});
        });
    </script>
@stop
