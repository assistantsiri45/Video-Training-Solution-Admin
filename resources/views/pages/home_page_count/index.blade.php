@extends('adminlte::page')

@section('title', 'Count Setting')

@section('content_header')
    <h1 class="m-0 text-dark">Count Setting</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="edit" method="POST" action="{{ route('count-setting.store') }}">
                    @csrf
                    <div class="card-body">
                    @php 
                    $counts = explode('|',$file_data);
                    @endphp
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Courses Purchased</label>
                                        <input type="text" name="courses_purchased" class="form-control" id="courses_purchased" placeholder="Courses Purchased" value="{{ @$counts[0] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Students Enrolled</label>
                                        <input type="text" name="students_enrolled" class="form-control" id="students_enrolled" placeholder="Students Enrolled" value="{{ @$counts[1] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Uploaded Videos</label>
                                        <input type="text" name="uploaded_videos" class="form-control" id="uploaded_videos" placeholder="Uploaded Videos" value="{{ @$counts[2] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Listed Courses</label>
                                        <input type="text" name="listed_courses" class="form-control" id="listed_courses" placeholder="Listed Courses" value="{{ @$counts[3] }}">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
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
                   "courses_purchased": {
                        required: true,
                        maxlength: 255,
                        pattern: /^[0-9,]+$/
                    },
                    "students_enrolled": {
                        required: true,
                        maxlength: 255,
                        pattern: /^[0-9,]+$/
                    },
                    "uploaded_videos": {
                        required: true,
                        maxlength: 255,
                        pattern: /^[0-9,]+$/
                    },
                    "listed_courses": {
                        required: true,
                        maxlength: 255,
                        pattern: /^[0-9,]+$/
                    }
                    
                },
                messages: {
                    "courses_purchased": {
                        pattern: 'Please enter a valid number'
                    },
                    "students_enrolled": {
                        pattern: 'Please enter a valid number'
                    },
                    "uploaded_videos": {
                        pattern: 'Please enter a valid number'
                    },
                    "listed_courses": {
                        pattern: 'Please enter a valid number'
                    }
                }
            });
        });
    </script>
@stop
