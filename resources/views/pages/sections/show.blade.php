@extends('adminlte::page')

@section('title', 'Section')

@section('content_header')
    <div class="row">
        <div class="col-sm-8">
            <h1 class="m-0 text-dark">{{$section->name}}</h1>
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
                            <div class="col text-left">
                                <h3>Selected Packages</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{url("sections/$section->id/section-packages/order")}}" type="button" class="btn btn-success mr-2">Change Order</a>
                                <a href="{{url("sections/$section->id/section-packages")}}" type="button" class="btn btn-warning">Add/Edit</a>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <input class="form-control" id="search" type="text" placeholder="Search" title="Search">
                            </div>
                            <div class="col-md-2">
                                <select id="select-course" class="form-control select-course" style="width: 100% !important;">
                                    <option value=""></option>
                                    @foreach (\App\Models\Course::all() as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="level_id" id="select-level"  class="form-control select-level" style="width: 100% !important;">
                                </select>
{{--                                <select id="select-level" class="form-control select-level" style="width: 100% !important;">--}}
{{--                                    <option value=""></option>--}}
{{--                                    @foreach (\App\Models\Level::all() as $level)--}}
{{--                                        <option value="{{ $level->id }}">{{ $level->name }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
                            </div>
                            <div class="col-md-2">
                                <select id="select-subject" class="form-control select-subject" style="width: 100% !important;">
                                    <option value=""></option>
                                    @foreach (\App\Models\Subject::all() as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="select-chapter" class="form-control select-chapter" style="width: 100% !important;">
                                    <option value=""></option>
                                    @foreach (\App\Models\Chapter::all() as $chapter)
                                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <select id="select-language" class="form-control select-language" style="width: 100% !important;">
                                    <option value=""></option>
                                    @foreach (\App\Models\Language::all() as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="select-professor" class="form-control select-professor" style="width: 100% !important;">
                                    <option value=""></option>
                                    @foreach (\App\Models\Professor::all() as $professor)
                                        <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" id="button-search">Search</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        {!! $html->table(['id' => 'section-packages'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function () {
            let table = $('#section-packages').DataTable();
            table.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    course: $('#select-course').val(),
                    level: $('#select-level').val(),
                    subject: $('#select-subject').val(),
                    chapter: $('#select-chapter').val(),
                    language: $('#select-language').val(),
                    professor: $('#select-professor').val()
                }
            });

            $('#button-search').click(function() {
                table.draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#select-course').val('').change();
                $('#select-level').val('').change();
                $('#select-subject').val('').change();
                $('#select-chapter').val('').change();
                $('#select-language').val('').change();
                $('#select-professor').val('').change();
                table.draw();
            });

            $('.select-course').select2({
                placeholder: 'Course'
            });

            $('.select-level').select2({
                placeholder: 'Level'
            });

            $('.select-subject').select2({
                placeholder: 'Subject'
            });

            $('.select-chapter').select2({
                placeholder: 'Chapter'
            });

            $('.select-language').select2({
                placeholder: 'Language'
            });

            $('.select-professor').select2({
                placeholder: 'Professor'
            });
            $(document).ready(function() {

                $('#select-course').on('change', function () {
                    var CourseID = $(this).val();

                    if (CourseID) {
                        $.ajax({
                            url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                // $('#product_id').select2('val','');
                                $('#select-level').empty();
                                $.each(data, function (key, value) {
                                    $('#select-level').append('<option value="' + value.id + '">' + value.name + '</option>');
                                });

                            }
                        });
                    } else {
                        $('#select-level').empty();
                    }
                });

            });
        });
    </script>
@stop

