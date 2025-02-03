@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
@section('title', 'Grade Master')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Grade Master</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="{{ route('quiz.grade.create') }}" class="pull-right btn btn-info">Add New</a>
            </ol>
        </div>
    </div>
@endsection

@section('content')
@include('quiz.error')
<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <table id="admin-datatable" class="table table-bordered table-hover">
                     <thead>
                        <tr>
                           <th>Name</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($datas as $data)
                        <tr>
                           <td>{{ $data->name }}</td>
                           <td>@if($data->status == '1') Enable @else Disable @endif</td>
                           <td>
                              <a href="{{ route('quiz.grade.edit', ['grade' => $data->id]) }}" class="btn btn-success btn-xs">
                              Edit
                              </a>
                              <!-- <form action="{{ route('quiz.grade.destroy', ['grade' => $data->id]) }}" method="post" enctype="multipart/form-data" style="display: inline-block;"> -->
                                 <!-- @csrf -->
                                 <!-- <input type="hidden"  name="_method" value="DELETE"> -->
                                 <button type="submit" onclick="confirmAlert('/quiz/grade/destroy/<?php echo $data->id ?>')" class="btn btn-danger btn-xs" data-toggle="confirmation">
                                 Delete
                                 </button>
                              <!-- </form> -->
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
<script>
   $(function () {
   
   });
</script>
@endsection