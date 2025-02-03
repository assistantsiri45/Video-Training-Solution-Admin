@extends('adminlte::page')

@section('title', 'Edit Associates')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Associates</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="edit" method="POST" action="{{ route('agents.update', $associate->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $associate->user->name) }}" placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $associate->email) }}" placeholder="Email" readonly="readonly">
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
                                                <option @if( $associate->country_code =="+91")  selected @endif value="+91">+91</option>
                                                <option @if($associate->country_code =="+971")  selected @endif  value="+971">+971</option>
                                            </select>
                                            @error('mobile_code')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('mobile_code') }}</span>
                                            @enderror
                                        </div>
                                        <input type="text" id="mobile" name="mobile" class="form-control" @error('mobile') is-invalid @enderror value="{{ old('mobile',$associate->phone) }}" placeholder="Mobile">
                                    </div>
                                    @error('mobile')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('mobile') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="commission">Commission(%)</label>
                                    <input type="text" id="commission" name="commission" class="form-control @error('commission') is-invalid @enderror" value="{{ old('commission', $associate->commission) }}" placeholder="Commission">
                                    @error('commission')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('commission') }}</span>
                                    @enderror
                                </div>
                            </div>
{{--                            <div class="col-sm-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="repeat-purchase-by-agent-commission">Repeat purchase by agent commission</label>--}}
{{--                                    <input type="text" id="repeat-purchase-by-agent-commission" name="repeat_purchase_by_agent_commission" class="form-control @error('repeat_purchase_by_agent_commission') is-invalid @enderror" value="{{ old('repeat_purchase_by_agent_commission', $associate->commission_repeat_purchase_by_agent) }}" placeholder="Repeat purchase by agent commission">--}}
{{--                                    @error('repeat_purchase_by_agent_commission')--}}
{{--                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('repeat_purchase_by_agent_commission') }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
{{--                        <div class="row">--}}
{{--                            <div class="col-sm-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="repeat-purchase-by-student-commission">Repeat purchase by student commission</label>--}}
{{--                                    <input type="text" id="repeat-purchase-by-student-commission" name="repeat_purchase_by_student_commission" class="form-control @error('repeat_purchase_by_student_commission') is-invalid @enderror" value="{{ old('repeat_purchase_by_student_commission', $associate->commission_repeat_purchase_by_student) }}" placeholder="Repeat purchase by student commission">--}}
{{--                                    @error('repeat_purchase_by_student_commission')--}}
{{--                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('repeat_purchase_by_student_commission') }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-6">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="repeat-purchase-by-other-agent-commission">Repeat purchase by other agent commission</label>--}}
{{--                                    <input type="text" id="repeat-purchase-by-other-agent-commission" name="repeat_purchase_by_other_agent_commission" class="form-control @error('repeat_purchase_by_other_agent_commission') is-invalid @enderror" value="{{ old('repeat_purchase_by_other_agent_commission', $associate->commission_repeat_purchase_by_other_agent) }}" placeholder="Repeat purchase by other agent commission">--}}
{{--                                    @error('repeat_purchase_by_other_agent_commission')--}}
{{--                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('repeat_purchase_by_other_agent_commission') }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
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
                   commission: {
                        number: true,
                        required: true,
                        max:100
                    }
                    // repeat_purchase_by_agent_commission: {
                    //     number: true,
                    //     required: true,
                    //     max:100
                    // },
                    // repeat_purchase_by_student_commission: {
                    //     number: true,
                    //     required: true,
                    //     max:100
                    // },
                    // repeat_purchase_by_other_agent_commission: {
                    //     number: true,
                    //     required: true,
                    //     max:100
                    // }
                }
            });
        });
    </script>
@stop
