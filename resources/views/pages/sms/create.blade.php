@extends('adminlte::page')

@section('title', 'Create Level')

@section('content_header')
    <h1 class="m-0 text-dark">Create SMS</h1>
@stop

@section('css')
    <style>
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('sms.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Title</label>
                                  
                                    <input type="text" name="title" class="form-control" id="title" @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Title">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                            <div class="form-group">
                                    <label>Template Id</label>
                                  
                                    <input type="text" name="template_id" class="form-control" id="template_id" @error('template_id') is-invalid @enderror" value="{{ old('template_id') }}" placeholder="Template id">
                                    @error('template_id')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('template_id') }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                            <div class="form-group">
                                    <label>Body</label>
                                    <textarea class="form-control" id="body" name="body" required="" rows="6" value="" placeholder="Introduction"></textarea>



                                  
                                    @error('body')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('body') }}</span>
                                    @enderror
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#create').validate({
                rules: {
                    template_id: {
                        required: true,
                    
                    },
                    title: {
                        required: true,
                    },
                    body: {
                        required: true,
                    }
                }
            });

           
        });
    </script>
@stop
