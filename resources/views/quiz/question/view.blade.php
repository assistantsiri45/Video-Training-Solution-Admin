@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
<style type="text/css">
    .form-check-label {
        margin: 0 20px 0 20px;
    }
</style>
@section('title', 'Question View')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Question View</h1>
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
         <h3 class="card-title">{!! $data->question !!}</h3>
      </div>
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
</script>
<script>
    MathJax = {
        tex: {
            inlineMath: [['$', '$'], ['\\(', '\\)']]
        },
        svg: {
            fontCache: 'global'
        }
    };
</script>
<script type="text/javascript" id="MathJax-script" async
        src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js">
</script>
@endsection
