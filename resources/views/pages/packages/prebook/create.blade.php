@extends('adminlte::page')

@section('title', 'Create Prebook Package')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('packages',$package->id)}}">{{$package->name}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Prebook</li>
        </ol>
    </nav>
{{--    <h1 class="m-0 text-dark">Enable Prebooking</h1>--}}
{{--    <h6 class="mt-3">{{$package->name}}</h6>--}}
@stop


@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ url('packages/prebook-package') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="launch_date">Launch Date</label>
                                    <input type="text" name="launch_date" class="form-control" @if($package->prebook_launch_date)  value="{{ old('launch_date', $package->prebook_launch_date ?  Carbon\Carbon::parse($package->prebook_launch_date)->format('m/d/Y') : '' ) }}" @endif id="launch_date" @error('launch_date') is-invalid @enderror  value="{{ old('launch_date') }}" placeholder="Launch date">
                                    @error('launch_date')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('launch_date') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{$id}}">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="prebook_price">Price</label>
                                    <input type="number" name="prebook_price" class="form-control"   @if($package->prebook_price) value="{{$package->prebook_price}}" @endif  id="prebook_price" @error('prebook_price') is-invalid @enderror  value="{{ old('prebook_price') }}" placeholder="Price">
                                    @error('prebook_price')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('prebook_price') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="booking_amount">Booking Amount</label>
                                    <input type="number" name="booking_amount" class="form-control"  @if($package->booking_amount) value="{{$package->booking_amount}}" @endif   id="booking_amount" @error('booking_amount') is-invalid @enderror  value="{{ old('booking_amount') }}" placeholder="Booking amount">
                                    @error('booking_amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('booking_amount') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span>
                                    @php
                                        $price = \App\Models\Package::getApplicablePrices($package->id)['price'];
                                        $special = \App\Models\Package::getApplicablePrices($package->id)['special'];
                                        $discounted = \App\Models\Package::getApplicablePrices($package->id)['discounted'];
                                    @endphp

                                    <label>Price:</label> @if($discounted || $special)<strike>₹{{ $price }}</strike>@else₹{{ $price }}@endif,
                                    <label>Discounted price:</label> @if($special)<strike>₹{{ $discounted }}</strike>@else{{ $discounted ? '₹' . $discounted : '-' }}@endif,
                                    <label>Special price:</label> {{ $special ? '₹' . $special : '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="prebook-content">Content</label>
                                    <textarea class="form-control" id="prebook-content" name="prebook_content"
                                              placeholder="Content" rows="6">{{ $package->prebook_content }}</textarea>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prebook-lectures">Lectures</label>
                                    <input class="form-control" id="prebook-lectures" name="prebook_lectures"
                                    placeholder="Lectures" value="{{ $package->prebook_lectures }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prebook-total-duration">Total Duration</label>
                                    <input class="form-control" id="prebook-total-duration" name="prebook_total_duration"
                                           placeholder="HH:MM:SS" value="{{ $package->prebook_total_duration }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="is-content-ready" name="is_content_ready"
                                           type="checkbox" @if ($package->is_prebook_content_ready) checked @endif>
                                    <label class="custom-control-label" for="is-content-ready">Is Content Ready</label>
                                </div>
                                <small>Check this only if all contents are ready. Once it is checked, original content will display instead of above temporary contents.</small>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="is-prebook" name="is_prebook"
                                           type="checkbox" @if ($package->is_prebook) checked @endif>
                                    <label class="custom-control-label" for="is-prebook">Enable</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
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
                    launch_date: {
                        required: true,
                        maxlength: 255
                    },
                    booking_amount: {
                        required: true,
                        number: true,
                        maxlength: 11,
                        max: function() {
                            return parseInt($('#prebook_price').val()) - 1;
                        }
                    },
                    prebook_price: {
                        required: true,
                        number: true,
                        maxlength: 11,
                        max: function() {
                            if(parseInt('{{$special}}') > 0){
                             return    parseInt('{{$special}}');
                            }

                            if(parseInt('{{$discounted}}') > 0){
                                return    parseInt('{{$discounted}}');
                            }

                            if(parseInt('{{$price}}') > 0){
                                return    parseInt('{{$price}}');
                            }
                        }
                    }
                }
            });

            $('#launch_date').datepicker({
                dateFormat:  "dd.mm.yy",
                changeMonth: true,
                changeYear: true,
                startDate:'+1d'
            });

            if ($('#is-content-ready').is(':checked')) {
                $('#prebook-content').attr('disabled', 'disabled');
                $('#prebook-lectures').attr('disabled', 'disabled');
                $('#prebook-total-duration').attr('disabled', 'disabled');
            } else {
                $('#prebook-content').removeAttr('disabled', '');
                $('#prebook-lectures').removeAttr('disabled', '');
                $('#prebook-total-duration').removeAttr('disabled', '');
            }

            $('#is-content-ready').change(function() {
                if ($(this).is(':checked')) {
                    $('#prebook-content').attr('disabled', 'disabled');
                    $('#prebook-lectures').attr('disabled', 'disabled');
                    $('#prebook-total-duration').attr('disabled', 'disabled');
                } else {
                    $('#prebook-content').removeAttr('disabled', '');
                    $('#prebook-lectures').removeAttr('disabled', '');
                    $('#prebook-total-duration').removeAttr('disabled', '');
                }
            });

        });

    </script>
@stop
