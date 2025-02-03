@extends('backend.master-layouts.master')
@section('contentHeader')
<div class="col-sm-6">
   <h1 class="m-0 text-dark">Admin Master</h1>
</div>
@endsection
@section('content.wrapper')
<section class="content">
   <div class="container-fluid">
      <!-- SELECT2 EXAMPLE -->
      <div class="card card-default">
         <div class="card-header">
            <h3 class="card-title">Create Admin</h3>
         </div>
         <!-- /.card-header -->
         <div class="card-body">
            <form class="form-horizontal" action="{{ route('backend.admin.store') }}" method="post">
               @csrf
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="{{old('name')}}" name="name" id="name">
                     </div>
                     <!-- /.form-group -->
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>E-mail</label>
                        <input type="text" class="form-control" name="email" id="email">
                     </div>
                     <!-- /.form-group -->
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4">
                     <div class="form-group">
                        <label>Password</label>
                        <input type="text" class="form-control" name="password" id="password">
                     </div>
                     <!-- /.form-group -->
                  </div>
                  <div class="col-md-2">
                        <input type="button" class="btn btn-primary float-right" name="generate_pswd" id="generate_pswd" value="Generate Password" style="margin-top: 30px;margin-right: 40px;" onclick="getPassword()">
                     <!-- /.form-group -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Status</label>
                        <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                           <option value="1" selected="selected">Enable</option>
                           <option value="0">Disable</option>
                        </select>
                     </div>
                     <!-- /.form-group -->
                  </div>
                  <!-- /.col -->
               </div>
               <div class="row">
                  <div class="col-12">
                     <input type="submit" value="Save" class="btn btn-success float-right">
                     <a href="{{ route('backend.admin.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
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
<script>
   
   function getPassword(){
      var token   = $('input[name=_token]').val();
      $.ajax({
          url: "{{ route('backend.getPassword') }}",
          type: "GET",
          success: function(response) {
            $('#password').val(response);
          }
      });
  }
</script>
@endsection