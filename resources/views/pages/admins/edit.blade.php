@extends('adminlte::page')

@section('title', 'Edit Admin')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Admin</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="edit" method="POST" action="{{ route('admins.update', $admin->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" id="role" class="form-control select2 select2-hidden-accessible @error('role') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option></option>
                                        <option data-select2-id="{{ App\Models\User::ROLE_ADMIN }}" @if( old('role')==App\Models\User::ROLE_ADMIN || $admin->role == App\Models\User::ROLE_ADMIN) selected @endif  value="{{ App\Models\User::ROLE_ADMIN }}">{{ App\Models\User::ROLE_ADMIN_TEXT }}</option>
                                        <option data-select2-id="{{ App\Models\User::ROLE_REPORT_ADMIN }}" @if( old('role')==App\Models\User::ROLE_REPORT_ADMIN || $admin->role == App\Models\User::ROLE_REPORT_ADMIN) selected @endif  value="{{ App\Models\User::ROLE_REPORT_ADMIN }}">{{ App\Models\User::ROLE_REPORT_ADMIN_TEXT }}</option>
                                        <option data-select2-id="{{ App\Models\User::ROLE_CONTENT_MANAGER }}" @if( old('role')==App\Models\User::ROLE_CONTENT_MANAGER || $admin->role == App\Models\User::ROLE_CONTENT_MANAGER) selected @endif  value="{{ App\Models\User::ROLE_CONTENT_MANAGER }}">{{ App\Models\User::ROLE_CONTENT_MANAGER_TEXT }}</option>
                                        <option data-select2-id="{{ App\Models\User::ROLE_FINANCE_MANAGER }}" @if( old('role')==App\Models\User::ROLE_FINANCE_MANAGER || $admin->role == App\Models\User::ROLE_FINANCE_MANAGER) selected @endif  value="{{ App\Models\User::ROLE_FINANCE_MANAGER }}">{{ App\Models\User::ROLE_FINANCE_MANAGER_TEXT }}</option>
                                        <option data-select2-id="{{ App\Models\User::ROLE_BRANCH_MANAGER }}" @if( old('role')==App\Models\User::ROLE_BRANCH_MANAGER || $admin->role == App\Models\User::ROLE_BRANCH_MANAGER) selected @endif  value="{{ App\Models\User::ROLE_BRANCH_MANAGER }}">{{ App\Models\User::ROLE_BRANCH_MANAGER_TEXT }}</option>
                                         <option data-select2-id="{{ App\Models\User::ROLE_ASSISTANT }}" @if( old('role')==App\Models\User::ROLE_ASSISTANT || $admin->role == App\Models\User::ROLE_ASSISTANT) selected @endif  value="{{ App\Models\User::ROLE_ASSISTANT }}">{{ App\Models\User::ROLE_ASSISTANT_TEXT }}</option>
                                         <option data-select2-id="{{ App\Models\User::ROLE_REPORTING }}" @if( old('role')==App\Models\User::ROLE_REPORTING || $admin->role == App\Models\User::ROLE_REPORTING) selected @endif  value="{{ App\Models\User::ROLE_REPORTING }}">{{ App\Models\User::ROLE_REPORTING_TEXT }}</option>
                                         <option data-select2-id="{{ App\Models\User::ROLE_BACKOFFICE_MANAGER }}" @if( old('role')==App\Models\User::ROLE_BACKOFFICE_MANAGER || $admin->role == App\Models\User::ROLE_BACKOFFICE_MANAGER) selected @endif  value="{{ App\Models\User::ROLE_BACKOFFICE_MANAGER }}">{{ App\Models\User::ROLE_BACKOFFICE_MANAGER_TEXT }}</option>
                                         <option data-select2-id="{{ App\Models\User::ROLE_JUNIOR_ADMIN }}" @if( old('role')==App\Models\User::ROLE_JUNIOR_ADMIN || $admin->role == App\Models\User::ROLE_JUNIOR_ADMIN) selected @endif  value="{{ App\Models\User::ROLE_JUNIOR_ADMIN }}">{{ App\Models\User::ROLE_JUNIOR_ADMIN_TEXT }}</option>
                                         
                                        {{--                                        <option data-select2-id="{{ App\Models\User::ROLE_COURSE_ADMIN }}" @if( old('role')==App\Models\User::ROLE_COURSE_ADMIN) selected @endif   value="{{ App\Models\User::ROLE_COURSE_ADMIN }}">{{ App\Models\User::ROLE_COURSE_ADMIN_TEXT }}</option>--}}
                                        {{--                                        <option data-select2-id="{{ App\Models\User::ROLE_BUSINESS_ADMIN }}" @if( old('role')==App\Models\User::ROLE_BUSINESS_ADMIN) selected @endif  value="{{ App\Models\User::ROLE_BUSINESS_ADMIN }}">{{ App\Models\User::ROLE_BUSINESS_ADMIN_TEXT }}</option>--}}
                                        {{--                                        <option data-select2-id="{{ App\Models\User::ROLE_PLATFORM_ADMIN }}" @if( old('role')==App\Models\User::ROLE_PLATFORM_ADMIN) selected @endif  value="{{ App\Models\User::ROLE_PLATFORM_ADMIN }}">{{ App\Models\User::ROLE_PLATFORM_ADMIN_TEXT }}</option>--}}
                                    </select>
                                    @error('role')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('role') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" placeholder="Email" readonly="true">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('email') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="mobile">Mobile</label>
                                    <div class="input-group-prepend">
                                        <div class="col-md-3">
                                            <select id="mobile-code" class="custom-select  @error('mobile_code') is-invalid @enderror"  name="mobile_code">
                                                <option @if( $admin->country_code =="+91")  selected @endif value="+91">+91</option>
                                                <option @if($admin->country_code =="+971")  selected @endif  value="+971">+971</option>
                                            </select>
                                            @error('mobile_code')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('mobile_code') }}</span>
                                            @enderror
                                        </div>
                                        <input type="text" id="mobile" name="mobile" class="form-control" @error('mobile') is-invalid @enderror value="{{ old('mobile',$admin->phone) }}" placeholder="Mobile">
                                    </div>
                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('mobile') }}</span>
                                    @enderror
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
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        maxlength: 255,
                        email: true
                    },
                    role: {
                        required: true
                    }
                }
            });

            $("#role").select2({
                placeholder: 'Please chose a role'
            });
        });
    </script>
@stop
