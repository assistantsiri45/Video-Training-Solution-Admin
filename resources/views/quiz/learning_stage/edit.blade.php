@extends('adminlte::page')
@section('title', 'Learning Stage Master')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Learning Stage Master</h1>
        </div>
     </div>
@endsection
@section('content')
@include('quiz.error')
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Update Learning Stage</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('backend.learning_stage.update', ['learning_stage' => $data->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
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
                                <a href="{{ route('backend.learning_stage.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
    </script>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
@endsection
