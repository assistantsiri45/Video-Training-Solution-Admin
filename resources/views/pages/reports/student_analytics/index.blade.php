@extends('adminlte::page')

@section('title', 'Reports - Student Analytics')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Reports - Student Analytics</h1>
        </div>
    </div>
@stop
<style>
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 3% !important;
    }
</style>

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <input class="form-control" id="search" type="text" placeholder="Search">
                        </div>
                        <div class="col-md-3">
                        <input id="date" type="text" class="form-control" placeholder="Date">
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input id="log_e_date" name="log_e_date" type="date" class="form-control float-right" placeholder="End Date">
                            </div>
                        </div> -->
                    </div>
                    <br>
                    <div class="row">
                        
                                        <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control" id="course">
                                                <option value=""></option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="level_id" id="level"  class="form-control select-level" style="width: 100% !important;">
                                            </select>
                                        </div>
                                     
                                        <div class="col-md-2">
                                            <select class="form-control" id="subject">

                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select id="select-chapter" class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control" id="language">
                                                <option value="" placeholder="Select Language"></option>
                                                @foreach ($languages as $language)
                                                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        </div>
                    </div>
                <div class="row">
                        <div class="col-md-3">
                            <button id="button-search" class="btn btn-primary">Search</button>
                            <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                        </div>
</div>
                    </div>
                
                <div class="table-responsive">
                    {!! $table->table(['id' => 'analytics-table'], true) !!}
                </div>
            </div>
        </div>
    </div>
   
@stop

@push('js')
    {!! $table->scripts() !!}

    <script>
        $(function() {
            $('#date').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - '
                },
                autoUpdateInput: false
            }, function (startDate, endDate) {
                $('#date').val(startDate.format('DD/MM/YYYY') + ' - ' + endDate.format('DD/MM/YYYY'));
            });
            let table = $('#analytics-table');

            table.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    date: $('#date').val(),
                    course:$('#course').val(),
                    level:$('#level').val(),
                    type:$('#select-type').val(),
                    subject:$('#subject').val(),
                    chapter:$('#select-chapter').val(),
                    language:$('#language').val()
                }
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#date').val('');
                $('#course').val('').change();
                $('#level').val('').change();
                $('#select-type').val('').change();
                $('#subject').val('').change();
                $('#select-chapter').val('').change();
                $('#language').val('').change();
                table.DataTable().draw();
            });

            // $('#log_s_date').datepicker({
            //     // format: 'dd-mm-yyyy',
            //     // autoclose: true 
            // });

            // $('#log_e_date').datepicker({
            //     // format: 'dd-mm-yyyy',
            //     // greaterThan: "#log_s_date" ,
            //     // autoclose: true
            // });

            $('#select-chapter').select2({
                placeholder: 'Chapter'
            });

            $('#select-type').select2({
                placeholder: 'Type'
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
            $('#language').select2({
                placeholder: 'Language'
            });
    
        });
    </script>
     <script>
        $('#course').on('change', function () {
            var CourseID = $(this).val();

            if (CourseID) {
                $.ajax({
                    url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#level').empty();
                        $('#level').append('<option disabled selected>  Choose Level </option>');
                        $.each(data, function (key, value) {
                            $('#level').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#level').empty();
            }
        });

        $('#level').on('change', function () {
            var levelID = $(this).val();
            LevelSubjects(levelID);
        });

        function LevelSubjects(levelID) {
            if (levelID) {
                $.ajax({
                    url: '{{ url('/level-subjects/ajax') }}' + '/' + levelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#subject').empty();
                        $('#subject').append('<option disabled selected>  Choose Subject </option>');
                        $.each(data, function (key, value) {
                            $('#subject').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }
                });
            } else {
                $('#subject').empty();
            }
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
                        $('#select-chapter').empty();
                        $('#select-chapter').append('<option disabled selected>  Choose Chapter </option>');
                        $.each(data, function (key, value) {
                            $('#select-chapter').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }
                });
            } else {
                $('#select-chapter').empty();
            }
        }

        
        $('#log_s_date').change(function(){
            document.getElementById('log_e_date').min = $('#log_s_date').val();
        });

        // $('#log_e_date').change(function(){
        //     document.getElementById('log_s_date').max = $('#log_e_date').val();
        // });
       
</script>
@endpush
