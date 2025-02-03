@extends('adminlte::page')

@section('title', 'Chapters')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Modules</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('modules.create') }}" type="button" class="btn btn-success">Create</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" id="search" type="text" placeholder="Search" title="Package name or Subject name">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="course">
                                    <option value=""></option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="level_id" id="level"  class="form-control select-level" style="width: 100% !important;">
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="package_type" name="package_type" style="width: 100%">
                                     <option value="">Choose Type</option>                                            
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="subject">
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="chapter">
                                </select>
                            </div>
                            <div class="col-md-2 mt-2">
                                <button class="btn btn-primary" id="button-search">Search</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>
                        </div>
                    </div>
                    {!! $html->table(['id' => 'datatable'], true) !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}
    <script>
        $(function() {
            let table = $('#datatable');

            table.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    course: $('#course').val(),
                    level: $('#level').val(),
                    subject: $('#subject').val(),
                    chapter: $('#chapter').val(),
                    package_type:$('#package_type').val(),
                }
            });

            $('#course').select2({
                placeholder: 'Course'
            });
            $('#level').select2({
                placeholder: 'Level'
            });
            $('#subject').select2({
                placeholder: 'Subject'
            });
            $('#chapter').select2({
                placeholder: 'Chapter'
            });
            $('#package_type').select2({
                placeholder: 'Type'
            });

            $('#course').on('change', function () {
                var CourseID = $(this).val();
                $('#level').empty();
                $('#package_type').empty();
                $('#subject').empty();
                $('#chapter').empty();
                if (CourseID) {
                    $.ajax({
                        url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#level').append('<option disabled selected>  Choose Level </option>');
                            $.each(data, function (key, value) {
                                $('#level').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                   
                }
            });

            var package_type;
            $('#level').on('change', function () {
                var LevelID = $(this).val();
                $('#package_type').empty();
                $('#subject').empty();
                $('#chapter').empty();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
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
                }
            });

            $('#package_type').on('change', function () {
                $('#subject').empty();
                $('#chapter').empty();
                var package_type = $(this).val();
                var level_id=$("#level").val();
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
                        
                    }
                    else{
                    }

                });
            }

            $('#subject').on('change', function () {
                var SubjectID = $(this).val();
                SubjectChapters(SubjectID);
            });

            function SubjectChapters(SubjectID) {
                if (SubjectID) {
                    $.ajax({
                        url: '{{ url('/subject-chapters/ajax') }}' + '/' + SubjectID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#chapter').empty();
                            $('#chapter').append('<option disabled selected>  Choose Chapter </option>');
                            $.each(data, function (key, value) {
                                $('#chapter').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                        }
                    });
                } else {
                    $('#chapter').empty();
                }
            }


            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#course').val('').change();
                $('#level').val('').change();
                $('#subject').val('').change();
                $('#chapter').val('').change();
                $('#package_type').val('').change();
                table.DataTable().draw();
            });
        });
    </script>
@stop
