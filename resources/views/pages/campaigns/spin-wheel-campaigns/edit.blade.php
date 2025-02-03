@extends('adminlte::page')

@section('title', 'Spin Wheel Campaigns')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">{{ $spinWheelCampaign->title }}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('spin-wheel-campaigns.update', $spinWheelCampaign->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">Title </label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                           id="title" value="{{ old('title', $spinWheelCampaign->title) }}" placeholder="Title" autocomplete="off">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($spinWheelCampaign->start_date)->format('Y-m-d')) }}" placeholder="Start Date" autocomplete="off">
                                    @error('start_date')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('start_date') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($spinWheelCampaign->end_date)->format('Y-m-d')) }}" placeholder="End Date" autocomplete="off">
                                    @error('end_date')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('end_date') }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="max_budget">Max. Budget</label>
                                    <input type="text" name="max_budget" class="form-control @error('max_budget') is-invalid @enderror"
                                           id="max_budget" value="{{ old('max_budget', $spinWheelCampaign->max_budget) }}" placeholder="Max. Budget" autocomplete="off">
                                    @error('max_budget')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('max_budget') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="point_validity">Point Validity</label>
                                    <input  type="date" name="point_validity" class="form-control @error('point_validity') is-invalid @enderror"
                                           id="point_validity" value="{{ old('end_date', \Carbon\Carbon::parse($spinWheelCampaign->point_validity)->format('Y-m-d')) }}" placeholder="Point Validity" autocomplete="off">
                                    @error('point_validity')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('point_validity') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="is_published" name="is_published" @if ($spinWheelCampaign->is_published) checked @endif>
                                    <label for="is_published" class="custom-control-label">Publish</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="buy_one_get_one" id="buy_one_get_one"
                                           @if(isset($buyoneGetone->success_percentage)) checked @endif>
                                    <label for="buy_one_get_one" class="custom-control-label">Buy One Get One</label>
                                </div>
                            </div>
                            <div class="col-sm-6 buy_one_get_one_div">
                                <div class="form-group">
                                    <input type="text" name="buy_one_get_one_title" id="buy_one_get_one_title" class="form-control @error('buy_one_get_one_title') is-invalid @enderror"
                                           id="buy_one_get_one_title" value="{{ old('buy_one_get_one_title', isset($buyoneGetone->title) ? $buyoneGetone->title: '') }}" placeholder="Title" autocomplete="off">
                                    @error('buy_one_get_one_title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('buy_one_get_one_title') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <input type="text" name="buy_one_get_success_percentage" id="buy_one_get_success_percentage" class="form-control @error('buy_one_get_success_percentage') is-invalid @enderror"
                                           id="buy_one_get_success_percentage" value="{{ old('buy_one_get_success_percentage',
isset($buyoneGetone->success_percentage) ? $buyoneGetone->success_percentage : '' ) }}" placeholder="Success %" autocomplete="off">
                                    @error('buy_one_get_success_percentage')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('buy_one_get_success_percentage') }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="better_luck_next_time" name="better_luck_next_time" @if(isset($blnt->success_percentage)) checked @endif>
                                    <label for="better_luck_next_time" class="custom-control-label">Better Luck Next Time</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="better_luck_next_time_title" id="better_luck_next_time_title" class="form-control @error('better_luck_next_time') is-invalid @enderror"
                                           id="better_luck_next_time" value="{{ old('better_luck_next_time_title', isset($blnt->title) ? $blnt->title: '') }}"  placeholder="Title" autocomplete="off">
                                    @error('better_luck_next_time')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('better_luck_next_time') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <input type="text" name="better_luck_next_time_success_percentage" id="better_luck_next_time_success_percentage" class="form-control @error('better_luck_next_time_success_percentage') is-invalid @enderror"
                                           id="better_luck_next_time_success_percentage" value="{{ old('better_luck_next_time_success_percentage',
isset($blnt->success_percentage) ? $blnt->success_percentage : '' ) }}" placeholder="Success %" autocomplete="off">
                                    @error('better_luck_next_time_success_percentage')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('better_luck_next_time_success_percentage') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="one_chapter_free" name="one_chapter_free" @if(isset($one_chapter->success_percentage)) checked @endif>
                                    <label for="one_chapter_free" class="custom-control-label">One Chapter Free</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="one_chapter_free_title" id="one_chapter_free_title" class="form-control @error('one_chapter_free') is-invalid @enderror"
                                           id="one_chapter_free" value="{{ old('one_chapter_free_title', isset($one_chapter->title) ? $one_chapter->title: '') }}"  placeholder="Title" autocomplete="off">
                                    @error('one_chapter_free')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('one_chapter_free') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <input type="text" name="one_chapter_free_success_percentage" id="one_chapter_free_success_percentage" class="form-control @error('one_chapter_free_success_percentage') is-invalid @enderror"
                                           id="one_chapter_free_success_percentage" value="{{ old('one_chapter_free_success_percentage',
isset($one_chapter->success_percentage) ? $one_chapter->success_percentage : '' ) }}" placeholder="Success %" autocomplete="off">
                                    @error('one_chapter_free_success_percentage')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('one_chapter_free_success_percentage') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="three_chapter_free" name="three_chapter_free" @if(isset($three_chapter->success_percentage)) checked @endif>
                                    <label for="three_chapter_free" class="custom-control-label">Three Chapter Free</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" name="three_chapter_free_title" id="three_chapter_free_title" class="form-control @error('three_chapter_free') is-invalid @enderror"
                                           id="three_chapter_free" value="{{ old('three_chapter_free_title', isset($three_chapter->title) ? $three_chapter->title: '') }}"  placeholder="Title" autocomplete="off">
                                    @error('three_chapter_free')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('three_chapter_free') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <input type="text" name="three_chapter_free_success_percentage" id="three_chapter_free_success_percentage" class="form-control @error('three_chapter_free_success_percentage') is-invalid @enderror"
                                           id="three_chapter_free_success_percentage" value="{{ old('three_chapter_free_success_percentage',
isset($three_chapter->success_percentage) ? $three_chapter->success_percentage : '' ) }}" placeholder="Success %" autocomplete="off">
                                    @error('three_chapter_free_success_percentage')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('three_chapter_free_success_percentage') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <div class="form-group spin-wheel-segment-container">
                                    <label>SPIN WHEEL SEGMENTS</label>
                                    @if (count($campaignSegments) > 0)
                                        @foreach ($campaignSegments as $index => $spinWheelSegment)
                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                   @if($index == 0) <label for="segment_title">Title</label> @endif
                                                    <input class="form-control" name="segment_title[]" type="text" placeholder="Title" value="{{ $spinWheelSegment->title }}" autocomplete="off">
                                                </div>
                                                <div class="col-md-2">
                                                    @if($index == 0) <label for="segment_title">Value</label> @endif
                                                    <input class="form-control" name="segment_value[]" type="text" placeholder="Value" value="{{ $spinWheelSegment->value }}" autocomplete="off">
                                                </div>
                                                <div class="col-md-2">
                                                    @if($index == 0)<label for="segment_title">Type</label> @endif
                                                    <select class="form-control" name="segment_value_type[]" placeholder="Value Type" >
                                                        <option @if ($spinWheelSegment->value_type == 1) selected @endif   value="1">Fixed</option>
                                                        <option @if ($spinWheelSegment->value_type == 2) selected @endif  value="2">Percentage</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    @if($index == 0) <label for="segment_title">Success %</label> @endif
                                                    <input class="form-control success-percentage" name="success_percentage[]" type="text" value="{{ $spinWheelSegment->success_percentage }}"  placeholder="Success %" autocomplete="off">
                                                </div>
                                                <div class="col-md-2">
                                                    @if ($loop->first)
                                                        <label for="segment_title">Add New</label>
                                                        <button class="btn btn-success button-add-wheel-segment form-control" type="button"><i class="fas fa-plus"></i></button>
                                                    @else
                                                        <button class="btn btn-danger button-remove-wheel-segment" type="button"><i class="fas fa-minus"></i></button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input class="form-control" name="segment_title[]" type="text" placeholder="Title" autocomplete="off">
                                            </div>
                                            <div class="col-md-2">
                                                <input class="form-control" name="segment_point[]" type="text" placeholder="Point" autocomplete="off">
                                            </div>
                                            <div class="col-md-2">
                                                <input class="form-control" name="segment_hit_number[]" type="text" placeholder="Hit Number" autocomplete="off">
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-success button-add-wheel-segment" type="button"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <small class="d-none percentage-error" style="color: red;">The sum of all percentage should be 100</small>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row mt-3 spin-wheel-segment-template d-none">
        <div class="col-md-4">
            <input class="form-control" name="segment_title[]" type="text" placeholder="Title" autocomplete="off">
        </div>
        <div class="col-md-2">
            <input class="form-control" name="segment_value[]" type="text" placeholder="Value" autocomplete="off">
        </div>
        <div class="col-md-2">
            <select class="form-control" name="segment_value_type[]" placeholder="Value Type" >
                <option value="1">Fixed</option>
                <option value="2">Percentage</option>
            </select>
        </div>
        <div class="col-md-2">
            <input class="form-control success-percentage" name="success_percentage[]" type="text" placeholder="Success %" autocomplete="off">
        </div>
        <div class="col-md-2">
            <button class="btn btn-danger button-remove-wheel-segment" type="button"><i class="fas fa-minus"></i></button>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#create').validate({
                rules: {
                    title: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    end_date: {
                        required: true
                    },
                    no_of_chances: {
                        required: true,
                        number: true
                    },
                    max_budget: {
                        required: true,
                        number: true
                    },
                    point_validity: {
                        required: true,
                    },
                    segment_title: {
                        required: true
                    },
                    'segment_title[]': {
                        required: true
                    },
                    'segment_point[]': {
                        required: true,
                        number: true
                    },
                    'segment_hit_number[]': {
                        required: true,
                        number: true
                    },
                    'success_percentage[]': {
                        required: true,
                        number: true
                    },
                }
            });

            $('.button-add-wheel-segment').click(function() {
                let spinWheelSegmentTemplate = $('.spin-wheel-segment-template').clone();
                spinWheelSegmentTemplate.removeClass('spin-wheel-segment-template');
                spinWheelSegmentTemplate.removeClass('d-none');
                $('.spin-wheel-segment-container').append(spinWheelSegmentTemplate);
            });

            $('.spin-wheel-segment-container').on('click', '.button-remove-wheel-segment', function() {
                $(this).closest('.row').remove();
            });

            $('#create').submit(function(e) {
                if ($(this).valid()) {
                    e.preventDefault();
                    let totalPercentage = 0;
                    if($('#buy_one_get_success_percentage').val())
                    {
                        if($('#buy_one_get_one').is(":checked"))
                        {
                            totalPercentage += parseInt($('#buy_one_get_success_percentage').val());
                        }

                    }
                    if($('#better_luck_next_time_success_percentage').val())
                    {
                        if($('#better_luck_next_time').is(":checked"))
                        {
                            totalPercentage += parseInt($('#better_luck_next_time_success_percentage').val());
                        }

                    }
                    if($('#one_chapter_free_success_percentage').val())
                    {
                        if($('#one_chapter_free').is(":checked"))
                        {
                            totalPercentage += parseInt($('#one_chapter_free_success_percentage').val());
                        }

                    }
                    if($('#three_chapter_free_success_percentage').val())
                    {
                        if($('#three_chapter_free').is(":checked"))
                        {
                            totalPercentage += parseInt($('#three_chapter_free_success_percentage').val());
                        }

                    }
                    $('.success-percentage').each(function () {
                        if ($(this).val()) {
                            totalPercentage += parseInt($(this).val());
                        }
                    });

                    if (totalPercentage === 100) {
                        $(this)[0].submit();
                    } else {
                        $('.percentage-error').removeClass('d-none');
                    }
                }

            });
        });
    </script>
@stop
