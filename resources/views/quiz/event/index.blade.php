@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
@section('title', 'Event Master')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Event Master</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="{{ route('quiz.event.create') }}" class="pull-right btn btn-info">Add New</a>
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
                                    <th>Event Type</th>
                                    <th>Access Type</th>
                                    <th>Course</th>
                                    <th>Level</th>
                                    <th>Event Start Date</th> 
                                    <th>Event End Date</th> 
                                    <!-- <th>Enroll Start Date</th> -->
                                    <!-- <th>Enroll End Date</th> -->
                                    {{-- <th>Status</th> --}}
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($datas) > 0)
                                @foreach($datas as $data)
                                    <tr>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->event_type }}</td>
                                        <td>{{ $data->access_type }}</td>
                                        <td>{{ $data->GetBoard->name }}</td>
                                        <td>{{ $data->GetGrade->name }}</td>
                                         <td>{{ date('d-m-Y', strtotime($data->start_date)) }}</td> 
                                         <td>{{ date('d-m-Y', strtotime($data->end_date)) }} </td> 
                                        <!-- <td>{{ date('d-m-Y', strtotime($data->enroll_start_date)) }} </td> -->
                                        <!-- <td>{{ date('d-m-Y', strtotime($data->enroll_end_date)) }} </td> -->
                                        {{-- <td>{{ $data->status == 1 ? 'Enable' : 'Disable' }}</td> --}}
                                        <td>
                                            <a href="{{ route('quiz.event.edit', ['event' => $data->id]) }}" class="btn btn-success btn-xs">
                                                Edit
                                            </a>
{{--                                            <form action="{{ route('quiz.event.destroy', ['event' => $data->id]) }}" method="post" enctype="multipart/form-data" style="display: inline-block;">--}}
{{--                                                @csrf--}}
{{--                                                <input type="hidden" name="_method" value="DELETE">--}}
                                                <button onclick="confirmAlert('/quiz/event/destroy/{{$data->id}}')" type="submit" class="btn btn-danger btn-xs" data-toggle="confirmation">
                                                    Delete
                                                </button>
{{--                                            </form>--}}
                                        </td>
                                    </tr>
                                @endforeach
                                @endif
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
