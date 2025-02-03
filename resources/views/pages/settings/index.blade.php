@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1 class="m-0 text-dark">Settings</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="edit" method="POST" action="{{ route('settings.store') }}">
                    @csrf
                    <div class="card-body">
                        @foreach($settings as $setting)
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">{{ $setting->label }}</label>
                                        <input type="text" name="{{ $setting->key }}" class="form-control" id="{{ $setting->key }}" placeholder="{{ $setting->label }}" value="{{ $setting->value }}" required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <button type="submit" id="submit"  class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            jQuery.validator.addMethod("noSpace", function(value, element) { 
                     return value.indexOf(" ") < 0 && value != ""; 
            }, "Space are not allowed");

            $('#edit').validate({
                rules:{
                @foreach($settings as $setting)
                    "{{ $setting->key }}" :{
                        required: true,
                        maxlength: 255,
                        @if($setting->key =='crone_to'||$setting->key == 'special_bcc'||$setting->key =='admin_email'||$setting->key =='email_bcc')
                         noSpace: true,
                        @endif
                    },
                    @endforeach
            },
            });

            $('#submit').click(function() {
                var valid = $("#edit").valid();
                if(!valid) {
                    return false;
                }
            });
        });
    </script>
@stop
