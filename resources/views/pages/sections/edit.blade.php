@extends('adminlte::page')

@section('title', 'Edit Section')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Section</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="edit" method="POST" action="{{ route('sections.update', $section->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $section->name) }}" placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input name="is_enabled" value="0" type="hidden">
                                <input class="custom-checkbox" id="is_enabled" name="is_enabled"
                                       type="checkbox" value="1" @if($section->is_enabled == 1)checked @endif/>
                                <label for="is_enabled">Enabled</label>
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
                    name: {
                        required: true
                    }
                }
            });
        });
    </script>
@stop
