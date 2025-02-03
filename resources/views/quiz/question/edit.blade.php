@extends('adminlte::page')
@section('title', 'Question Bank')
@section('content_header')
<style type="text/css">
   .form-check-label {
   margin: 0 20px 0 20px;
   }

   input.custom-control-label1 {
       width : 20px;
       height : 20px;
   }
</style>
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Question Bank</h1>
        </div>
     </div>
@endsection
@section('content')
@include('quiz.error')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Edit Question</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.question.update', ['question' => $ques_details->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Question</label>
                                    <textarea class="tinymce-editor1" name="question">{!! $ques_details->question !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="quest_desc">
                                <div class="form-group">
                                    <label>Question Description</label>
                                    <textarea class="tinymce-editor1" name="question_description">{!! $ques_details->question_desc !!}</textarea>
                                </div>
                            </div>
                            {{--                                <div class="col-md-3" id="quest_file">--}}
                            {{--                                    <div class="form-group">--}}
                            {{--                                        <label>File</label>--}}
                            {{--                                        <input type="file" class="form-control" name="q_attachment" id="q_attachment">--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Instruction</label>
                                    <select class="select2 select2-hidden-accessible instruction" data-placeholder="Select Instruction" style="width: 100%;" name="instruction" id="instruction">
                                        <option value="">Select</option>
                                        @foreach ($instructions as $key => $instruction)
                                            <option value="{{ $instruction->id }}" @if($ques_details->instruction_id == $instruction->id) selected="selected" @endif>{{ $instruction->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($ques_details->is_paragraph == 1)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Paragraph</label>
                                        <select class="select2 select2-hidden-accessible instruction" data-placeholder="Select Paragraph" style="width: 100%;" name="paragraph" id="paragraph">
                                            <option value="">Select</option>
                                            @foreach ($paragraphs as $key => $paragraph)
                                                <option value="{{ $paragraph->id }}" @if($ques_details->paragraph_id == $paragraph->id) selected="selected" @endif>{{ $paragraph->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Difficulty</label>
                                    <select class="select2 select2-hidden-accessible difficulty" data-placeholder="Select" style="width: 100%;" name="difficulty" id="difficulty">
                                        <option value="">Select</option>
                                        @foreach(config('constants.difficult') as $key => $difficult)
                                            <option value="{{ $key }}" @if($ques_details->difficulty == $key) selected="selected" @endif>{{ $difficult }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Score</label>
                                    <input type="number"class="form-control" min="0" name="score" id="score" value="{{ $ques_details->score }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Minutes</label>
                                    <input type="number" min="0" class="form-control" name="minutes" id="minutes" value="{{ floor($ques_details->time / 60) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Seconds</label>
                                    <input type="number" min="0" max="59" class="form-control" name="seconds" id="seconds" value="{{ $ques_details->time % 60 }}">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Order By</label>
                                    <input type="number" min="1" class="form-control" name="order_by" id="order_by" value="{{ $ques_details->order_by }}">
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                                        <option value="1" @if($ques_details->status == 1) selected="selected" @endif>Enable</option>
                                        <option value="0" @if($ques_details->status == 0) selected="selected" @endif>Disable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>QuestionCorrectFeedback</label>
                                    <textarea class="form-control" name="correct_feedback">{!! $ques_details->correct_feedback !!}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>QuestionInCorrectFeedback</label>
                                    <textarea class="form-control" name="incorrect_feedback">{!! $ques_details->incorrect_feedback !!}</textarea>
                                </div>
                            </div>
                            @if($ques_details->question_type == 'MCQ')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>QuestionPartiallyFeedback</label>
                                        <textarea class="form-control" name="partially_feedback">{!! $ques_details->partially_feedback !!}</textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-header">
                            <h3 class="card-title">Create Options</h3>
                        </div>
                        @php
                        $i = 0;
                        @endphp
                        @foreach($ques_details->getOptions as $data)
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Content type</label>
                                        <select class="select2 select2-hidden-accessible ans_content_type" data-placeholder="Select Content type" style="width: 100%;" name="ans_content_type[]">
                                            <option value="">Select</option>
                                            @foreach(config('constants.ct') as $key => $c_type)
                                                <option value="{{ $key }}" @if($data->content_type == $key) selected="selected" @endif>{{ $c_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input @if($ques_details->question_type == 'SCQ') type="radio" @elseif($ques_details->question_type == 'MCQ') type="checkbox" @endif class="form-check-input is_correct" name="is_correct[]" value="{{$i}}" @if($data->is_correct == 1) checked="checked" @endif>Is Correct
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Answer</label>
                                        <textarea class="tinymce-editor1" name="answer[]">{!! $data->answer !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Feedback</label>
                                        <textarea class="form-control" name="feedback[]">{!! $data->feedback !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                        <div class="append"></div>
                        <div class="row">
                            <div class="col-12">
                                <input type="submit" value="Update" name="save" class="btn btn-success float-right">
                                    <a href="{{ route('quiz.question.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
    <script>
        $(function () {
            var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var ImageUrl = "{{ route('quiz.image-upload') }}";
            tinymce.init({
                selector: 'textarea.tinymce-editor1',
                height: 200,
                skin: useDarkMode ? 'oxide-dark' : 'oxide',
                content_css: useDarkMode ? 'dark' : 'default',
            });
        });

        var n = 1;
        // function getContentType(){
        //     var content_type = $('.content_type option:selected').val();
        //     if(content_type=='Text'){
        //         $('#quest_desc').css('display','block');
        //         $('#quest_file').css('display','none');
        //     }else{
        //         $('#quest_desc').css('display','none');
        //         $('#quest_file').css('display','block');
        //     }
        //
        // }
        function getQuestionType(){
            var question_type = $('.question_type option:selected').val();
            if(question_type=='SCQ'){
                $('.is_correct').attr('type','radio');

            }else{
                $('.is_correct').attr('type','checkbox');
            }

        }

        function appendAnswerRow(){
            var content_type = <?php echo json_encode(config('constants.ct')) ?>;
            var html = '<div class="row"><div class="col-md-3"><div class="form-group"><label>Content type</label><select class="select2 select2-hidden-accessible ans_content_type" id="sel_'+n+'" data-placeholder="Select Content type" style="width: 100%;" name="ans_content_type[]" onchange="getContentType()"><option value="">Select</option>';
            $.each(content_type,function( key, value ) {
                html += '<option value="'+key+'">'+value+'</option>';
            });
            html += '</select></div></div><div class="col-md-3"><div class="form-group"><label>Answer</label><textarea class="tinymce-editor1 desc_'+n+'" name="answer[]"></textarea></div></div><div class="col-md-3"><div class="form-group"><label>File</label><input type="file" class="form-control" name="a_attachment[]"></div></div><div class="col-md-2" id="is_correct"><div class="form-check"><label class="form-check-label"><input type="radio" class="form-check-input is_correct" name="is_correct[]" value="1">Is Correct </label></div></div><div class="col-md-1"><input type="button" value="Add" class="btn btn-success float-right"></div></div>'

            $('.append').html(html);

            $('#sel_'+n).select2();
            var nme = 'textarea.desc_'+n+'';
            $(function () {
                tinymce.init({
                    selector: nme,
                    height: 200,
                });
            });
            n++;
        }
    </script>
@endsection
