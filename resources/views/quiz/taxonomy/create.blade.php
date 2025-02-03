@extends('adminlte::page')
@section('title', 'Taxamony Master')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Taxamony Master</h1>
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
                    <h3 class="card-title">Create Taxonomy</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('backend.taxonomy.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" value="{{old('name')}}" name="name" id="name">
                            </div>
                        </div>
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
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <input type="submit" value="Save" class="btn btn-success float-right">
                            <a href="{{ route('backend.taxonomy.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>

                        </div>
                    </div>
                    </form>
                </div>
            </div>
    </section>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
@endsection
