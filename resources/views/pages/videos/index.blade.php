@extends('adminlte::page')

@section('title', 'Videos')

@section('content_header')
<style>
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 3% !important;
    }
</style>
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Videos</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('s3-videos.create') }}" type="button" class="btn btn-success">S3 Upload Video</a>
            <!--
                <a href="{{ route('videos.create') }}" type="button" class="btn btn-success">Upload Video</a>
            -->
            <button id="btn-add-to-archeive" class="btn btn-primary">Add To Archieve</button>
            {{--            <a href="{{ url('sync-videos') }}" type="button" class="btn btn-info">Sync Videos</a>--}}
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-status="unpublished"   data-toggle="tab" href="#tab-unpublished">Draft</a></li>
                        <li><a class="nav-link" data-status="published"   data-toggle="tab" href="#tab-published">Published</a></li>
                        <li><a class="nav-link" data-status="archived" data-toggle="tab" href="#tab-archived">Archived</a></li>
                        {{--                        <li><a class="nav-link" data-status="studio-upload" data-toggle="tab" href="#tab-studio-upload">Studio Upload</a></li>--}}
                    </ul>

                    <div class="tab-content">
                        <div id="tab-unpublished" class="tab-pane active">
                            <div class="col-md-12">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Filter</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select id="select-professor" class="form-control">
                                                    <option></option>
                                                    @foreach(\App\Models\Professor::all() as $professor)
                                                        <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
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
                                        <select class="form-control" id="package_type_unpublished" name="package_type" style="width: 100%">
                                        <option value="">Choose Type</option>
                                        
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
                                        <div class="col-sm-4">
                                            <label></label>
                                            <button id="btn-filter" class="btn btn-primary">Filter</button>
                                            <label></label>
                                            <button id="btn-clear" class="btn btn-primary">Clear</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    {!! $unpublishedVideos->table(['id' => 'tbl-unpublished-videos'], true) !!}
                                </div>
                            </div>
                        </div>
                        <div id="tab-published" class="tab-pane fade">
                            <div class="col-md-12">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Filter</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select id="select-professor-published" class="form-control" style="width: 100%">
                                                    <option></option>
                                                    @foreach(\App\Models\Professor::all() as $professor)
                                                        <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select class="form-control" id="course-published" style="width: 100%">
                                                    <option value=""></option>
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="level_id" id="level-published"  class="form-control " style="width: 100% !important;">
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="package_type_published" name="package_type" style="width: 100%">
                                            <option value="">Choose Type</option>                                            
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="subject-published" style="width: 100% !important;">

                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select id="select-chapter-published" class="form-control" style="width: 100%">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label></label>
                                            <button id="btn-filter-published" class="btn btn-primary">Filter</button>
                                            <button id="btn-clear-published" class="btn btn-primary">Clear</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    {!! $publishedVideos->table(['id' => 'tbl-published-videos'], true) !!}
                                </div>
                            </div>
                        </div>
                        <div id="tab-archived" class="tab-pane fade">
                            <div class="col-md-12">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Filter</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select id="select-professor-archived" class="form-control" style="width: 100%">
                                                    <option></option>
                                                    @foreach(\App\Models\Professor::all() as $professor)
                                                        <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <select class="form-control" name="course_id" id="course-archived" style="width: 100% !important;">
                                                <option value=""></option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <select name="level_id" id="level-archived"  class="form-control select-level" style="width: 100% !important;">
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                        <select class="form-control" id="package_type_archived" name="package_type" style="width: 100%">
                                        <option value="">Choose Type</option>
                                        
                                        </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <select class="form-control" id="subject-archived" style="width: 100% !important;">
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select id="select-chapter-archived" class="form-control" style="width: 100%">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label></label>
                                            <button id="btn-filter-archived" class="btn btn-primary">Filter</button>
                                            <button id="btn-clear-archived" class="btn btn-primary">Clear</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    {!! $archivedVideos->table() !!}
                                </div>
                            </div>
                        </div>
                        {{--                        <div id="tab-studio-upload" class="tab-pane fade">--}}
                        {{--                            <div class="col-md-12">--}}
                        {{--                                {!! $tableStudioUploadVideos->table(['id' => 'tbl-studio'], true) !!}--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-publish">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Publish</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Type <strong>publish</strong> to continue</p>
                    <div class="form-group">
                        <input type="hidden" id="confirmation-url">
                        <input type="text" class="form-control" id="publish-confirmation" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-publish">Publish</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-un-publish">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Un-Publish</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Type <strong>un-publish</strong> to continue</p>
                    <div class="form-group">
                        <input type="hidden" id="confirmation-url">
                        <input type="text" class="form-control" id="un-publish-confirmation" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-un-publish">Un-Publish</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-change-video" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <form id="form-change-video" method="POST" {{--action="{{ route('videos.change') }}"--}}
                onkeydown="return event.key != 'Enter';">
                    @csrf
                    <input id="video-id" name="video_id" type="hidden">
                    <div class="modal-header">
                        <h4 class="modal-title">Change Media ID</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="media-id">Media ID</label>
                                    <input class="form-control" id="media-id" name="media_id" placeholder="Media ID" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary save-media-id">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <form id="form-add-to-archive" method="POST">
        @csrf
    </form>
    <form id="form-remove-from-archive" method="POST">
        @csrf
    </form>
@stop

@section('js')
    {{--    {!! $tableStudioUploadVideos->scripts() !!}--}}
    {!! $publishedVideos->scripts() !!}
    {!! $unpublishedVideos->scripts() !!}
    {!! $archivedVideos->scripts() !!}

    <script>
        $('#course').on('change', function () {
            var CourseID = $(this).val();
            $('#level').empty();
            $('#package_type_unpublished').empty();
            $('#subject').empty();
            $('#select-chapter').empty();
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
            $('#package_type_unpublished').empty();
            $('#subject').empty();
            $('#select-chapter').empty();
            var LevelID = $(this).val();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#package_type_unpublished').append('<option disabled selected>  Choose Type </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#package_type_unpublished').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                     
                        });
                        getSubject(package_type,LevelID);
                    }
                });
              

                } else {
                }
        });
        $('#package_type_unpublished').on('change', function () {
                var package_type = $(this).val();
                var level_id=$("#level").val();
                $('#subject').empty();
                $('#select-chapter').empty();
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
            $('#select-chapter').empty();
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
                        $('#select-chapter').append('<option disabled selected>  Choose Chapter </option>');
                        $.each(data, function (key, value) {
                            $('#select-chapter').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }
                });
            } else {
            }
        }

        $('#course-published').on('change', function () {
            var CourseID = $(this).val();
            $('#level-published').empty();
            $('#package_type_published').empty();
            $('#subject-published').empty();
            $('#select-chapter-published').empty();
            if (CourseID) {
                $.ajax({
                    url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#level-published').append('<option disabled selected>  Choose Level </option>');
                        $.each(data, function (key, value) {
                            $('#level-published').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
            }
        });
        $('#level-published').on('change', function () {
            $('#package_type_published').empty();
            $('#subject-published').empty();
            $('#select-chapter-published').empty();
            var LevelID = $(this).val();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#package_type_published').append('<option disabled selected>  Choose Type </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#package_type_published').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                     
                        });
                        PublishedGetSubject(package_type,LevelID);
                    }
                });             

                } else {
                    $('#package_type_published').empty();
                }
        });    
        $('#package_type_published').on('change', function () {
                $('#subject-published').empty();
                $('#select-chapter-published').empty();
                var package_type = $(this).val();
                var level_id=$("#level-published").val();
                if(package_type && level_id){
                    PublishedGetSubject(package_type,level_id);
                }
            });
            function PublishedGetSubject(package_type,level_id){               
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
                   if(response.length>0){
                       $('#subject-published').append('<option disabled selected>  Choose Subject </option>');                      
                       $.each(response, function( index, value ) {
                           var item = value.id;                  
                          $('#subject-published').append('<option value="' + value.id + '">' + value.name + '</option>');
                       });                       
                   }
                   else{
                   }
               });
           }
        $('#subject-published').on('change', function () {
            var SubjectID = $(this).val();
            PublisedSubjectChapters(SubjectID);
        });

        function PublisedSubjectChapters(SubjectID) {
            if (SubjectID) {
                $.ajax({
                    url: '{{ url('/subject-chapters/ajax') }}' + '/' + SubjectID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#select-chapter-published').append('<option disabled selected>  Choose Chapter </option>');
                        $.each(data, function (key, value) {
                            $('#select-chapter-published').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }
                });
            } else {
            }
        }

        $('#course-archived').on('change', function () {
            var CourseID = $(this).val();
            $('#level-archived').empty();
            $('#package_type_archived').empty();
            $('#subject-archived').empty();
            $('#select-chapter-archived').empty();
            if (CourseID) {
                $.ajax({
                    url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#level-archived').append('<option disabled selected>  Choose Level </option>');
                        $.each(data, function (key, value) {
                            $('#level-archived').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        var levelID = $('#level-archived').val();
                        ArchivedLevelSubjects(levelID);
                    }
                });
            } else {
            }
        });

        $('#level-archived').on('change', function () {
            $('#package_type_archived').empty();
            $('#subject-archived').empty();
            $('#select-chapter-archived').empty();
            var LevelID = $(this).val();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#package_type_archived').append('<option disabled selected>  Choose Type </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#package_type_archived').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                     
                        });
                        ArchivedGetSubject(package_type,LevelID);
                    }
                });             

                } else {
                }
        });    
        $('#package_type_archived').on('change', function () {
                $('#subject-archived').empty();
                $('#select-chapter-archived').empty();
                var package_type = $(this).val();
                var level_id=$("#level-archived").val();
                if(package_type && level_id){
                    ArchivedGetSubject(package_type,level_id);

                }
            });
            function ArchivedGetSubject(package_type,level_id){               
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
                   if(response.length>0){
                       $('#subject-archived').append('<option disabled selected>  Choose Subject </option>');                      
                       $.each(response, function( index, value ) {
                           var item = value.id;                  
                          $('#subject-archived').append('<option value="' + value.id + '">' + value.name + '</option>');
                       });                       
                   }
                   else{
                   }
               });
           }

        $('#subject-archived').on('change', function () {
            $('#select-chapter-archived').empty();
            var SubjectID = $(this).val();
            ArchivedSubjectChapters(SubjectID);
        });

        function ArchivedSubjectChapters(SubjectID) {
            if (SubjectID) {
                $.ajax({
                    url: '{{ url('/subject-chapters/ajax') }}' + '/' + SubjectID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#select-chapter-archived').append('<option disabled selected>  Choose Chapter </option>');
                        $.each(data, function (key, value) {
                            $('#select-chapter-archived').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }
                });
            } else {
            }
        }

        function renderCheckbox(data, type, row, meta){
            return '<input id="'+data+'" class="check-row" name="users[]" type="checkbox">';
        }


        $(function() {

            var selected = [];

            if(selected.length<=0){
                $("#btn-add-to-archeive").attr('disabled', true);
            }
            else {
                $("#btn-add-to-archeive").attr('disabled', false);
            }

            let table = $('#tbl-unpublished-videos').DataTable();
            $("#tbl-unpublished-videos").on('click', '.btn-publish', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // check if the video is s3 and duration is not updated
                var duration = $(this).data('video-duration');
                var url = $(this).data("video-url");                
                var word = "cloudfront";
                if (url.toLowerCase().includes(word.toLowerCase())) {
                    if (duration == null || duration == false || duration === '' || duration === '0') {
                        alert('Please Preview/Play video once');
                        return false;
                    }
                }

                $('#modal-publish').modal('toggle');

                $('.modal-body #confirmation-url').val($(this).attr('href'));
            });

            // to recieve messages from IZ
            $(window).on('message', function(event) {
                var data = event.originalEvent.data;
                if (data.type === 'Video Duration Updated') {
                    var newDuration = data.duration.toString();
                    var videoId = data.videoId;
                    var $publishButton = $('a.btn-publish[data-video-id="'+ videoId +'"]');
                    $publishButton.attr('data-video-duration', newDuration);
                    let table = $('#tbl-unpublished-videos').DataTable(); 
                    // Refresh DataTables to update the table data
                    table.row($publishButton.closest('tr')).invalidate().draw(false);
                }
            });

            $('#btn-publish').click(function() {
                let confirmation = $('#publish-confirmation');

                if (confirmation.val() === 'publish') {
                    var videos = $('#modal-publish').data('videos');

                    $.ajax({
                        url: $('#confirmation-url').val(),
                        type: 'POST',
                        data: { videos }
                    }).done(function(response) {
                        if (response) {
                            $('#modal-publish').modal('toggle');

                            confirmation.val('');

                            if (response.status === 200) {
                                toastr.success(response.message);
                            }

                            if (response.status === 503) {
                                toastr.error(response.message);
                            }

                            table.draw();
                            selected_published_videos = [];
                            updateDataTableSelectAllCtrl();
                        }
                    });
                } else {
                    confirmation.addClass('is-invalid');
                    $('.form-group').append('<small class="text-danger invalid-confirmation">Invalid Confirmation</small>');
                }

                confirmation.keyup(function() {
                    confirmation.removeClass('is-invalid');
                    $('.invalid-confirmation').remove();
                });
            });

            $("#tbl-published-videos").on('click', '.btn-un-publish', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $('#modal-un-publish').modal('toggle');

                $('.modal-body #confirmation-url').val($(this).attr('href'));
            });

            $('#btn-un-publish').click(function() {
                let confirmation = $('#un-publish-confirmation');

                if (confirmation.val() === 'un-publish') {
                    var videos = $('#modal-un-publish').data('videos');

                    $.ajax({
                        url: $('#confirmation-url').val(),
                        type: 'POST',
                        data: { videos }
                    }).done(function(response) {
                        if (response) {
                            $('#modal-un-publish').modal('toggle');
                            confirmation.val('');
                            toastr.success(response.message);
                            $('#tbl-published-videos').DataTable().draw();
                            $('#tbl-unpublished-videos').DataTable().draw();

                            selected_unpublished_videos = [];
                            updateDataTableSelectAllCtrl();
                        }
                    });
                } else {
                    confirmation.addClass('is-invalid');
                    $('.form-group').append('<small class="text-danger invalid-confirmation">Invalid Confirmation</small>');
                }

                confirmation.keyup(function() {
                    confirmation.removeClass('is-invalid');
                    $('.invalid-confirmation').remove();
                });
            });

            // table.on('preXhr.dt', function(e, settings, data) {
            //     let status = $('#tbl-videos-tab').find('.nav-link.active').first().data('status');
            //
            //     data.filter = {
            //         status: status
            //     }
            // });
            //
            // $('#tbl-videos-tab').on('shown.bs.tab', function(e) {
            //     table.draw();
            // });
            //
            // table.draw();

            $('#select-chapter').select2({
                placeholder: 'Chapter'
            });

            $('#select-professor').select2({
                placeholder: 'Professor'
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
            $('#package_type_unpublished').select2({
                placeholder: 'Type'
            });

            let tableDraft = $('#tbl-unpublished-videos').DataTable();

            tableDraft.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    chapter: $('#select-chapter').val(),
                    professor: $('#select-professor').val(),
                    course: $('#course').val(),
                    level: $('#level').val(),
                    subject: $('#subject').val(),
                    package_type:$('#package_type_unpublished').val(),
                }
            });

            $('#btn-filter').click(function() {
                tableDraft.draw();
            });

            $('#select-chapter-published').select2({
                placeholder: 'Chapter'
            });

            $('#select-professor-published').select2({
                placeholder: 'Professor'
            });
            $('#course-published').select2({
                placeholder: 'Course'
            });

            $('#level-published').select2({
                placeholder: 'Level'
            });
            $('#package_type_published').select2({
                placeholder: 'Type'
            });
            $('#subject-published').select2({
                placeholder: 'Subject'
            });

            $('#select-chapter-archived').select2({
                placeholder: 'Chapter'
            });

            $('#course-archived').select2({
                placeholder: 'Course'
            });

            $('#level-archived').select2({
                placeholder: 'Level'
            });
            $('#subject-archived').select2({
                placeholder: 'Subject'
            });

            $('#select-professor-archived').select2({
                placeholder: 'Professor'
            });
            $('#package_type_archived').select2({
                placeholder: 'Type'
            });

            let tablePublished = $('#tbl-published-videos').DataTable();

            tablePublished.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    chapter: $('#select-chapter-published').val(),
                    professor: $('#select-professor-published').val(),
                    course: $('#course-published').val(),
                    level: $('#level-published').val(),
                    subject: $('#subject-published').val(),
                    package_type:$('#package_type_published').val(),

                }
            });

            $('#btn-filter-published').click(function() {
                tablePublished.draw();
            });

            $('#btn-clear').click(function() {
                $('#select-chapter').val('').trigger('change');
                $('#select-professor').val('').trigger('change');
                $('#course').val('').trigger('change');
                $('#level').val('').trigger('change');
                $('#subject').val('').trigger('change');
                $('#package_type_unpublished').val('').trigger('change');
                tableDraft.draw();
            });

            $('#btn-clear-published').click(function() {
                $('#select-chapter-published').val('').trigger('change');
                $('#select-professor-published').val('').trigger('change');
                $('#course-published').val('').trigger('change');
                $('#level-published').val('').trigger('change');
                $('#subject-published').val('').trigger('change');
                $('#package_type_published').val('').trigger('change');
                tablePublished.draw();
            });

            var selected_unpublished_videos = [];
            var selected_published_videos = [];

            // Handle click on "Select all" control
            $('#tbl-unpublished-videos, #tbl-published-videos').on('click', 'thead input[name="select_all"]',function(e){
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

            $('#tbl-unpublished-videos, #tbl-published-videos').on('click', 'tbody input[type="checkbox"]', function(e){
                var $row = $(this).closest('tr');
                var $table = $(this).closest('table');

                // Get row data
                let table = $table.DataTable();
                var data = table.row($row).data();

                // Get row ID
                var rowId = data.id;

                if ($table.is('#tbl-unpublished-videos')) {
                    selected = selected_unpublished_videos;
                } else if ($table.is('#tbl-published-videos')) {
                    selected = selected_published_videos;
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

            $(document).on( 'init.dt', function ( e, settings ) {
                console.log(settings.sTableId);

                var api = new $.fn.dataTable.Api( settings );

                if (settings.sTableId == 'tbl-unpublished-videos') {
                    api.button().add( 0, {
                        text: 'Publish',
                        key: 'publish',
                        className: 'buttons-html5 btn btn-sm btn-default',
                        action: function () {
                            if (selected_unpublished_videos.length == 0) {
                                alert("Please select videos");
                                return;
                            }

                            $('#modal-publish').data('videos', selected_unpublished_videos);
                            $('#modal-publish').modal('toggle');
                            $('.modal-body #confirmation-url').val('{{ route('videos.publish.multiple') }}');
                        }
                    } );

                    return;
                }

                if (settings.sTableId == 'tbl-published-videos') {
                    api.button().add( 0, {
                        text: 'Un-Publish',
                        key: 'unPublish',
                        className: 'buttons-html5 btn btn-sm btn-default',
                        action: function () {
                            if (selected_published_videos.length == 0) {
                                alert("Please select videos");
                                return;
                            }

                            $('#modal-un-publish').data('videos', selected_published_videos);
                            $('#modal-un-publish').modal('toggle');
                            $('.modal-body #confirmation-url').val('{{ route('videos.un-publish.multiple') }}');
                        }
                    } );
                }
            } );

            tableDraft.on('click', '.change-video', function (e) {
                e.preventDefault();
                let videoID = $(this).data('video-id');
                let mediaID = $(this).data('media-id');
                $('#modal-change-video').find('#video-id').val(videoID);
                $('#modal-change-video').find('#media-id').val(mediaID);
                $('#modal-change-video').modal('toggle');
            });

            $('#form-change-video').validate({
                rules: {
                    media_id: {
                        required: true,
                    }
                }
            });

            tableDraft.on('click', '.a-add-to-archive', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let isConfirmed = confirm('Add to archive?');

                if (isConfirmed) {
                    $('#form-add-to-archive').attr('action', url).submit();
                }
            });

            let tableArchived = $('#table-archived-videos').DataTable();

            tableArchived.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    chapter: $('#select-chapter-archived').val(),
                    professor: $('#select-professor-archived').val(),
                    course: $('#course-archived').val(),
                    level: $('#level-archived').val(),
                    subject: $('#subject-archived').val(),
                    package_type:$('#package_type_archived').val(),

                }
            });

            $('#btn-filter-archived').click(function() {
                tableArchived.draw();
            });

            $('#btn-clear-archived').click(function() {
                $('#select-chapter-archived').val('').trigger('change');
                $('#select-professor-archived').val('').trigger('change');
                $('#course-archived').val('').trigger('change');
                $('#level-archived').val('').trigger('change');
                $('#subject-archived').val('').trigger('change');
                $('#package_type_archived').val('').trigger('change');
                tableArchived.draw();
            });

            tableArchived.on('click', '.a-remove-from-archive', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let isConfirmed = confirm('Remove from archive?');

                if (isConfirmed) {
                    $('#form-remove-from-archive').attr('action', url).submit();
                }
            });

            $("#btn-add-to-archeive").click(function (){
                let confirm = window.confirm('Add to archeive?');
                console.log(selected)
                if (confirm) {
                    $.ajax({
                        url: '{{ url('videos-add-to-archeive') }}',
                        type: 'POST',
                        data: {
                            "selectedVideoIds": selected,
                        }
                    }).done(function (response) {
                        toastr.success('Added to archeive');
                        tableDraft.draw();
                        tablePublished.draw();
                        tableArchived.draw();
                    });
                }
            });
        });
    </script>
    <script>
        $('.save-media-id').on('click', function (e){
            e.preventDefault();
            $.ajax({
                url: "{{ route('videos.change') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    'video_id': $('#video-id').val(),
                    'media_id': $('#media-id').val(),
                },
                success: function (response){
                    $("#modal-change-video").modal('toggle');
                    $('#tbl-unpublished-videos').DataTable().draw();
                    toastr.success('Media ID successfully changed');
                }
            });

        });
          
    </script>
@stop
