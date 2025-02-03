@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
<style>
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 3% !important;
    }
</style>
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">All Packages</h1> 
        </div>
        <div class="col text-right">
           
            <button id="btn-add-to-archeive" class="btn btn-primary">Add To Archieve</button>
         
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
                                <select class="form-control" id="type">
                                    <option value=""></option>
                                    <option value="{{ \App\Models\Package::TYPE_CHAPTER_LEVEL }}">{{ \App\Models\Package::TYPE_CHAPTER_LEVEL_VALUE }}</option>
                                    <option value="{{ \App\Models\Package::TYPE_SUBJECT_LEVEL }}">{{ \App\Models\Package::TYPE_SUBJECT_LEVEL_VALUE }}</option>
                                    <option value="{{ \App\Models\Package::TYPE_CUSTOMIZED }}">{{ \App\Models\Package::TYPE_CUSTOMIZED_VALUE }}</option>
                                </select>
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
                                        </div>
                        <div class="row">
                            <div class="col-md-2 mt-2">
                                <select class="form-control" id="subject">
                                    <option value=""></option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mt-2">
                                <button class="btn btn-primary" id="button-search">Search</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                    {!! $html->table(['id' => 'tbl-packages'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="form-add-to-archive" method="POST">
        @csrf
    </form>


@stop

@section('js')

    <script>
        $(function () {
            var selected = [];
            if(selected.length<=0){
                $("#btn-add-to-archeive").attr('disabled', true);
            }
            else {
                $("#btn-add-to-archeive").attr('disabled', false);
            }

            $('#course').on('change', function () {
                $('#level').empty();
                $('#package_type').empty();
                $('#subject').empty();
                var CourseID = $(this).val();

                if (CourseID) {
                    $.ajax({
                        url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                        //    $('#level').empty();
                            $('#level').append('<option disabled selected>  Choose Level </option>');
                            $.each(data, function (key, value) {
                                $('#level').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                        }
                    });
                } else {
                //    $('#level').empty();
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
                    //    $('#package_type').empty();
                        $('#package_type').append('<option disabled selected>  Choose Type </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#package_type').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                        });
                        $('#package_type').append('<option value="is_freemium">Freemium Package</option>');

                        getSubject(package_type,LevelID);
                    }
                });
              

                } else {
                //    $('#package_type').empty();
                }
            });

            $('#package_type').on('change', function () {
                $('#subject').empty();
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
                  
                //    $('#subject').empty();
                    if(response.length>0){
                        $('#subject').append('<option disabled selected>  Choose Subject </option>');
                       
                        $.each(response, function( index, value ) {
                            var item = value.id;
                           
                           
                            $('#subject').append('<option value="' + value.id + '">' + value.name + '</option>');

                        });
                        // $("#no_subjects_available").addClass('d-none');
                    }
                    else{
                        // if(subjectsArray.length==0){
                            // $("#subject-container").empty();
                            // $("#no_levelt_selected").addClass('d-none');
                            // $("#no_subjects_available").removeClass('d-none');
                        // }
                    }

                });
            }
            $("#tbl-packages").on('click', '.add-to-archived', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let isConfirmed = confirm('Add to archive?');

                if (isConfirmed) {
                    $('#form-add-to-archive').attr('action', url).submit();
                }
            });

            $("#tbl-packages").on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    type: $('#type').val(),
                    language: $('#language').val(),
                    course: $('#course').val(),
                    level: $('#level').val(),
                    subject: $('#subject').val(),
                    package_type:$('#package_type').val(),
                }
            });

        });
    </script>

    {!! $html->scripts() !!}

    <script>
        $(document).ready(function () {
            $('#tbl-packages').on('click', 'thead input[name="select_all"]',function(e){
               
               $("#btn-add-to-archeive").attr('disabled', false);
               var $table = $(this).closest('table');

               if(this.checked){
                   $table.find('tbody input[type="checkbox"]:not(:checked)').trigger('click');
               } else {
                   $table.find('tbody input[type="checkbox"]:checked').trigger('click');
               }

               // Prevent click event from propagating to parent
               e.stopPropagation();
           });
            var selected_unpublished_videos = [];




            $('#tbl-packages').on('click', 'tbody input[type="checkbox"]', function(e){
               var $row = $(this).closest('tr');
               var $table = $(this).closest('table');

               // Get row data
               let table = $table.DataTable();
               var data = table.row($row).data();

               // Get row ID
               var rowId = data.id;

               if ($table.is('#tbl-packages')) {
                   selected = selected_unpublished_videos;
               } 

               // Determine whether row ID is in the list of selected row IDs
               var index = $.inArray(rowId, selected);

               // If checkbox is checked and row ID is not in list of selected row IDs
               if(this.checked && index === -1) {
                   selected.push(rowId);

                   // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
               } else if (!this.checked && index !== -1){
                   selected.splice(index, 1);
               }
               if(selected.length >=1){
                   $("#btn-add-to-archeive").attr('disabled', false);
               }
               else {
                   $("#btn-add-to-archeive").attr('disabled', true);
               }
               if(this.checked){
                   $row.addClass('selected');
               } else {
                   $row.removeClass('selected');
               }

               // Update state of "Select all" control
               updateDataTableSelectAllCtrl(table);

               // Prevent click event from propagating to parent
               e.stopPropagation();
               console.log(selected);
           });
           $("#btn-add-to-archeive").click(function (){
               let confirm = window.confirm('Add to archeive?');
               console.log(selected)
               if (confirm) {
                   $.ajax({
                       url: '{{ url('package-add-to-archeive') }}',
                       type: 'POST',
                       data: {
                           "selectedVideoIds": selected,
                       }
                   }).done(function (response) {
                       toastr.success('Added to archeive');
                     
                     
                       location.reload();
                   });
               }
           });         
            $("#tbl-packages").on('click', '.btn-delete', function (e) {
                e.preventDefault();
                e.stopPropagation();

                let confirmation = confirm("Delete this item?");
                let url = $(this).attr('href');
                let table = $('#tbl-packages');

                if (confirmation) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        success: function(result) {
                            if (result) {
                                toastr.success(result.message);
                                table.DataTable().draw();
                            }
                        }
                    });
                }
            });
            let table = $('#tbl-packages');
            table.DataTable().draw();

            $('.buttons-csv').remove();
            $('.buttons-pdf').remove();

            $('#type').select2({
                placeholder: 'Type'
            });

            $('#language').select2({
                placeholder: 'Language'
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
            $('#package_type').select2({
                placeholder: 'Package Type'
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#type').val('').change();
                $('#language').val('').change();
                $('#course').val('').change();
                $('#level').val('').change();
                $('#subject').val('').change();
                $('#package_type').val('').change();

                table.DataTable().draw();
            });
        });
        function disap(){
	  
      var len=$('input[name="packages[]"]:checked').length;
     
    
   if(len > 0){
    $("#btn-add-to-archeive").attr('disabled', false);
      }else{
        $("#btn-add-to-archeive").attr('disabled', true);
      }
        
        
    }
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
    </script>
@stop

