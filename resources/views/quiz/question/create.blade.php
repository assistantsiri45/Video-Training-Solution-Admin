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
            <h3 class="card-title">Create Question</h3>
         </div>
         <div class="card-body">
            <form class="form-horizontal" action="{{ route('quiz.question.store') }}" method="post" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Course</label>
                        <select class="select2 select2-hidden-accessible" data-placeholder="Select Course" style="width: 100%;" name="board" id="board">
                           <option value="">Select</option>
                           @foreach ($boards as $key => $board)
                            @if($data)
                           <option {{ $data->board_id == $board->id ? "selected" : "" }} value="{{ $board->id }}">{{ $board->name }}</option>
                           @else
                            <option {{ old('board') == $board->id ? "selected" : "" }} value="{{ $board->id }}">{{ $board->name }}</option>
                           @endif
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Level</label>
                        <select class="select2 select2-hidden-accessible" data-placeholder="Select Level" style="width: 100%;" name="grade" id="grade">
                           <option value="">Select</option>
                            @foreach ($grades as $key => $grade)
                               @if($data)
                           <option {{ $data->grade_id == $grade->id ? "selected" : "" }} value="{{ $grade->id }}">{{ $grade->name }}</option>
                           @else
                                <option {{ old('grade') == $grade->id ? "selected" : "" }} value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endif
                            @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Subject</label>
                        <select class="select2 select2-hidden-accessible subject" data-placeholder="Select Subject" style="width: 100%;" name="subject" id="subject" onchange="getChapter()">
                           <option value="">Select</option>
                           @foreach ($subjects as $key => $subject)
                           @if($data)
                           <option {{ $data->subject_id == $subject->id ? "selected" : "" }} value="{{ $subject->id }}">{{ $subject->name }}</option>
                           @else
                            
                                <option {{ old('subject') == $subject->id ? "selected" : "" }} value="{{ $subject->id }}">{{ $subject->name }}</option>

                                @endif
                            @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Chapter</label>
                        <select class="select2 select2-hidden-accessible chapter" data-placeholder="Select Chapter" style="width: 100%;" name="chapter" id="chapter" onchange="getConcept()">
                           <option value="">Select</option>
                           @if($data)
                           @foreach ($chapter as $chapters)
                           <option {{ $data->chapter_id == $chapters->id ? "selected" : "" }} value="{{ $chapters->id }}">{{ $chapters->name }}</option>
                           @endforeach
                           @endif
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <!-- <div class="col-md-3">
                     <div class="form-group">
                        <label>Concept</label>
                        <select class="select2 select2-hidden-accessible concept" data-placeholder="Select Concept" style="width: 100%;" name="concept" id="concept">
                           <option value="">Select</option>
                        </select>
                     </div>
                  </div> -->

                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Question type</label>
                        <select class="select2 select2-hidden-accessible question_type" data-placeholder="Select Question type" style="width: 100%;" name="question_type" id="question_type" onchange="getQuestionType()">
                           <option value="">Select</option>
                           @foreach(config('constants.qt') as $key => $q_type)

                           <option value="{{ $key }}" @if($data && $data->question_type == $key) selected @else @if(old('question_type') && old('question_type') == $key) selected @endif @endif>{{ $q_type }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="form-group">
                        <label>Content type</label>
                        <select class="select2 select2-hidden-accessible content_type" data-placeholder="Select Content type" style="width: 100%;" name="content_type" id="content_type" onchange="getContentType()">
                           <option value="">Select</option>
                           @foreach(config('constants.ct') as $key => $c_type)
                           <option value="{{ $key }}" @if($data && $data->content_type == $key) selected @else @if(old('content_type') && old('content_type') == $key) selected @endif @endif>{{ $c_type }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                   <div class="col-md-3">
                       <div class="form-group">
                           <label>No. of Options</label>
                           <input type="number" min="0" class="form-control" name="options" id="options" @if($data) value="{{ $data->no_of_options }}" @else value="{{ old('options') }}" @endif>
                       </div>
                   </div>
               </div>
               <div class="append"></div>
               <div class="row">
                   <div class="col-md-6">
                       <div class="form-check">
                           <input type="checkbox" class="form-check-input custom-control-label1" name="is_paragraph" id="is_paragraph" height="20px" @if($data && $data->is_paragraph == 1) checked @else @if(old('is_paragraph') && old('is_paragraph') == 1) checked @endif @endif>
                           <label class="form-check-label" for="is_paragraph" style="font-size: 20px;font-weight: 600;">Is Paragraph</label>
                       </div>
                   </div>
                   @if($data)
                    <input type="hidden" name="updated_id" value="{{ $data->id }}">

                  @endif
                  <div class="col-6">
                     <input type="submit" value="Next" class="btn btn-success float-right">
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
       tinymce.init({
           selector: 'textarea.tinymce-editor1',
           height: 200,
       });
   });

   var n = 1;
   function getContentType(){
     var content_type = $('.content_type option:selected').val();
     if(content_type=='Text'){
       $('#quest_desc').css('display','block');
       $('#quest_file').css('display','none');
     }else{
       $('#quest_desc').css('display','none');
       $('#quest_file').css('display','block');
     }

   }
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
