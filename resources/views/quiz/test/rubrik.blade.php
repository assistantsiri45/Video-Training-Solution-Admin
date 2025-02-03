@extends('adminlte::page')
@section('title', 'Test Master')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Test Master</h1>
        </div>
     </div>
@endsection
@section('content')
@include('quiz.error')
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Create Test Module => Step - 2</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.test.step2-submit-auto', ['QID' => $data->id]) }}" method="post">
                        @csrf
                        <input type="hidden" name="test_id" id="test_id" value="{{ $data->id }}">
                        @if(isset($flag))
                            <input type="hidden" name="flag" id="flag" value="{{ $flag }}">
                        @endif
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Module Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Total Questions</label>
                                    <input type="number" class="form-control" min="1" name="total" id="total" readonly>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Minutes</label>
                                    <input type="number" min="0" class="form-control" name="minutes" id="minutes" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Seconds</label>
                                    <input type="number" min="0" max="59" class="form-control" name="seconds" id="seconds" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cut-off Score</label>
                                    <input type="number" class="form-control" name="cutoff" id="cutoff" value="{{ old('cutoff') }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                                        <option value="1" selected="selected">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                            </div>

                        </div>


                        <div class="card card-body" id="refresh-table">
                            @include('quiz.test.auto-table')
                        </div>
                        <div class="row">
                            <div class="col-12">
                                @if(isset($flag))
                                    <input type="submit" value="Save" class="btn btn-success float-right">
                                @else
                                    <input type="submit" value="Next" class="btn btn-success float-right">
                                    <a href="#" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
                                @endif

                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
    <script>
        $(function () {

            var total = 0;
            var totalMin = 0;
            var totalSec = 0;

            $('.subject').on('change', function (){
                var subject = '0';
                if($(this).val() !== ''){
                    subject = $('.subject').val();
                }
                var test = $('#test_id').val();
                var token = $('input[name=_token]').val();
                $.ajax({
                    url: "{{ route('quiz.getSelectedChapter') }}",
                    type: "POST",
                    data: {
                        _token:token,
                        test:test,
                        subject:subject
                    },
                    success: function(response) {
                        var html = '<option value="">Select</option>';

                        $.each(response, function (key, val) {
                            html += '<option value="'+val.id+'" data-counter="0" data-name="'+val.name+'">'+val.name+'(0)</option>';
                        });
                        $('.chapter').html(html);
                        $('.chapter').select2();
                    }
                });
            });

            $('.chapter').on('change', function (){
                var chapter = '0';
                var subject = '0';
                if($(this).val() !== ''){
                    subject = $('.subject').val();
                }
                if($(this).val() !== ''){
                    chapter = $('.chapter').val();
                }
                var test = $('#test_id').val();
                var token = $('input[name=_token]').val();
                $.ajax({
                    url: "{{ route('quiz.getSelectedConcept') }}",
                    type: "POST",
                    data: {
                        _token:token,
                        test:test,
                        subject:subject,
                        chapter:chapter
                    },
                    success: function(response) {
                        var html = '<option value="">Select</option>';

                        $.each(response, function (key, val) {
                            html += '<option value="'+val.id+'" data-counter="0" data-name="'+val.name+'">'+val.name+'(0)</option>';
                        });
                        $('.concept').html(html);
                        $('.concept').select2();
                    }
                });
            });

            $('#test_type').on('change', function (){

                if($(this).val() !== 'Olympiad'){
                    $('#sections').val(1);
                    $('.sections').hide();
                }else{
                    $('.sections').show();
                    $('#sections').val(1);
                }
            });

            $('#ques_type').on('change', function (){
                if($(this).val() === 'auto'){
                    $('.auto_ques_type').show();
                    $('.is_difficulty').show();
                }else{
                    $('.auto_ques_type').hide();
                    $('.is_difficulty').hide();
                }
            });

            $(document).on('click', '#refresh', function (){
                if (confirm('Are you sure you want to refresh the questions? The question selection is going to refresh also')) {
                    // Save it!
                    refreshTable();
                }
            });

            function refreshTable(){
                var subject = '0';
                var chapter = '0';
                var concept = '0';
                if($('.subject').val() != ''){
                    subject = $('.subject').val();
                }
                if($('.chapter').val() != ''){
                    chapter = $('.chapter').val();
                }
                if($('.concept').val() != ''){
                    concept = $('.concept').val();
                }
                var test = $('#test_id').val();
                {{--                var url = "{{ url('quiz/test/refresh-table') }}/"+test+"/0/0/0";--}}
                var url = "{{ url('quiz/test/refresh-table') }}/"+test+"/"+subject+"/"+chapter+"/"+concept

                $('#refresh-table').load(url, function(responseTxt, statusTxt, xhr){
                    // alert(responseTxt, statusTxt, xhr);
                    $('.subject').select2();
                    $('.chapter').select2();
                    $('.concept').select2();

                    total = 0;
                    totalMin = 0;
                    totalSec = 0;

                    $('#total').val(total);
                    $('#minutes').val(totalMin);
                    $('#seconds').val(totalSec);
                });

            }
            $(document.body).on('change', '.selection' ,function(){
// alert(1);
                var selection = $(this);
                var auto_selection_type = $("#auto_selection_type").val();
                var subject_id = 0;
                var chapter_id = 0;
                var concept_id = 0;
                var old_data = selection.attr('data-old');
                if(auto_selection_type === 'subject'){
                     subject_id = selection.attr('data-sub');
                }else if(auto_selection_type === 'chapter'){
                     subject_id = selection.attr('data-sub');
                     chapter_id = selection.attr('data-chap');
                }else if(auto_selection_type === 'concept'){
                     subject_id = selection.attr('data-sub');
                     chapter_id = selection.attr('data-chap');
                     concept_id = selection.attr('data-con');
                }

                var subct = $(".subject").find("#subject-"+subject_id).attr('data-counter');
                var chapct = $(".chapter").find("#chapter-"+chapter_id).attr('data-counter');
                var conct = $(".concept").find("#concept-"+concept_id).attr('data-counter');
                var subcst;
                var chapcst;
                var concst;
                // if($(this).prop('checked') == true){
                    subcst = parseInt(subct) + parseInt(selection.val()) - parseInt(old_data);
                    chapcst = parseInt(chapct) + parseInt(selection.val()) - parseInt(old_data);
                    concst = parseInt(conct) + parseInt(selection.val()) - parseInt(old_data);
                    total = total + parseInt(selection.val()) - parseInt(old_data);

                    // if(parseInt(totalSec) + parseInt(second) > 59){
                    //
                    //     totalSec = parseInt(totalSec) + parseInt(second) - 60;
                    //     totalMin = parseInt(totalMin) + parseInt(minute) + 1;
                    // }else{
                    //     totalSec = parseInt(totalSec) + parseInt(second);
                    //     totalMin = parseInt(totalMin) + parseInt(minute);
                    // }

                // }else{
                //     subcst = parseInt(subct) - 1;
                //     chapcst = parseInt(chapct) - 1;
                //     concst = parseInt(conct) - 1;
                //
                //     total = total - 1;
                //
                //     if(parseInt(totalSec) < parseInt(second) ){
                //
                //         totalSec = parseInt(totalSec) + parseInt(second);
                //         totalMin = parseInt(totalMin) - parseInt(minute) - 1;
                //     }else{
                //         totalSec = parseInt(totalSec) - parseInt(second);
                //         totalMin = parseInt(totalMin) - parseInt(minute);
                //     }
                // }
                selection.attr('data-old', parseInt(selection.val()));
                $(".subject").find("#subject-"+subject_id).text($(".subject").find("#subject-"+subject_id).attr('data-name')+'('+subcst+')');
                $(".subject").find("#subject-"+subject_id).attr('data-counter', subcst);
                $('.subject').select2();
                $(".chapter").find("#chapter-"+chapter_id).text($(".chapter").find("#chapter-"+chapter_id).attr('data-name')+'('+chapcst+')');
                $(".chapter").find("#chapter-"+chapter_id).attr('data-counter', chapcst);
                $('.chapter').select2();
                $(".concept").find("#concept-"+concept_id).text($(".concept").find("#concept-"+concept_id).attr('data-name')+'('+concst+')');
                $(".concept").find("#concept-"+concept_id).attr('data-counter', concst);
                $('.concept').select2();

                $('#total').val(total);
                $('#minutes').val(totalMin);
                $('#seconds').val(totalSec);
            });

            $(document.body).on('change', '.diff-selection' ,function(){

                var selection = $(this);
                var auto_selection_type = $("#auto_selection_type").val();

                var subject_id = 0;
                var chapter_id = 0;
                var concept_id = 0;
                var old_data = selection.attr('data-old');
                var type = selection.attr('data-type');
                var id = selection.attr('data-id');
                var selectionValue = selection.parent().parent().find('td #'+auto_selection_type+'-'+id).val();

                // alert(selectionValue);
                if(type === 'easy'){
                    var easy = selection.val();
                    var medium = $('#medium-'+id).val();
                    var hard = $('#hard-'+id).val();
                    var tt = parseInt(easy) + parseInt(medium) + parseInt(hard);
                    if(parseInt(tt) > parseInt(selectionValue)){
                        selection.val(old_data);
                    }else{
                        selection.attr('data-old', selection.val());
                    }
                }else if(type === 'medium'){
                    var medium = selection.val();
                    var easy = $('#easy-'+id).val();
                    var hard = $('#hard-'+id).val();
                    var tt = parseInt(easy) + parseInt(medium) + parseInt(hard);
                    if(parseInt(tt) > parseInt(selectionValue)){
                        selection.val(old_data);
                    }else{
                        selection.attr('data-old', selection.val());
                    }
                }else if(type === 'hard'){
                    var hard = selection.val();
                    var medium = $('#medium-'+id).val();
                    var easy = $('#easy-'+id).val();
                    var tt = parseInt(easy) + parseInt(medium) + parseInt(hard);
                    if(parseInt(tt) > parseInt(selectionValue)){
                        selection.val(old_data);
                    }else{
                        selection.attr('data-old', selection.val());
                    }
                }

            });
        });
    </script>
@endsection
