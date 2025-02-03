@extends('adminlte::page')

@section('title', 'Prepaid Packages')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Prepaid Packages</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <input class="form-control" id="search" type="text" placeholder="Search" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input class="form-control" id="date" type="text" placeholder="Created At">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select id="select-professors" style="width: 100%">
                                    <option value=""></option>
                                    @foreach (\App\Models\Professor::all() as $professor)
                                        <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button id="btn-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary">Clear</button>
                        </div>
                        <div class="col-md-2">
                            <button id="btn-create" class="btn btn-primary float-right">Create Student</button>
                        </div>
                    </div>
                </div>
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
    <form id="create" method="POST" action="{{ url('/create-student') }}">
        @csrf
        <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="model-create" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                        <input type="hidden" name="form" value="signup">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="signup_name">Name</label>
                                    <input type="text"  class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required id="signup_name" name="name" value="{{ old('name') }}" placeholder="Name">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="signup_email">Email</label>
                                    <input type="email" class="form-control {{$errors->has('email') ? ' is-invalid' : '' }}" required id="signup_email" name="email" value="{{ old('email') }}" placeholder="Email">
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
                                            <option @if(old('mobile_code')=='+91') selected @endif value="+91">+91</option>
                                            <option  @if(old('mobile_code')=='+971') selected @endif value="+971">+971</option>
                                        </select>
                                        <input type="text" id="mobile" name="mobile" class="form-control" @error('mobile')  requiredis-invalid @enderror value="{{ old('mobile') }}" placeholder="Mobile">
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
                                        @if(!empty(old('course_id')))
                                            <option value="{{ old('course_id') }}" selected>{{ old('course_id_text') }}</option>
                                        @endif
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
                                        @if(!empty(old('level_id')))
                                            <option value="{{ old('course_id') }}" selected>{{ old('level_id_text') }}</option>
                                        @endif
                                    </x-inputs.level>
                                    @if ($errors->has('level_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea type="text"  required class="form-control{{$errors->has('address') ? ' is-invalid' : '' }}" id="address" placeholder="Address" name="address" >{{ old('address') }}</textarea>
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
                                        @if(!empty(old('country_id')))
                                            <option value="{{ old('country_id') }}" selected>{{ old('country_id_text') }}</option>
                                        @endif
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
                                        @if(!empty(old('state_id')))
                                        <option value="{{ old('state_id') }}" selected>{{ old('state_id_text') }}</option>
                                        @endif
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
                                    <input type="text"  required class="form-control{{$errors->has('city') ? ' is-invalid' : '' }}" id="signup_city" placeholder="City" name="city" value="{{ old('city') }}">
                                    @if ($errors->has('city'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('city') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pin">Pin</label>
                                    <input type="text" required class="form-control{{$errors->has('pin') ? ' is-invalid' : '' }}" id="pin" placeholder="Pin" name="pin" value="{{ old('pin') }}">
                                    @if ($errors->has('pin'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('pin') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            <button id="btn-signup" type="submit" class="btn btn-primary px-4">Create</button>
                        </div>
                        </div>
                    </div>
                </div>

            </div>

{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                <button type="button" class="btn btn-primary">Send message</button>--}}
{{--            </div>--}}
        </div>
    </div>
    </form>

    <div class="modal fade" id="modal-student-edit" tabindex="-1" role="dialog" aria-labelledby="modal-student-edit-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-student-edit-label">Edit Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit" method="POST" action="">
                        @csrf
                        @method('PUT')
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="form" value="signup">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="signup_name">Name</label>
                                        <input type="text"  class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required id="edit_signup_name" name="name" value="{{ old('name') }}" placeholder="Name">
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="signup_email">Email</label>
                                        <input type="email" class="form-control {{$errors->has('email') ? ' is-invalid' : '' }}" required id="edit_signup_email" name="email" value="{{ old('email') }}" placeholder="Email">
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
                                            <select id="edit_mobile_code" class="col-md-3 custom-select mr-2"  name="mobile_code">
                                                <option @if(old('mobile_code')=='+91') selected @endif value="+91">+91</option>
                                                <option  @if(old('mobile_code')=='+971') selected @endif value="+971">+971</option>
                                            </select>
                                            <input type="text" id="edit_mobile" name="mobile" class="form-control" @error('mobile')  requiredis-invalid @enderror value="{{ old('mobile') }}" placeholder="Mobile">
                                        </div>
                                        @error('mobile')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('mobile') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="signup_course_id" >Course</label><br>
                                        <x-inputs.course style="width:100%;" id="edit_signup_course_id" name="course_id"  class="form-control form-control-sm {{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                            @if(!empty(old('course_id')))
                                                <option value="{{ old('course_id') }}" selected>{{ old('course_id_text') }}</option>
                                            @endif
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
                                        <x-inputs.level style="width:100%;" id="edit_signup_level_id" name="level_id" class="form-control-sm {{$errors->has('level_id') ? ' is-invalid' : '' }}" related="#edit_signup_course_id">
                                            @if(!empty(old('level_id')))
                                                <option value="{{ old('course_id') }}" selected>{{ old('level_id_text') }}</option>
                                            @endif
                                        </x-inputs.level>
                                        @if ($errors->has('level_id'))
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea type="text"  required class="form-control{{$errors->has('address') ? ' is-invalid' : '' }}" id="edit_address" placeholder="Address" name="address" >{{ old('address') }}</textarea>
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
                                        <x-inputs.country style="width:100%;" id="edit_country_id" name="country_id" class="form-control-sm form-control-sm">
                                            @if(!empty(old('country_id')))
                                                <option value="{{ old('country_id') }}" selected>{{ old('country_id_text') }}</option>
                                            @endif
                                        </x-inputs.country>
                                        @if ($errors->has('country_id'))
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('country_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state_id" class="col-sm-3  {{$errors->has('state_id') ? ' is-invalid' : '' }}">State</label>
                                        <x-inputs.state style="width:100%;" id="edit_state_id" name="state_id" class="form-control-sm form-control-sm" related="#edit_country_id">
                                            @if(!empty(old('state_id')))
                                                <option value="{{ old('state_id') }}" selected>{{ old('state_id_text') }}</option>
                                            @endif
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
                                        <input type="text"  required class="form-control{{$errors->has('city') ? ' is-invalid' : '' }}" id="edit_signup_city" placeholder="City" name="city" value="{{ old('city') }}">
                                        @if ($errors->has('city'))
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('city') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pin">Pin</label>
                                        <input type="text" required class="form-control{{$errors->has('pin') ? ' is-invalid' : '' }}" id="edit_pin" placeholder="Pin" name="pin" value="{{ old('pin') }}">
                                        @if ($errors->has('pin'))
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('pin') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-4">
                                <button id="btn-edit" type="submit" class="btn btn-primary px-4">Update</button>
                            </div>
                        </div>
                    </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}

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
           $('#date').datepicker({
               format: 'dd-mm-yyyy',
               autoclose: true,
           });

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
//                    sign_up_date: $('#sign-up-date').val(),
                    search: $('#search').val(),
                    date: $('#date').val(),
                    professor: $('#select-professors').val(),
                }
            });

            // $("#country").select2({
            //     placeholder: 'Select Country'
            // });
            //
            // $("#state").select2({
            //     placeholder: 'Select State'
            // });

            $('#btn-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {
                search: $('#search').val('');
                $('#date').val('');
                $('#select-professors').val('').change();
                table.draw();
            });

            $('#datatable').on('click', '.button-edit-student', function () {

                let student_id = $(this).data('student-id');

                let url = '{{ url('prepaid-packages' ) }}' + '/' + student_id;

                $("#edit").attr('action',url);

                $.ajax({
                    url: "{{ url('prepaid-packages' ) }}" + '/' + student_id + '/edit',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        if (result) {
                           $("#edit_signup_name").val(result.name);
                           $("#edit_signup_email").val(result.email);
                           $("#edit_mobile").val(result.phone);
                           $("#edit_address").val(result.address);
                           $("#edit_signup_city").val(result.city);
                           $("#edit_pin").val(result.pin);
                           $("#edit_signup_course_id").append(`<option value="${result.course_id}">${result.course.name}</option>`);
                           $("#edit_mobile_code").val(result.country_code);
                           $("#edit_signup_level_id").append(`<option value="${result.level_id}">${result.level.name}</option>`);
                           $("#edit_country_id").append(`<option value="${result.country_id}">${result.country.name}</option>`);
                           $("#edit_state_id").append(`<option value="${result.state_id}">${result.state.name}</option>`);
                        }

                        $('#modal-student-edit').modal('toggle');
                    }
                });

            });

            {{--$('#btn-edit').click(function() {--}}

            {{--    let student_id = $('.button-edit-student').data('student-id');--}}

            {{--    $.ajax({--}}
            {{--        url: "{{ url('prepaid-packages' ) }}" + '/' + student_id,--}}
            {{--        type: 'PUT',--}}
            {{--        data: {--}}
            {{--            _token: "{{ csrf_token() }}"--}}
            {{--        },--}}
            {{--        success: function(result) {--}}
            {{--            if (result) {--}}
            {{--                console.log(result);--}}
            {{--            }--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

            $('#select-professors').select2({
                placeholder: 'Professors'
            });
        });
    </script>
@stop
