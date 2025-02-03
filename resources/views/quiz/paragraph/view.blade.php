@extends('adminlte::page')
@section('title', 'Paragraph View')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Paragraph View</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <a href="{{ route('quiz.paragraph.index') }}" class="pull-right btn btn-info">Back</a>
          </ol>
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
         <h3 class="card-title">{{ $data->name }}</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
         {!! $data->description !!}
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
