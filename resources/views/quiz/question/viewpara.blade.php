@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
@section('title', 'Paragraph View')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Paragraph View</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <a href="{{ route('quiz.question.index') }}" class="pull-right btn btn-info">Back</a>
          </ol>
        </div>
    </div>
@endsection

@section('content')
@include('quiz.error')

<section class="content">
   <div class="container-fluid">
   <div class="card card-default">
      <div class="card-header">
         <h3 class="card-title">{!! $data->getParagraph->name !!}</h3>
      </div>
      {{-- {{dd($data->getQuestions)}} --}}
      {{-- <div class="card-body">
        @php($i=1)
        @foreach($data as $questions)
        @if($i == 1)
        <div>
        <div class="row">
        @else  
        <div class="more_question" style="display: none;">
        <hr>
        <div class="row">
        @endif
        @if($questions->content_type == 'Text')
         {!! $i.'.'. $questions->question_desc !!}
        @else
         {{ $questions->attachment }}
        @endif
        </div>
        @if($questions->question_type == 'MCQ')
        @php($type = 'checkbox') 
        @else
        @php($type = 'radio') 
        @endif 
        @foreach($questions->getOptions as $answers)
        <div class="col-md-12">
             <div class="form-check">
                 <input type="{{ $type }}" class="form-check-input" name="para" id="para">
                 <label class="form-check-label" for="para">
                 @if($answers->content_type == 'Text')
                  {!! $answers->answer !!}
                 @else
                  {{ $answers->attachment }}
                 @endif
                 </label>
             </div>
         </div>
        @endforeach
        @if($i == 1)
        <a href="#" class="more" style="float: right;margin-top: -5px;">Show More</a>
        @endif
        </div>
        @php($i++)
        @endforeach
        <a href="#" class="less" style="float: right;margin-top: -5px;display: none;">Show Less</a>
      </div> --}}
      <div class="card-body">
        @if($data->content_type == 'Text')
         {!! $data->question_desc !!}
        @else
         {{ $data->attachment }}
        @endif
        @if($data->question_type == 'MCQ')
        @php($type = 'checkbox')
        @else
        @php($type = 'radio')
        @endif
        @foreach($data->getOptions as $answers)
        <div class="col-md-12">
             <div class="form-check">
                 <input type="{{ $type }}" class="form-check-input" name="para" id="para">
                 <label class="form-check-label" for="para">
                 {!! $answers->answer !!}
                 <!-- @if($answers->content_type == 'Text') -->
                  <!-- {!! $answers->answer !!} -->
                 <!-- @else -->
                  <!-- {!! $answers->answer !!} -->
                 <!-- @endif -->
                 </label>
             </div>
         </div>
        @endforeach
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

   $('.more').click(function(){
     $('.more_question').css('display','block');
     $(this).hide()
     $('.less').show();
   });

   $('.less').click(function(){
     $('.more_question').css('display','none');
     $(this).hide()
     $('.more').show();
   });

</script>
@endsection