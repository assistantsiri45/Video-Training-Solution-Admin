@extends('adminlte::page')
@section('title', 'Concept Master')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Concept Master</h1>
     </div>
        </div>
@endsection
@section('content')
@include('quiz.error')
<section class="content">
   <div class="container-fluid">
      <div class="card card-default">
         <div class="card-header">
            <h3 class="card-title">Update Concept</h3>
         </div>
         <div class="card-body">
            <form class="form-horizontal" action="{{ route('backend.concept.update', ['concept' => $data->id]) }}" method="post">
               @csrf
               @method('PUT')
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Subject</label>
                        <select class="select2 select2-hidden-accessible subject" data-placeholder="Select Subject" style="width: 100%;" name="subject" id="subject" onchange="getChapter()">
                           <option value="">Select</option>
                           @foreach ($subjects as $key => $subject)
                           <option value="{{ $subject->id }}" @if($data->getChapter->getSubject->id == $subject->id) selected="selected" @endif>{{ $subject->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Chapter</label>
                        <select class="select2 select2-hidden-accessible" data-placeholder="Select Chapter" style="width: 100%;" name="chapter" id="chapter">
                           <option value="">Select</option>
                           @foreach($chapters as $key => $chapter)
                           <option value="{{ $chapter->id }}" @if($data->chapter_id == $chapter->id) selected="selected" @endif>{{ $chapter->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}">
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Order By</label>
                        <input type="number" class="form-control" name="order_by" id="order_by" value="{{ $data->order_by }}" min="1">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Status</label>
                        <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                        <option value="1" @if($data->status == 1) selected="selected" @endif>Enable</option>
                        <option value="0" @if($data->status == 0) selected="selected" @endif>Disable</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <input type="submit" value="Update" class="btn btn-success float-right">
                     <a href="{{ route('backend.concept.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
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
   
</script>
@endsection