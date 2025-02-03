@extends('adminlte::page')

@section('title', 'Chapter Master')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Chapter Master</h1>
        </div>
     </div>
@endsection
@section('content')
@include('quiz.error')
<section class="content">
   <div class="container-fluid">
      <div class="card card-default">
         <div class="card-header">
            <h3 class="card-title">Create Chapter</h3>
         </div>
         <div class="card-body">
            <form class="form-horizontal" action="{{ route('backend.chapter.store') }}" method="post">
               @csrf
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Subject</label>
                        <select class="select2 select2-hidden-accessible subject" data-placeholder="Select Subject" style="width: 100%;" name="subject">
                           <option value="">Select</option>
                           @foreach ($subjects as $key => $subject)
                           <option  @if($subject->id == old('subject')) selected="selected" @endif   value="{{ $subject->id }}">{{ $subject->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="{{old('name')}}" name="name" id="name">
                     </div>
                  </div>
               </div>
               <div class="row">
                  
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Order By</label>
                        <input type="number" class="form-control" value="{{old('order_by')}}" name="order_by" min="1" id="order_by">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Status</label>
                        <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                           <option value="1" selected="">Enable</option>
                           <option value="0">Disable</option>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <input type="submit" value="Save" class="btn btn-success float-right">
                     <a href="{{ route('backend.chapter.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
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