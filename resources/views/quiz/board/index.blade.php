@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
@section('title', 'Board Master')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Board Master</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="{{ route('quiz.board.create') }}" class="pull-right btn btn-info">Add New</a>
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
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $data)
                                <tr>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->status == 1 ? 'Enable' : 'Disable' }}</td>
                                    <td>
                                        <a href="{{ route('quiz.board.edit', ['board' => $data->id]) }}" class="btn btn-success btn-xs">
                                            Edit
                                        </a>
                                        <!-- <form action="{{ route('quiz.board.destroy', ['board' => $data->id]) }}" method="post" enctype="multipart/form-data" style="display: inline-block;"> -->
                                            <!-- @csrf -->
                                            <!-- <input type="hidden" name="_method" value="DELETE"> -->
                                            <button onclick="confirmAlert('/quiz/board/destroy/<?php echo $data->id ?>')"  type="submit" class="btn btn-danger btn-xs" data-toggle="confirmation">
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
    function deleteItem(id) {
    if (confirm("Are you sure?")) {
        // alert(id);
        var dis = '{{ route("quiz.board.destroy", ["board" => '+id+']) }}';
        // alert(dis);
    }
    return false;
}
</script>
@endsection
