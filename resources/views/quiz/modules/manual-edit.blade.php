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
                    <form class="form-horizontal" action="{{ route('quiz.modules.update', ['module' => $module->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="test_id" id="test_id" value="{{ $data->id }}">
                        <input type="hidden" name="module_id" id="module_id" value="{{ $module->id }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Module Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $module->name }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Total Questions</label>
                                    <input type="number" class="form-control" min="1" name="total" id="total" readonly value="{{ $module->no_of_ques }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Minutes</label>
                                    <input type="number" min="0" class="form-control" name="minutes" id="minutes" value="{{ floor($module->time / 60) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Seconds</label>
                                    <input type="number" min="0" max="59" class="form-control" name="seconds" id="seconds" value="{{ $module->time % 60 }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cut-off Score</label>
                                    <input type="number" class="form-control" name="cutoff" id="cutoff" value="{{ $module->cut_off }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                                        <option value="1" @if($module->status == 1) selected="selected" @endif>Enable</option>
                                        <option value="0" @if($module->status == 0) selected="selected" @endif>Disable</option>
                                    </select>
                                </div>
                            </div>

                        </div>


                        <div class="card card-body" id="refresh-table">
                            @include('quiz.modules.manual-table')
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <input type="submit" value="Update" class="btn btn-success float-right">
                                <a href="{{ url('quiz/test/'.$data->id.'/edit') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
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

            var total = $('#selected-ques').val();
            var totalMin = $('#selected-min').val();
            var totalSec = $('#selected-sec').val();

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

            $(document.body).on('click', '#refresh', function (){
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
                var module = $('#module_id').val();
                {{--                var url = "{{ url('quiz/test/refresh-table') }}/"+test+"/0/0/0";--}}
                var url = "{{ url('quiz/modules/refresh-table') }}/"+test+"/"+module+"/"+subject+"/"+chapter+"/"+concept

                $('#refresh-table').load(url, function(responseTxt, statusTxt, xhr){
                    // alert(responseTxt, statusTxt, xhr);
                    $('.subject').select2();
                    $('.chapter').select2();
                    $('.concept').select2();

                     total = $('#selected-ques').val();
                     totalMin = $('#selected-min').val();
                     totalSec = $('#selected-sec').val();

                    $('#total').val(total);
                    $('#minutes').val(totalMin);
                    $('#seconds').val(totalSec);
                });

            }
            $(document.body).on('change', '.question_id' ,function(){
// alert(1);
                var question_id = $(this).attr('id');
                var subject_id = $(this).attr('data-sub');
                var chapter_id = $(this).attr('data-chap');
                var concept_id = $(this).attr('data-con');
                var minute = $(this).attr('data-min');
                var second = $(this).attr('data-sec');
                var addMin = 0;
                var subct = $(".subject").find("#subject-"+subject_id).attr('data-counter');
                var chapct = $(".chapter").find("#chapter-"+chapter_id).attr('data-counter');
                var conct = $(".concept").find("#concept-"+concept_id).attr('data-counter');
                var subcst;
                var chapcst;
                var concst;
                if($(this).prop('checked') == true){
                    subcst = parseInt(subct) + 1;
                    chapcst = parseInt(chapct) + 1;
                    concst = parseInt(conct) + 1;
                    total = parseInt(total) + 1;

                    if(parseInt(totalSec) + parseInt(second) > 59){

                        totalSec = parseInt(totalSec) + parseInt(second) - 60;
                        totalMin = parseInt(totalMin) + parseInt(minute) + 1;
                    }else{
                        totalSec = parseInt(totalSec) + parseInt(second);
                        totalMin = parseInt(totalMin) + parseInt(minute);
                    }

                }else{
                    subcst = parseInt(subct) - 1;
                    chapcst = parseInt(chapct) - 1;
                    concst = parseInt(conct) - 1;

                    total = parseInt(total) - 1;

                    if(parseInt(totalSec) < parseInt(second) ){

                        totalSec = parseInt(totalSec) + parseInt(second);
                        totalMin = parseInt(totalMin) - parseInt(minute) - 1;
                    }else{
                        totalSec = parseInt(totalSec) - parseInt(second);
                        totalMin = parseInt(totalMin) - parseInt(minute);
                    }
                }


                if(!$(this).hasClass('selected')){
                    $(".subject").find("#subject-"+subject_id).text($(".subject").find("#subject-"+subject_id).attr('data-name')+'('+subcst+')');
                    $(".subject").find("#subject-"+subject_id).attr('data-counter', subcst);
                    $('.subject').select2();
                    $(".chapter").find("#chapter-"+chapter_id).text($(".chapter").find("#chapter-"+chapter_id).attr('data-name')+'('+chapcst+')');
                    $(".chapter").find("#chapter-"+chapter_id).attr('data-counter', chapcst);
                    $('.chapter').select2();
                    $(".concept").find("#concept-"+concept_id).text($(".concept").find("#concept-"+concept_id).attr('data-name')+'('+concst+')');
                    $(".concept").find("#concept-"+concept_id).attr('data-counter', concst);
                    $('.concept').select2();
                }


                $('#total').val(total);
                $('#minutes').val(totalMin);
                $('#seconds').val(totalSec);
            });
        });
    </script>
@endsection
