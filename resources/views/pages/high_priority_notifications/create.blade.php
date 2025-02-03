@extends('adminlte::page')

@section('title', 'Create Professor')

@section('content_header')
    <h1 class="m-0 text-dark">Create Notification</h1>
@stop

@section('content')
        <form role="form" id="create" method="POST" action="{{ route('high-priority-notifications.store') }}">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        @csrf
                        <div id="hidden-inputs-container">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Start at</label>
                                        <input type="datetime-local" id="start_at" name="start_at" @error('start_at') is-invalid @enderror value="{{ old('start_at') }}" class="form-control" placeholder="Start At">
                                        @error('start_at')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('start_at') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="title">End at</label>
                                        <input type="datetime-local" id="end_at" name="end_at" @error('end_at') is-invalid @enderror value="{{ old('end_at') }}" class="form-control" placeholder="End At">
                                        @error('end_at')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('end_at') }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="introduction">Content</label>
                                        <textarea class="form-control" id="content" name="content" required rows="3" value="{{ old('content') }}" placeholder="Content">{{ old('content') }}</textarea>
                                    </div>
                                    @error('content')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('content') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input class="custom-checkbox" id="is-status" name="status"
                                           type="checkbox" />
                                    <label for="status">Status</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#create').validate({
                rules: {
                    start_at: {
                        required: true,
                    },
                    end_at:{
                        required: true,
                    },
                    content: {
                        required: true,
                    },
                }
            });
        });
    </script>
@stop
