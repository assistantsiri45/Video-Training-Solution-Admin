@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
@yield('css')
<style type="text/css">
   .btn-align{
   margin-right: 10px;
   }
</style>
@section('title', 'Questions')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Questions</h1>
        </div>
        <?php 
// dd(storage_path('app/public/Documents/ExcelSample/excel.zip'));
         ?>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <a href="/Documents/ExcelSample/excel.zip" class="pull-right btn btn-danger btn-align" download>Excel Sample</a>
              <!-- <a href="{{ url('storage/app/public/Documents/ExcelSample/excel.zip') }}" class="pull-right btn btn-danger btn-align" download>Excel Sample</a> -->
              <a href="{{ route('quiz.uploadExcelView') }}" class="pull-right btn btn-success btn-align">Upload Excel</a>
              <a href="{{ route('quiz.question.create') }}" class="pull-right btn btn-info btn-align">Add New</a>
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
                  <table id="admin-datatable" class="table table-bordered table-hover" width="100%">
                     <thead>
                        <tr>
                            <th>Course</th>
                            <th>Level</th>
                            <th>Subject</th>
                            <th>Chapter</th>
                            <!-- <th>Concept</th> -->
                            <th>Question Type</th>
                            <th>Content Type</th>
                            <th>Paragraph</th>
                            <th>Question</th>
                            <th>Difficulty</th>
                            <th>Score</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                     @if(count($datas) > 0)
                        @foreach($datas as $data)
                        <tr>
                            <td>
                                @if(!empty($data->getBoard))
                                    {{ $data->getBoard->name }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($data->getGrade))
                                    {{ $data->getGrade->name }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($data->getSubject))
                                    {{ $data->getSubject->name }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($data->getChapter))
                                    {{ $data->getChapter->name }}
                                @endif
                            </td>
                            <!-- <td>
                                @if(!empty($data->getConcept))
                                    {{ $data->getConcept->name }}
                                @endif
                            </td> -->
                            <td>
                                {{ config('constants.qt.'.$data->question_type) }}
                            </td>
                            <td>
                                {{ config('constants.ct.'.$data->content_type) }}
                            </td>
                            <td>
                                @if(!empty($data->getParagraph))
                                    {{ $data->getParagraph->name }}
                                @endif
                            </td>
{{--                            <td>{!! \Illuminate\Support\Str::limit($data->question, 50, $end='...') !!}</td>--}}
                            <td>{!! $data->question !!}</td>
                            <td>{{ $data->difficulty }}</td>
                            <td>{{ $data->score }}</td>
                            <td>{{ str_pad(floor($data->time/ 60), 2, 0, STR_PAD_LEFT).':'.str_pad($data->time %60, 2, 0)  }}</td>
                            <td>@if($data->status == 1 ) Enable @else Disable @endif</td>
                           <td>
                              <a href="{{ route('quiz.question.view', ['ID' => $data->id]) }}" class="btn btn-primary btn-xs">
                              View
                              </a>
                              <a href="{{ route('quiz.question.edit', ['question' => $data->id]) }}" class="btn btn-success btn-xs">
                              Edit
                              </a>
                              <!-- <form action="{{ route('quiz.question.destroy', ['question' => $data->id]) }}" method="post" enctype="multipart/form-data" style="display: inline-block;"> -->
                                 <!-- @csrf -->
                                 <!-- <input type="hidden" name="_method" value="DELETE"> -->
                                 <button type="button"  onclick="confirmAlert('/quiz/question/destroy/<?php echo $data->id ?>')"  class="btn btn-danger btn-xs" data-toggle="confirmation">
                                 Delete
                                 </button>
                              <!-- </form> -->
                           </td>
                        </tr>
                        @endforeach
                     @endif
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
