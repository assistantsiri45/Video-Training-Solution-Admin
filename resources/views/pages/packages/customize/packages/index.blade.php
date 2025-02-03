@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <div class="row">
        <div class="col">
            <div class="mb-2">
                <a class="btn btn-primary" href="{{ url('packages', $package->id) }}"><i class="fas fa-chevron-circle-left"></i> Back</a>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('packages',$package->id)}}">{{$package->name}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Packages</li>
                </ol>
            </nav>
        </div>
        <div class="col">
            <form method="POST" id ="form_pkg" action="{{ url("packages/$package->id/all-packages") }}">
                @csrf
                <input type="hidden" id="selected-package-ids" name="selected_package_ids">
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
                        <div class="col-md-2">
                            <input class="form-control" id="search" type="text" placeholder="Search" title="Search">
                        </div>
                        <div class="col-md-2">
                            <select id="select-course" class="form-control">
                                <option value=""></option>
                                @foreach (\App\Models\Course::all() as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="select-level" class="form-control">
                                <!-- <option value=""></option>
                                @foreach (\App\Models\Level::all() as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="select-package-type" class="form-control">
                                <!-- <option value=""></option>
                                 @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach --> 
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="select-subject" class="form-control">
                                <!-- <option value=""></option>
                                @foreach (\App\Models\Subject::all() as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="select-chapter" class="form-control">
                                <!-- <option value=""></option>
                                @foreach (\App\Models\Chapter::all() as $chapter)
                                    <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                @endforeach -->
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-2">
                            <select id="select-language" class="form-control">
                                <option value=""></option>
                                @foreach (\App\Models\Language::all() as $language)
                                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="select-professor" class="form-control">
                                <option value=""></option>
                                @foreach (\App\Models\Professor::all() as $professor)
                                    <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="select-type" class="form-control">
                                <option value=""></option>
                                <option value="1">Chapter Level</option>
                                <option value="2">Subject Level</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" id="button-search">Search</button>
                            <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                        </div>
                    </div>
                    <div class="card-body">
                        {!! $html->table(['id' => 'tbl-packages'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


@section('js')
    {!! $html->scripts() !!}

    <script>
        let checkedPackages = JSON.parse('{!! json_encode($selectedPackageIDs) !!}');
        $('#selected-package-ids').val(JSON.stringify(checkedPackages));

        function renderCheckbox(data) {
            return `<div class="custom-control custom-checkbox text-center">
                        <input class="custom-control-input select-package" type="checkbox" id="checkbox-package-${data}" name="packages[]" value="${data}" ${ checkedPackages.includes(parseInt(data)) ? 'checked' : '' }>
                        <label for="checkbox-package-${data}" class="custom-control-label"></label>
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

                $('#selected-package-ids').val(JSON.stringify(checkedPackages));
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
                    professor: $('#select-professor').val(),
                    type: $('#select-type').val(),
                    package_type: $('#select-package-type').val(),
                    
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
                $('#select-type').val('').change();
                $('#select-package-type').val('').change();
                table.draw();
            });

            $('#select-course').select2({
                placeholder: 'Course'
            });

            $('#select-level').select2({
                placeholder: 'Level'
            });

            $('#select-subject').select2({
                placeholder: 'Subject'
            });

            $('#select-chapter').select2({
                placeholder: 'Chapter'
            });

            $('#select-language').select2({
                placeholder: 'Language'
            });

            $('#select-professor').select2({
                placeholder: 'Professor'
            });

            $('#select-type').select2({
                placeholder: 'Type'
            });
            $('#select-package-type').select2({
                placeholder: 'Package Type'
            });
        });
        $('#form_pkg').on('submit', function(e){

var stds = $('tbody input[type="checkbox"]:checked').length;
if(stds <= 0){

alert('Please select atleast one video');
e.preventDefault();
}
});
    </script>
       <script>
         $('#select-course').on('change', function () {
            var CourseID = $(this).val();
            $('#select-level').empty();
            $('#select-package-type').empty();
            $('#select-subject').empty();
            $('#select-chapter').empty();
            if (CourseID) {
                $.ajax({
                    url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    //    $('#select-level').empty();
                        $('#select-level').append('<option disabled selected>  Choose Level </option>');
                        $.each(data, function (key, value) {
                            $('#select-level').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
            //    $('#select-level').empty();
            }
        });

        var package_type;
        $('#select-level').on('change', function () {
            $('#select-package-type').empty();
            $('#select-subject').empty();
            $('#select-chapter').empty();
            var LevelID = $(this).val();
            if (LevelID) {
            $.ajax({
                url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                type: "GET",
                dataType: "json",
                success: function (data) {
                //    $('#select-type').empty();
                    $('#select-package-type').append('<option disabled selected>  Choose Type </option>');
                    $.each(data, function (key, value) {
                        if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#select-package-type').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                        }
                     
                    });
                    getSubject(package_type,LevelID);
                }
            });
              

            } else {
            //    $('#select-type').empty();
            }
        });

        $('#select-package-type').on('change', function () {
                $('#select-subject').empty();
                $('#select-chapter').empty();
                var package_type = $(this).val();
                var level_id=$("#select-level").val();
                if(package_type && level_id){
                    getSubject(package_type,level_id);
                }
        });

        $('#select-subject').on('change', function () {
            $('#select-chapter').empty();
            var SubjectID = $(this).val();
            SubjectChapters(SubjectID);
        //    SubjectProfessors(SubjectID);
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
                 
                //   $('#select-subject').empty();
                   if(response.length>0){
                        $('#select-subject').append('<option disabled selected>  Choose Subject </option>');
                        $.each(response, function( index, value ) {
                           var item = value.id;
                           $('#select-subject').append('<option value="' + value.id + '">' + value.name + '</option>');

                        });    
                   }
                   else{}
            });
        }

        function SubjectChapters(SubjectID) {
            if (SubjectID) {
                $.ajax({
                    url: '{{ url('/subject-chapters/ajax') }}' + '/' + SubjectID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    //    $('#select-chapter').empty();
                        $('#select-chapter').append('<option disabled selected>  Choose Chapter </option>');
                        $.each(data, function (key, value) {
                            $('#select-chapter').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }
                });
            } else {
            //    $('#select-chapter').empty();
            }
        }

    </script>
@stop

