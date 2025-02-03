@extends('adminlte::page')
@section('title', 'Content Master')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Content Master</h1>
        </div>
     </div>
@endsection
@section('content')
@include('quiz.error')
<section class="content">
   <div class="container-fluid">
      <div class="card card-default">
         <div class="card-header">
            <h3 class="card-title">Create Content</h3>
         </div>
         <div class="card-body">
            <form class="form-horizontal" action="{{ route('backend.content_library.store') }}" method="post" enctype="multipart/form-data">
               @csrf
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Board</label>
                        <select class="select2 select2-hidden-accessible board" data-placeholder="Select Board" style="width: 100%;" name="board" id="board">
                           <option value="">Select</option>
                           @foreach ($boards as $key => $board)
                           <option {{ old('board') == $board->id ? "selected" : "" }} value="{{ $board->id }}">{{ $board->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Grade</label>
                        <select class="select2 select2-hidden-accessible grade" data-placeholder="Select Grade" style="width: 100%;" name="grade" id="grade">
                           <option value="">Select</option>
                           @foreach ($grades as $key => $grade)
                           <option {{ old('grade') == $grade->id ? "selected" : "" }} value="{{ $grade->id }}">{{ $grade->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Subject</label>
                        <select class="select2 select2-hidden-accessible subject" data-placeholder="Select Subject" style="width: 100%;" name="subject" id="subject" onchange="getChapter()">
                           <option value="">Select</option>
                           @foreach ($subjects as $key => $subject)
                           <option {{ old('subject') == $subject->id ? "selected" : "" }} value="{{ $subject->id }}">{{ $subject->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Chapter</label>
                        <select class="select2 select2-hidden-accessible chapter" data-placeholder="Select Chapter" style="width: 100%;" name="chapter" id="chapter" onchange="getConcept()">
                           <option value="">Select</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Concept</label>
                        <select class="select2 select2-hidden-accessible concept" data-placeholder="Select Concept" style="width: 100%;" name="concept" id="concept">
                           <option value="">Select</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="name">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Content type</label>
                        <select class="select2 select2-hidden-accessible content_type" data-placeholder="Select Content type" style="width: 100%;" name="content_type" id="content_type">
                           <option value="">Select</option>
                           @foreach(config('constants.ct') as $key => $c_type)
                           @if($key!='Text')
                           <option  {{ old('content_type') == $key ? "selected" : "" }}value="{{ $key }}">{{ $c_type }}</option>
                           @endif
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>File</label>
                        <input type="file" class="form-control" name="attachment" id="attachment">
                     </div>
                  </div>
                   <div class="col-md-4">
                       <div class="form-group">
                           <label>Thumbnail</label>
                           <input type="file" class="form-control" name="thumbnail" id="thumbnail">
                       </div>
                   </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Taxomony Selection</label>
                        <select class="select2 select2-hidden-accessible taxonomy" data-placeholder="Select" style="width: 100%;" name="taxonomy" id="taxonomy">
                           <option value="">Select</option>
                           @foreach ($taxonomy as $key => $taxonom)
                           <option  {{ old('taxonomy') == $taxonom->id ? "selected" : "" }} value="{{ $taxonom->id }}">{{ $taxonom->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Learning Stages</label>
                        <select class="select2 select2-hidden-accessible learning_stage" data-placeholder="Select" style="width: 100%;" name="learning_stage" id="learning_stage">
                           <option value="">Select</option>
                           @foreach ($learning_stage as $key => $learning)
                           <option  {{ old('learning_stage') == $learning->id ? "selected" : "" }} value="{{ $learning->id }}">{{ $learning->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Status</label>
                        <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                           <option value="1" {{ old('status') == "1" ? "selected" : "" }}>Enable</option>
                           <option  {{ old('status') == "0" ? "selected" : "" }} value="0">Disable</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <input type="submit" value="Save" class="btn btn-success float-right">
                     <a href="{{ route('backend.content_library.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
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
</script>
@endsection
