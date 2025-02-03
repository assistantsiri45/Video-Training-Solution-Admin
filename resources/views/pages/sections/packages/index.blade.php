@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <div class="row">
        <div class="col">
            <div class="mb-2">
                <a class="btn btn-primary" href="{{ url('sections', $section->id) }}"><i class="fas fa-chevron-circle-left"></i> Back</a>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('sections',$section->id)}}">{{$section->name}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Packages</li>
                </ol>
            </nav>
        </div>
        <div class="col">
            <form method="POST" action="{{ url("sections/$section->id/section-packages") }}">
                @csrf
                <input type="hidden" id="package-ids" name="package_ids">
                <button class="btn btn-success float-right">Save</button>
            </form>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <ul class="nav nav-tabs" id="tbl-packages-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-status="selected-packages" id="tab-selected-packages"  data-target="#selected-packages" data-toggle="pill" href="#">Selected Packages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-status="all-packages" id="tab-all-packages" data-target="#all-packages" data-toggle="pill" href="#">All Packages</a>
                        </li>

                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="selected-packages" role="tabpanel" aria-labelledby="selected-packages-tab">

                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input class="form-control" id="search-selected-package" type="text" placeholder="Search" title="Search">
                                    </div>
                                    <div class="col-md-2">
                                        <select id="select-course-selected-package" class="form-control select-course">
                                            <option value=""></option>
                                            @foreach (\App\Models\Course::all() as $course)
                                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select id="select-level-selected-package" class="form-control select-level">
                                            <option value=""></option>
                                            @foreach (\App\Models\Level::all() as $level)
                                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select id="select-subject-selected-package" class="form-control select-subject">
                                            <option value=""></option>
                                            @foreach (\App\Models\Subject::all() as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select id="select-chapter-selected-package" class="form-control select-chapter">
                                            <option value=""></option>
                                            @foreach (\App\Models\Chapter::all() as $chapter)
                                                <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-2">
                                        <select id="select-language-selected-package" class="form-control select-language">
                                            <option value=""></option>
                                            @foreach (\App\Models\Language::all() as $language)
                                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select id="select-professor-selected-package" class="form-control select-professor">
                                            <option value=""></option>
                                            @foreach (\App\Models\Professor::all() as $professor)
                                                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary" id="button-search-selected-package">Search</button>
                                        <button class="btn btn-primary ml-2" id="button-clear-selected-package">Clear</button>
                                    </div>

                                    <div class="col float-right">
                                        <form method="POST" action="{{ url("sections/$section->id/destroy-selected-packages") }}">
                                            @csrf
                                            <input type="hidden" id="selected-package-ids" name="selected_package_ids">
                                            <button class="btn btn-danger float-right">Delete Selected Packages</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {!! $sectionPackages->table(['id' => 'section-packages']) !!}
                        </div>
                        <div class="tab-pane fade" id="all-packages" role="tabpanel" aria-labelledby="all-packages-tab">
                            <div class="card-header">
                                <div class="row">
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
                                        <select id="select-level" class="form-control select-level" style="width: 100% !important;">
                                            <option value=""></option>
                                            @foreach (\App\Models\Level::all() as $level)
                                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                                            @endforeach
                                        </select>
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
                                {!! $html->table() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


@section('js')
    {!! $html->scripts() !!}
    {!! $sectionPackages->scripts() !!}

    <script>
            let checkedPackages = JSON.parse('{!! json_encode($sectionPackageIDs) !!}');
            $('#package-ids').val(JSON.stringify(checkedPackages));

            function renderCheckbox(data) {
                return `<div class="custom-control custom-checkbox text-center">
                        <input class="custom-control-input select-package" type="checkbox" id="checkbox-package-${data}" name="packages[]" value="${data}" ${ checkedPackages.includes(parseInt(data)) ? 'checked' : '' }>
                        <label for="checkbox-package-${data}" class="custom-control-label"></label>
                    </div>`;
            }

            function renderSelectedPackageCheckbox(data) {
                return `<div class="custom-control custom-checkbox text-center">
                        <input class="custom-control-input select-section-package" type="checkbox" id="checkbox-selected-package-${data}" name="selectedPackages[]" value="${data}" >
                        <label for="checkbox-selected-package-${data}" class="custom-control-label"></label>
                    </div>`;
            }

            $(function () {

            $('#tbl-packages').on('click', '.select-package', function() {
                let id = parseInt($(this).val());

                if ($(this).is(':checked')) {
                    checkedPackages.push(id);
                } else {
                    let index = checkedPackages.indexOf(id);

                    if (index >= 0) {
                        checkedPackages.splice(index, 1);
                    }
                }

                $('#package-ids').val(JSON.stringify(checkedPackages));
            });

            // Handle click on "Select all" control
            $('#tbl-packages').on('click', 'thead input[name="select_all"]',function(e){
                var $table = $(this).closest('table');

                if(this.checked){
                    $table.find('tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $table.find('tbody input[type="checkbox"]:checked').trigger('click');
                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            $('#tbl-packages').on('click', 'tbody input[type="checkbox"]', function(e){
                var $row = $(this).closest('tr');
                var $table = $(this).closest('table');

                // Get row data
                let table = $table.DataTable();

                if(this.checked){
                    $row.addClass('selected');
                } else {
                    $row.removeClass('selected');
                }

                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(table);

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            $('#tbl-packages').on('draw.dt', function(e){
                var $row = $(this).closest('tr');
                var $table = $(this);

                // Get row data
                let table = $table.DataTable();

                // Update state of "Select all" control
                updateDataTableSelectAllCtrl(table);
            });

            function updateDataTableSelectAllCtrl(table){
                var $table             = table.table().node();
                var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
                var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
                var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

                // If none of the checkboxes are checked
                if($chkbox_checked.length === 0){
                    chkbox_select_all.checked = false;
                    if('indeterminate' in chkbox_select_all){
                        chkbox_select_all.indeterminate = false;
                    }

                    // If all of the checkboxes are checked
                } else if ($chkbox_checked.length === $chkbox_all.length){
                    chkbox_select_all.checked = true;
                    if('indeterminate' in chkbox_select_all){
                        chkbox_select_all.indeterminate = false;
                    }

                    // If some of the checkboxes are checked
                } else {
                    chkbox_select_all.checked = true;
                    if('indeterminate' in chkbox_select_all){
                        chkbox_select_all.indeterminate = true;
                    }
                }
            }

            let table = $('#tbl-packages').DataTable();

            table.on('preXhr.dt', function( e, settings, data) {
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


                let tablePackages = $('#section-packages').DataTable();
                tablePackages.on('preXhr.dt', function (e, settings, data) {
                    data.filter = {
                        search: $('#search-selected-package').val(),
                        course: $('#select-course-selected-package').val(),
                        level: $('#select-level-selected-package').val(),
                        subject: $('#select-subject-selected-package').val(),
                        chapter: $('#select-chapter-selected-package').val(),
                        language: $('#select-language-selected-package').val(),
                        professor: $('#select-professor-selected-package').val()
                    }
                });

                $('#button-search-selected-package').click(function (e) {
                    e.preventDefault();
                    tablePackages.draw();
                });

                $('#button-clear-selected-package').click(function () {
                    $('#search-selected-package').val('');
                    $('#select-course-selected-package').val('').change();
                    $('#select-level-selected-package').val('').change();
                    $('#select-subject-selected-package').val('').change();
                    $('#select-chapter-selected-package').val('').change();
                    $('#select-language-selected-package').val('').change();
                    $('#select-professor-selected-package').val('').change();
                    tablePackages.draw();
                });

                let checkedSelectedPackages = [];

                $('#section-packages').on('click', '.select-section-package', function() {
                    // alert('selected');
                    let id = parseInt($(this).val());

                    if ($(this).is(':checked')) {
                        checkedSelectedPackages.push(id);
                    } else {
                        let index = checkedSelectedPackages.indexOf(id);

                        if (index >= 0) {
                            checkedSelectedPackages.splice(index, 1);
                        }
                    }

                    $('#selected-package-ids').val(JSON.stringify(checkedSelectedPackages));
                });

                $('#section-packages').on('click', 'thead input[name="select_all_selected_packages"]',function(e){
                    var $table = $(this).closest('table');

                    if(this.checked){
                        $table.find('tbody input[type="checkbox"]:not(:checked)').trigger('click');
                    } else {
                        $table.find('tbody input[type="checkbox"]:checked').trigger('click');
                    }

                    // Prevent click event from propagating to parent
                    e.stopPropagation();
                });
        });
    </script>
@stop

