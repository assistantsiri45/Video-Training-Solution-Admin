@extends('adminlte::page')

@section('title', 'Subjects')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Subjects</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('subjects.create') }}" type="button" class="btn btn-success">Create</a>
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
                                <input class="form-control" id="search" type="text" placeholder="Search" title="Course name or level name">
                            </div>
                            <div class="col-md-2">
                                <select id="course" class="form-control select-course" style="width: 100% !important;">
                                    <option value=""></option>
                                    @foreach (\App\Models\Course::all() as $course)
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
                            <div class="col-md-3">
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
        $(function () {

            $('#course').on('change', function () {
                var CourseID = $(this).val();
                $('#level').empty();
                $('#package_type').empty();

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

            $('#level').on('change', function () {
                var LevelID = $(this).val();
                $('#package_type').empty();
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
                    }
                });             

                } else {
                }
            });


            let table = $('#datatable');

            $('#course').select2({
                placeholder: 'Course'
            });
            $('#level').select2({
                placeholder: 'Level'
            });
            $('#package_type').select2({
                placeholder: 'Type'
            });

            $("#datatable").on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    course: $('#course').val(),
                    level: $('#level').val(),
                    package_type:$('#package_type').val(),

                }
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#course').val('').change();
                $('#level').val('').change();
                $('#package_type').val('').change();
                table.DataTable().draw();
            });



        })
    </script>
@stop
