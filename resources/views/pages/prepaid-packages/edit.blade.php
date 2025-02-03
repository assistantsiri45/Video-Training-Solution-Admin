@extends('adminlte::page')

@section('title', 'Prepaid Packages')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Edit</h1>
        </div>
    </div>
@stop

@section('content')

    <form id="create" method="POST" action="{{ route('prepaid-packages.update', $student->id)  }}">
        @csrf
        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="form" value="signup">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="signup_name">Name</label>
                                            <input type="text"  class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required id="signup_name" name="name" value="{{ $student->name }}" placeholder="Name">
                                            @if ($errors->has('name'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="signup_email">Email</label>
                                            <input type="email" class="form-control {{$errors->has('email') ? ' is-invalid' : '' }}" required id="signup_email" name="email" value="{{ $student->email }}" placeholder="Email">
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile</label>
                                            <div class="input-group-prepend">
                                                <select id="mobile-code" class="col-md-3 custom-select mr-2"  name="mobile_code">
                                                    <option @if($student->country_code=='+91') selected @endif value="+91">+91</option>
                                                    <option  @if($student->country_code=='+971') selected @endif value="+971">+971</option>
                                                </select>
                                                <input type="text" id="mobile" name="mobile" class="form-control" @error('mobile')  requiredis-invalid @enderror value="{{ $student->phone }}" placeholder="Mobile">
                                            </div>
                                            @error('mobile')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('mobile') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="signup_course_id" >Course</label><br>
                                            <x-inputs.course style="width:100%;" id="signup_course_id" name="course_id"  class="form-control form-control-sm {{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                                    <option value="{{ $student->course_id }}" selected>{{ $student->course->name }}</option>
                                            </x-inputs.course>
                                            @if ($errors->has('course_id'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="signup_level_id">Level</label><br>
                                            <x-inputs.level style="width:100%;" id="signup_level_id" name="level_id" class="form-control-sm {{$errors->has('level_id') ? ' is-invalid' : '' }}" related="#signup_course_id">
                                                    <option value="{{ $student->level_id }}" selected>{{ $student->level->name }}</option>
                                            </x-inputs.level>
                                            @if ($errors->has('level_id'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea type="text"  required class="form-control{{$errors->has('address') ? ' is-invalid' : '' }}" id="address" placeholder="Address" name="address" >{{ $student->address }}</textarea>
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('address') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country_id" class="col-sm-3  {{$errors->has('country_id') ? ' is-invalid' : '' }}">Country</label>
                                            <x-inputs.country style="width:100%;" id="country_id" name="country_id" class="form-control-sm form-control-sm">
                                                    <option value="{{  $student->country_id }}" selected>{{ $student->country->name }}</option>
                                            </x-inputs.country>
                                            @if ($errors->has('country_id'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('country_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="state_id" class="col-sm-3  {{$errors->has('state_id') ? ' is-invalid' : '' }}">State</label>
                                            <x-inputs.state style="width:100%;" id="state_id" name="state_id" class="form-control-sm form-control-sm" related="#country_id">
                                                    <option value="{{ $student->state_id }}" selected>{{ $student->state->name }}</option>

                                            </x-inputs.state>
                                            @if ($errors->has('state_id'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('state_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="signup_city">City</label>
                                            <input type="text"  required class="form-control{{$errors->has('city') ? ' is-invalid' : '' }}" id="signup_city" placeholder="City" name="city" value="{{ $student->city }}">
                                            @if ($errors->has('city'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('city') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pin">Pin</label>
                                            <input type="text" required class="form-control{{$errors->has('pin') ? ' is-invalid' : '' }}" id="pin" placeholder="Pin" name="pin" value="{{ $student->pin }}">
                                            @if ($errors->has('pin'))
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('pin') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-4">
                                    <button id="btn-signup" type="submit" class="btn btn-primary px-4">Update</button>
                                </div>
                            </div>
                        </div>
    </form>
@stop

@section('js')


    <script>
        $(function() {

            $('#create').validate({
                rules: {
                    name: {
                        required: true,
                        accept: "[a-zA-Z]+"
                    },
                    email: {
                        required: true,
                        maxlength: 255,
                        email: true
                    },
                    mobile_code: {
                        required: true,
                    },
                    mobile: {
                        required: true,
                        maxlength: function() {
                            if ($('#mobile-code').val() === '+91') {
                                return 10;
                            } else {
                                return 9;
                            }
                        },
                        minlength: function() {
                            if ($('#mobile-code').val() === '+91') {
                                return 10;
                            } else {
                                return 9;
                            }
                        },
                        remote: {
                            url: '{{ url('validate-phone') }}',
                            type: 'POST',
                            data: {
                                mobile: function() {
                                    if ($('#mobile-code').val() === '+91') {
                                        return '+91' + $('#phone').val();
                                    } else {
                                        return '+971' + $('#phone').val();
                                    }
                                }
                            }
                        }
                    },
                    course_id: {
                        required: true,
                    },
                    country_id: {
                        required: true,
                    },
                    address: {
                        required: true,
                    },
                    level_id: {
                        required: true,
                    },
                }
            });
            let table = $('#datatable').DataTable();


            $('#btn-create').click(function() {
                $('#modal-create').modal('show');
            });
//            $('#sign-up-date').datepicker({
//                format: 'dd-mm-yyyy',
//                autoclose: true
//            });

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
//                    sign_up_date: $('#sign-up-date').val(),
                    search: $('#search').val()
                }
            });

            $("#country").select2({
                placeholder: 'Select Country'
            });

            $("#state").select2({
                placeholder: 'Select State'
            });


            $('#btn-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {
                search: $('#search').val('');
                table.draw();
            });
        });
    </script>
@stop
