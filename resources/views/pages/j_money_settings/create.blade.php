@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1 class="m-0 text-dark">Settings</h1>
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
                <form role="form" id="create" method="POST" action="{{ route('j-money-settings.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="sign_up_point">Sign Up</label>
                                    <input type="number" name="sign_up_point" class="form-control" id="sign_up_point"  placeholder="Sign up points" required
                                    @error('sign_up_point') is-invalid @enderror @if($settings) value="{{ $settings->sign_up_point }}"@else value="{{ old('sign_up_point') }}" @endif>
                                    @error('sign_up_point')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('sign_up_point') }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="sign_up_point_expiry">Expires After</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="sign_up_point_expiry" class="form-control" id="sign_up_point_expiry" placeholder="Expires After" required
                                        @error('sign_up_point_expiry') is-invalid @enderror @if($settings) value="{{ $settings->sign_up_point_expiry }}"@else value="{{ old('sign_up_point_expiry') }}" @endif >
                                        @error('sign_up_point_expiry')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('sign_up_point_expiry') }}</span>
                                        @enderror
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="first_purchase_point">First Purchase</label>
                                    <input type="number" name="first_purchase_point" class="form-control" id="first_purchase_points" placeholder="First purchase points" required
                                    @error('first_purchase_point') is-invalid @enderror  @if($settings) value="{{ $settings->first_purchase_point }}"@else value="{{ old('first_purchase_point') }}" @endif >
                                    @error('first_purchase_point')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('first_purchase_point') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="first_purchase_point_expiry">Expires After</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="first_purchase_point_expiry" class="form-control" id="first_purchase_point_expiry"  placeholder="Expires After" required
                                        @error('first_purchase_point_expiry') is-invalid @enderror  @if($settings) value="{{ $settings->first_purchase_point_expiry }}"@else value="{{ old('first_purchase_point_expiry') }}" @endif>
                                        @error('first_purchase_point_expiry')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('first_purchase_point_expiry') }}</span>
                                        @enderror
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="promotional_activity_point">Promotional Activity</label>
                                    <input type="number" name="promotional_activity_point" class="form-control" id="promotional_activity_point" required placeholder="Promotional activity points"
                                           @error('promotional_activity_point') is-invalid @enderror  @if($settings) value="{{ $settings->promotional_activity_point }}" @else value="{{ old('promotional_activity_point') }}" @endif >
                                    @error('promotional_activity_point')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('promotional_activity_point') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="promotional_activity_point_expiry">Expires After</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="promotional_activity_point_expiry" class="form-control" id="promotional_activity_point_expiry" required  placeholder="Expires After"
                                        @error('promotional_activity_point_expiry') is-invalid @enderror  @if($settings) value="{{ $settings->promotional_activity_point_expiry }}"@else value="{{ old('promotional_activity_point_expiry') }}" @endif>
                                        @error('promotional_activity_point_expiry')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('promotional_activity_point_expiry') }}</span>
                                        @enderror
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="referral_activity_point">Referral Activities</label>
                                    <input type="number" name="referral_activity_point" class="form-control" id="referral_activity_point" required placeholder="Referral activity points"
                                    @error('referral_activity_point') is-invalid @enderror  @if($settings) value="{{ $settings->referral_activity_point }}"@else value="{{ old('referral_activity_point') }}" @endif>
                                    @error('referral_activity_point')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('referral_activity_point') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="referral_activity_point_expiry">Expires After</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="referral_activity_point_expiry" class="form-control" id="referral_activity_point_expiry" required placeholder="Expires After"
                                        @error('referral_activity_point_expiry') is-invalid @enderror  @if($settings) value="{{ $settings->referral_activity_point_expiry }}"@else value="{{ old('referral_activity_point_expiry') }}" @endif>
                                        @error('referral_activity_point_expiry')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('referral_activity_point_expiry') }}</span>
                                        @enderror
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="refund_expiry">Refund</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="refund_expiry" class="form-control" id="refund_expiry" required placeholder="Expires After"
                                        @error('refund_expiry') is-invalid @enderror  @if($settings) value="{{ $settings->refund_expiry }}"@else value="{{ old('refund_expiry') }}" @endif >
                                        @error('refund_expiry')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('refund_expiry') }}</span>
                                        @enderror
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       
                        <!--MAx jcoin setting-->
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="max_jkoin">Maximum Jkoin </label>
                                    <div class="input-group mb-3">
                                        <input type="number" min="0" max="100" name="max_jkoin" class="form-control" id="max_jkoin" placeholder="Max. Jkoin Percentage"
                                        @error('max_jkoin') is-invalid @enderror  @if($settings) value="{{ $settings->max_jkoin }}"@else value="{{ old('max_jkoin') }}" @endif >
                                        @error('max_jkoin')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('max_jkoin') }}</span>
                                        @enderror
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- MAx jcoin ends -->
                   
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
            $('#create').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    },
                    course_id: {
                        required: true
                    }
                }
            });

            $("#course").select2({
                placeholder: 'Please choose a Course'
            });
        });
    </script>
@stop
