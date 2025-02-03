@extends('adminlte::page')
@section('title', 'Power Master')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Power Master</h1>
        </div>z
     </div>
@endsection
@section('content')
@include('quiz.error')
<section class="content">
   <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
         <div class="card-header">
            <h3 class="card-title">Update Power</h3>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <form class="form-horizontal" action="{{ route('backend.power.update', ['power' => $data->id]) }}" method="post" enctype="multipart/form-data">
               @csrf
               @method('PUT')
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}">
                     </div>
                     <!-- /.form-group -->
                  </div>
                   <div class="col-md-4">
                       <div class="form-group">
                           <label>Slug (Type any one)</label>
                           <input type="text" placeholder="double,50-50,reward,extra-life,stop-timer" class="form-control" value="{{ $data->slug }}" name="slug" id="slug">
                       </div>
                       <!-- /.form-group -->
                   </div>
                   <div class="col-md-4">
                       <div class="form-group">
                           <label>Select File</label>
                           <a href="{{ asset($data->attachment) }}" target="_blank"><img src="{{ asset($data->attachment) }}" style="width: 50px" /></a>
                           <input type="file" class="form-control" name="attachment" id="attachment">
                       </div>
                       <!-- /.form-group -->
                   </div>
               </div>
                <!-- /.col -->
                <div class="row">
                  <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select File</label>
                            <a href="{{ asset($data->attachment_hover) }}" target="_blank"><img src="{{ asset($data->attachment_hover) }}" style="width: 50px" /></a>
                            <input type="file" class="form-control" name="attachment_hover" id="attachment_hover">
                        </div>
                        <!-- /.form-group -->
                    </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Status</label>
                        <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                        <option value="1" @if($data->status == 1) selected="selected" @endif>Enable</option>
                        <option value="0" @if($data->status == 0) selected="selected" @endif>Disable</option>
                        </select>
                     </div>
                     <!-- /.form-group -->
                  </div>
                  <!-- /.col -->
               </div>
               <div class="row">
                  <div class="col-12">
                     <input type="submit" value="Update" class="btn btn-success float-right">
                     <a href="{{ route('backend.power.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
                  </div>
               </div>
            </form>
         </div>
         <!-- /.card-body -->
      </div>
      <!-- /.row -->
   </div>
   <!-- /.container-fluid -->
</section>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
<script>

</script>
@endsection
