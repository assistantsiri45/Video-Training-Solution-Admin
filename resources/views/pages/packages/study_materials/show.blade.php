@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <div class="row">
        <div class="col">
            <div class="mb-2">
                <a class="btn btn-primary" href="{{ url('packages', $package->id) }}"><i class="fas fa-chevron-circle-left"></i> Back</a>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-arrow">
                    <li class="breadcrumb-item"><a href="{{url('packages',$package->id)}}">{{$package->name}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Study Material</li>
                </ol>
            </nav>
        </div>
        <div class="col">
            <form method="POST" action="{{ url("packages/$package->id/study-materials") }}">
                @csrf
                <input type="hidden" id="study-material-ids" name="study_material_ids">
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

                        <div class="row">
                            <div class="col text-left">
                                <h3>Study Materials</h3>
                            </div>
                        </div>
                        <div class="card-header">
                            <form id="search-form">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <input id="text-search" type="text" class="form-control" placeholder="Search Title">
                                    </div>
                                    <div class="col-sm-2">
                                        <select id="select-type" style="width: 100%">
                                            <option value=""></option>
                                            <option value="{{ \App\Models\StudyMaterialV1::STUDY_MATERIALS }}">{{ \App\Models\StudyMaterialV1::STUDY_MATERIALS_TEXT }}</option>
                                            <option value="{{ \App\Models\StudyMaterialV1::STUDY_PLAN }}">{{ \App\Models\StudyMaterialV1::STUDY_PLAN_TEXT }}</option>
                                            <option value="{{ \App\Models\StudyMaterialV1::TEST_PAPER }}">{{ \App\Models\StudyMaterialV1::TEST_PAPER_TEXT }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select id="select-language" style="width: 100%">
                                            <option value=""></option>
                                            @foreach (\App\Models\Language::all() as $language)
                                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <x-inputs.professor id="select-professor" class="{{ $errors->has('professor_id') ? ' is-invalid' : '' }}" style="width: 100%;">
                                                @if(!empty(old('professor_id')))
                                                    <option value="{{ old('professor_id') }}" selected>{{ old('professor_id_text') }}</option>
                                                @endif
                                            </x-inputs.professor>

                                            @if ($errors->has('professor_id'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('professor_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <select id="select-package-type" style="width: 100%">
                                            <option value=""></option>
                                            @foreach ($package_types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" id="button-filter" class="btn btn-primary">Filter</button>
                                        <button type="button" class="btn btn-primary ml-2" id="button-clear">Clear</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                    <div class="card-body">
                        {!! $html->table(['id' => 'table-study-materials'], true) !!}
                    </div>
            </div>
        </div>
    </div>

@stop


@section('js')
    {!! $html->scripts() !!}
    <script>
        let checkedStudyMaterials = JSON.parse('{!! json_encode($studyMaterialsIDs) !!}');

        $('#study-material-ids').val(JSON.stringify(checkedStudyMaterials));

        function renderStudyMaterialsCheckbox(data) {
            return `<div class="custom-control custom-checkbox text-center">
                        <input class="custom-control-input select-study-material" type="checkbox"
                            id="select-study-material-${data}" name="study_material[]" value="${data}" ${ checkedStudyMaterials.includes(parseInt(data)) ? 'checked' : '' }>
                        <label for="select-study-material-${data}" class="custom-control-label"></label>
                    </div>`;
        }

        $(function() {
            let tableStudyMaterials = $('#table-study-materials').DataTable();

            tableStudyMaterials.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#text-search').val(),
                    type: $('#select-type').val(),
                    language: $('#select-language').val(),
                    professor: $('#select-professor').val(),
                    package_type: $('#select-package-type').val(),
                }
            });

            $('#button-filter').click(function (e) {
                e.preventDefault();
                tableStudyMaterials.draw();
            });

            $('#button-clear').click(function() {
                $('#text-search').val('');
                $('#select-type').val("").trigger('change');
                $('#select-language').val("").trigger('change');
                $('#select-professor').val("").trigger('change');
                $('#select-package-type').val("").trigger('change');
                tableStudyMaterials.draw();
            });


            $('#select-type').select2({
                placeholder: 'Choose Type'
            });

            $('#select-language').select2({
                placeholder: 'Choose Language'
            });

            $('#select-package-type').select2({
                placeholder: 'Choose Package Type'
            })

            $('#table-study-materials').on('click', '.select-study-material', function() {
                let id = parseInt($(this).val());

                if ($(this).is(':checked')) {
                    checkedStudyMaterials.push(id);
                } else {
                    let index = checkedStudyMaterials.indexOf(id);

                    if (index >= 0) {
                        checkedStudyMaterials.splice(index, 1);
                    }
                }

                $('#study-material-ids').val(JSON.stringify(checkedStudyMaterials));
            });

            $('#table-study-materials').on('click', 'thead input[name="select_all_study_materials"]',function(e){
                var $table = $(this).closest('table');

                if(this.checked){
                    $table.find('tbody input[type="checkbox"]:not(:checked)').trigger('click');
                } else {
                    $table.find('tbody input[type="checkbox"]:checked').trigger('click');
                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            $('#table-study-materials').on('click', 'tbody input[type="checkbox"]', function(e){
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
                updateStudyMaterialDataTableSelectAllCtrl(table);

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            $('#table-study-materials').on('draw.dt', function(e){
                var $row = $(this).closest('tr');
                var $table = $(this);

                // Get row data
                let table = $table.DataTable();

                // Update state of "Select all" control
                updateStudyMaterialDataTableSelectAllCtrl(table);
            });

            function updateStudyMaterialDataTableSelectAllCtrl(table){
                var $table             = table.table().node();
                var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
                var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
                var chkbox_select_all  = $('thead input[name="select_all_study_materials"]', $table).get(0);

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
        });
    </script>
@stop

