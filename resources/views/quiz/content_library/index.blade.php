@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
@section('title', 'Content Library')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Content Library</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="{{ route('quiz.content_library.create') }}" class="pull-right btn btn-info">Add New</a>
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
                           <th>Content</th>
                           <th>Chapter</th>
                           <th>Subject</th>
                           <th>Grade</th>
                           <th>Board</th>
                           <th>Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($datas as $data)
                        <tr>
                           <td>
                              {{ $data->name }}
                           </td>
                           <td>
                              @if(!empty($data->getContentConcept))
                              {{ $data->getContentConcept->name }}
                              @endif
                           </td>
                           <td>
                              @if(!empty($data->getContentConcept->getChapter))
                              {{ $data->getContentConcept->getChapter->name }}
                              @endif
                           </td>
                           <td>
                              @if(!empty($data->getContentConcept->getChapter->getSubject))
                              {{ $data->getContentConcept->getChapter->getSubject->name }}
                              @endif
                           </td>
                           <td>
                              @if(!empty($data->getGrade))
                              {{ $data->getGrade->name }}
                              @endif
                           </td>
                           <td>
                              @if(!empty($data->getBoard))
                              {{ $data->getBoard->name }}
                              @endif
                           </td>
                           <td>{{ $data->status = 1 ? 'Enable' : 'Disable' }}</td>
                           <td>
                              <a href="{{ route('quiz.content_library.edit', ['content_library' => $data->id]) }}" class="btn btn-success btn-xs">
                              Edit
                              </a>
                              <!-- <form action="{{ route('quiz.content_library.destroy', ['content_library' => $data->id]) }}" method="post" enctype="multipart/form-data" style="display: inline-block;"> -->
                                 <!-- @csrf -->
                                 <!-- <input type="hidden" name="_method" value="DELETE"> -->
                                 <button  onclick="confirmAlert('/quiz/content_library/destroy/<?php echo $data->id ?>')" class="btn btn-danger btn-xs" data-toggle="confirmation">
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
