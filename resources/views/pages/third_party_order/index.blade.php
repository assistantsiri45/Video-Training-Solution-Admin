@extends('adminlte::page')

@section('title', 'New Order')

@section('content_header')
    <div class="row">
        <div class="col-7">
            <h1 class="m-0 text-dark">Create new student</h1>
        </div>
        <div class="col-5">
            <h1 class="m-0 text-dark">Search student</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-7">
            <div class="card">
            <div class="card-body">
                <div class="bg-secondary-10 p-4">
                    <form id="form-add-address"  method="POST" action="{{route('third-party.students.store')}}">
                        @csrf
                        <input id="add-address-state" type="hidden" name="state">
                        <input id="add-address-country" type="hidden" name="country">
                        <div class="form-group">
                            <label>Country / Region</label>
                            <x-inputs.country id="country_id" name="country_id" class="form-control form-control-sm">
                                @if(!empty(old('country_id')))
                                    <option value="{{ old('country_id') }}" selected>{{ old('country_id_text') }}</option>
                                @endif
                            </x-inputs.country>
                            @if ($errors->has('country_id'))
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('country_id') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Full name</label>
                            <input type="text" id="add-address"  name="name" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="email" name="email" class="form-control email form-control-sm {{ $errors->has('email') ? ' is-invalid' : '' }}">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3 mt-3">
                                <label>Phone</label>
                                <select id="mobile_code" class="form-control-sm bg-white" name="country_code">
                                    <option selected value="+91">+91</option>
                                    <option value="+971">+971</option>
                                </select>
                            </div>
                            <div class="col-sm-9 mt-3">
                                <input type="text" placeholder="10-digit mobile number without prefixes" class="form-control phone form-control-sm {{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ old('phone') }}">
                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Course</label>
                                <x-inputs.course id="course_id" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                    @if(!empty(old('course_id')))
                                        <option value="{{ old('course_id') }}" selected>{{ old('course_id_text') }}</option>
                                    @endif
                                </x-inputs.course>

                                @if ($errors->has('course_id'))
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                @endif
                            </div>
                            <div class="col-sm-6">
                                <label>Level</label>
                                <x-inputs.level id="level_id" related="#course_id">
                                    @if(!empty(old('level_id')))
                                        <option value="{{ old('level_id') }}" selected>{{ old('level_id_text') }}</option>
                                    @endif
                                </x-inputs.level>

                                @if ($errors->has('level_id'))
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>PIN code</label>
                            <input type="text" name="pin" class="form-control form-control-sm" placeholder="6 digits [0-9] PIN code">
                        </div>
                        <div class="form-group">
                            <label>Flat, House no., Building, Company, Apartment</label>
                            <input name="address" class="form-control form-control-sm" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Area, Colony, Street, Sector, Village</label>
                            <input name="area" class="form-control form-control-sm" placeholder="">
                        </div>
                        <div class="form-group">
                            <label>Landmark</label>
                            <input name="landmark" class="form-control form-control-sm" placeholder="E.g. Near AIIMS Flyover, Behind Regal Cinema, etc.">
                        </div>
                        <div class="form-group">
                            <label>Town/City</label>
                            <input type="text" name="city" class="form-control form-control-sm">
                        </div>
                        <div class="form-group ">
                            <label>State / Province / Region</label>
                            <x-inputs.state id="state_id" name="state_id" class="form-control form-control-sm {{$errors->has('state_id') ? ' is-invalid' : '' }}" related="#country_id">
                                @if(!empty(old('state_id')))
                                    <option value="{{ old('state_id') }}" selected>{{ old('state_id_text') }}</option>
                                @endif
                            </x-inputs.state>
                            @if ($errors->has('state_id'))
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('state_id_text') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Address Type</label>
                            <select id="address_type" name="address_type" style="width: 100%">
                                <option value="1" selected>Home (7am - 9pm delivery)</option>
                                <option value="2">Office/Commercial (10am - 6pm delivery)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
        </div>

            <div class="col-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <input id="search" type="text" class="form-control" placeholder="Search">
                            </div>
                            <div class="col-md-3">
                                <button id="button-filter" class="btn btn-primary w-100">Search</button>
                            </div>
                            <div class="col-md-3">
                                <button id="btn-clear" class="btn btn-primary">Clear</button>
                            </div>
                            <div class="col-md-12 d-none mt-5" id="student-field">
                                <h4 id="name"></h4>
                                <p id="student-email"></p>
                                <p id="student-phone"></p>
                                <a class="btn btn-primary text-white" id="add-package">Add Package</a>
                            </div>
                            <div class="col-md-12 d-none mt-5" id="student-name-not-found">
                                <h4 id="name">Student not found</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>


{{--    <form id="form-export" method="POST" action="{{ url('export-call-requests') }}">--}}
{{--        @csrf--}}
{{--        <input id="export-status" type="hidden" name="export_status">--}}
{{--        <input id="export-search" type="hidden" name="export_search">--}}
{{--        <input id="export-created-at" type="hidden" name="export_created_at">--}}
{{--    </form>--}}
@stop

@section('js')

    <script type="text/javascript">
        $(function() {

            $('#button-filter').click(function() {

                $.ajax({
                    method: 'GET',
                    url: '{{url('/get-student')}}',
                    data: {
                        name:$("#search").val(),
                    },
                    success: function(response){
                        if(response.length != 0) {
                            $("#student-name-not-found").addClass("d-none");
                          var name = response[0].name;
                          var id = response[0].id;
                          var email = response[0].email;
                          var phone = response[0].phone;
                            $("#name").text(name);
                            $("#student-email").text(email);
                            $("#student-phone").text(phone);
                            $("#student-field").removeClass("d-none");
                            $("#add-package").attr("href", "{{url('third-party-orders')}}"+"/"+id)
                        }
                        else
                        {
                            $("#student-field").addClass("d-none");
                            $("#student-name-not-found").removeClass("d-none");

                        }
                    },
                });
            });

            $('#btn-clear').click(function() {
                search: $('#search').val('');
                $("#student-field").addClass("d-none");
                $("#student-name-not-found").addClass("d-none");
                table.draw();
            });





        });
    </script>
    <script id="script-modal-signup">
        $(function () {
            'use strict';

            @if (request()->has('referral'))
            $('#modal-signup').modal('show');
            @endif

            $('#form-signup').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255
                    },
                    mobile: {
                        required: true,
                        maxlength: 255
                    },
                    password: {
                        required: true,
                        maxlength: 255,
                        minlength: 5
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#signup_password"
                    },
                    course_id: {
                        required: true
                    },
                    level_id: {
                        required: true
                    },
                    country_id: {
                        required: true
                    },
                   
                    state_id: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    pin: {
                        required: true
                    },
                    terms: {
                        required: true
                    }
                },
                messages: {
                    terms: {
                        required: "You must agree our terms & condition and our privacy policy to continue signup"
                    }
                }
            });


            $('#btn-signup').click(function (e) {
                e.preventDefault();

                if (!$('#form-signup').valid()) {
                    return;
                }

                $('#modal-otp').find('#otp_mobile').val($('#signup_mobile').val());
                $('#modal-otp').modal('show');
            });

            $('#modal-otp').on('hide.bs.modal', function () {
                $('#form-signup').find('#otp_token').val($('#modal-otp').find('#otp_token').val());
                $('#form-signup').find('#otp_code').val($('#modal-otp').find('#otp_code').val());

                $('#btn-signup').prop('disabled', true);
                $('#form-signup').submit();
            });

            @if(old('form') == 'signup')
            $('#modal-signup').modal('show');
            @endif
        });
    </script>
    <script>
        $(document).ready(function(){
            'use strict';

            $('#address_type').select2();
            $('#update-address-address-type').select2();

            $('#form-add-address').find('#state_id').change(function() {
                $("#add-address-state").attr("hidden",false);
                $('#add-address-state').val('');
                $('#add-address-state').val($(this).text());
                $("#add-address-state").attr("hidden",true);
            });

            $('#form-add-address').find('#country_id').change(function() {
               $("#add-address-country").attr("hidden",false);
                $('#add-address-country').val('');
                $('#add-address-country').val($(this).text());
                $("#add-address-country").attr("hidden",true);
            });

            $('#form-add-address').find('#mobile_code').change(function() {
                if ($(this).val() === '+91') {
                    $('#form-add-address').find('#phone').attr('placeholder', '10-digit mobile number without prefixes');
                } else {
                    $('#form-add-address').find('#phone').attr('placeholder', '9-digit mobile number without prefixes');
                }
            });

            $('#form-add-address').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 191
                    },
                    phone: {
                        required: true,
                        digits: true,
                        maxlength: function() {
                            if ($('#mobile_code').val() === '+91') {
                                return 10;
                            } else {
                                return 9;
                            }
                        },
                        minlength: function() {
                            if ($('#mobile_code').val() === '+91') {
                                return 10;
                            } else {
                                return 9;
                            }
                        },
                    },
                    alternate_phone: {
                        digits: true,
                        maxlength: function() {
                            if ($('#altr-mobile-code').val() === '+91') {
                                return 10;
                            } else {
                                return 9;
                            }
                        },
                        minlength: function() {
                            if ($('#altr-mobile-code').val() === '+91') {
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
                    city: {
                        required: true,
                        maxlength: 191
                    },
                    country_id: {
                        required: true
                    },
                    state: {
                        required: true,
                        maxlength: 191
                    },
                    country: {
                        required: true
                    },
                    state_id: {
                        required: true,
                       
                    },
                    address: {
                        required: true,
                        maxlength: 191
                    },
                    pin: {
                        required: true,
                        digits: true,
                        maxlength: 191
                    }
                }
            });
        });
    </script>
@stop
