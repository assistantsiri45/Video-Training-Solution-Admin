@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
@section('title', 'Module')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Module</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="{{ route('quiz.module.create') }}" class="pull-right btn btn-info">Add New</a>
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
                    {{--                        <div class="card-header">--}}
                    {{--                            <h3 class="card-title">DataTable with minimal features & hover style</h3>--}}
                    {{--                        </div>--}}
                    <!-- /.card-header -->
                        <div class="card-body">
                            <table id="admin-datatable" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Chapter</th>
                                    <!-- <th>Concept</th> -->
                                    <th>No. of Questions</th>
                                    <th>Time</th>
                                    <th>Easy Questions</th>
                                    <th>Medium Questions</th>
                                    <th>Hard Questions</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $data)
                                    <tr>
                                        <td>{{ $data->name }}</td>
                                        @if($data->getSubject)
                                            <td>{{ $data->getSubject->name }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if($data->getChapter)
                                            <td>{{ $data->getChapter->name }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>{{ $data->no_of_ques }}</td>
                                        <td>{{ str_pad(floor($data->time/ 60), 2, 0, STR_PAD_LEFT).':'.str_pad($data->time %60, 2, 0)  }}</td>
                                        <td>{{ $data->easy_ques }}</td>
                                        <td>{{ $data->medium_ques }}</td>
                                        <td>{{ $data->hard_ques }}</td>
                                        <td>{{ $data->status == 1 ? 'Enable' : 'Disable' }}</td>
                                        <td>
                                            <a href="{{ route('quiz.module.edit', ['module' => $data->id]) }}" class="btn btn-success btn-xs">
                                                Edit
                                            </a>
                                            <!-- <form action="{{ route('quiz.module.destroy', ['module' => $data->id]) }}" method="post" enctype="multipart/form-data" style="display: inline-block;"> -->
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button onclick="confirmAlert('/quiz/module/delete/<?php echo $data->id ?>')" type="button" class="btn btn-danger btn-xs" data-toggle="confirmation">
                                                    Delete
                                                </button>
                                            <!-- </form> -->
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
    <script>
        $(function () {

        });
    </script>
@endsection
