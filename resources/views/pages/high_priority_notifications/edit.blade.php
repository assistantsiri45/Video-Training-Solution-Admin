@extends('adminlte::page')

@section('title', 'Edit Notification')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Notification</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form role="form" id="edit" method="POST" action="{{ route('high-priority-notifications.update', $notification->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div id="hidden-inputs-container">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Start At</label>
                                            <input type="datetime-local" name="start_at" class="form-control" id="start_at" placeholder="start_at"
                                                   @error('start_at') is-invalid @enderror value="{{ old('end_at', \Carbon\Carbon::parse($notification->start_at)->format('Y-m-d\TH:i')) }}">
                                            @error('start_at')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('start_at') }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">End At</label>
                                            <input type="datetime-local" name="end_at" class="form-control" id="end_at" placeholder="end_at"
                                                   @error('end_at') is-invalid @enderror value="{{ old('end_at', \Carbon\Carbon::parse($notification->end_at)->format('Y-m-d\TH:i')) }}">
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
                                            <textarea class="form-control" id="content" name="content" required rows="3" placeholder="content" @error('content') is-invalid @enderror>{{ old('content', $notification->content) }}</textarea>

                                            @error('content')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('content') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="custom-checkbox" id="status" name="status"
                                               type="checkbox" @if ($notification->status) checked @endif />
                                        <label for="status">Status</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#edit').validate({
                rules: {
                    start_at: {
                        required: true,
                    },
                    end_at: {
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
