@extends('adminlte::page')

@section('title', 'Study Materials')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Study Materials</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('study-materials.create') }}" type="button" class="btn btn-success">Create</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" >
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" id="search" type="text" placeholder="Search" title="Package name or Subject name">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="language">
                                    <option value=""></option>
                                    @foreach (\App\Models\Language::all() as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
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
                            <div class="col-md-2 mt-2">
                                <select class="form-control" id="professor">
                                    <option value=""></option>
                                    @foreach ($professors as $professor)
                                        <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mt-2">
                                <button class="btn btn-primary" id="button-search">Search</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>
                        </div>
                    </div>
                     <div style="overflow-x: auto">
                         {!! $html->table(['id' => 'datatable'], true) !!}
                     </div>
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
                    language: $('#language').val(),
                    course: $('#course').val(),
                    level: $('#level').val(),
                    subject: $('#subject').val(),
                    professor: $('#professor').val(),
                    package_type:$('#package_type').val(),
                }
            });

            $('#course').select2({
                placeholder: 'Course'
            });

            $('#language').select2({
                placeholder: 'Language'
            });
            $('#level').select2({
                placeholder: 'Level'
            });
            $('#subject').select2({
                placeholder: 'Subject'
            });
            $('#package_type').select2({
                placeholder: 'Type'
            });
            $('#professor').select2({
                placeholder: 'Professor'
            });

            $('#course').on('change', function () {
                var CourseID = $(this).val();
                $('#level').empty();
                $('#package_type').empty();
                $('#subject').empty();
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
                $('#package_type').empty();
                $('#subject').empty();
                var LevelID = $(this).val();
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
                var package_type = $(this).val();
                $('#subject').empty();
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


            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#language').val('').change();
                $('#course').val('').change();
                $('#level').val('').change();
                $('#subject').val('').change();
                $('#professor').val('').change();
                $('#package_type').val('').change();
                table.DataTable().draw();
            });
        });
    </script>
@stop
