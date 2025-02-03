@extends('adminlte::page')

@section('title', 'Create Holiday Offer')

@section('content_header')
    <h1 class="m-0 text-dark">Create Holiday Offer</h1>
@stop

@section('css')
    <style>
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
        }
        /*#filter-btn{*/
            /*margin-top: 30px;*/
        /*}*/
        #students-table_filter{
            display: none;
        }
    </style>

@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form role="form" id="create_holidayoffer_form" method="POST" action="{{route('holiday-scheme.store')}}" >
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" required class="form-control" id="name" @error('name') is-invalid @enderror value="{{ old('name') }}" placeholder="Name" pattern="[a-zA-Z0-9\s]+">
                                   
                                  
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                          
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="dis_amount_type">Discount Type</label>
                                    <select name="dis_amount_type" id="dis_amount_type"  class="form-control select2 select2-hidden-accessible @error('dis_amount_type') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <!-- <option></option> -->
                                        <!-- <option data-select2-id="{{ \App\Models\HolidayOffer::FLAT}}"   value="{{ \App\Models\HolidayOffer::FLAT}}">{{ \App\Models\HolidayOffer::FLAT_TEXT}}</option> -->
                                        <option data-select2-id="{{ \App\Models\HolidayOffer::PERCENTAGE}}"   value="{{ \App\Models\HolidayOffer::PERCENTAGE}}">{{ \App\Models\HolidayOffer::PERCENTAGE_TEXT}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="amount">Discount Percentage</label>
                                    <input type="number" min="0" step="any" name="dis_amount" id="dis_amount" class="form-control" id="dis_amount" @error('dis_amount') is-invalid @enderror value="{{ old('dis_amount') }}" placeholder="Discount amount">
                                    <span class="invalid-feedback" id="dis_amount_err" style="display: inline;" role="alert"></span>
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('dis_amount') }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="dis_amount_type">Cash Back Type</label>
                                    <select name="cashback_amount_type" id="cb_amount_type"  class="form-control select2 select2-hidden-accessible @error('amount_type') is-invalid @enderror" style="width: 100%;" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <!-- <option></option> -->
                                        <!-- <option data-select2-id="{{ \App\Models\HolidayOffer::FLAT}}"   value="{{ \App\Models\HolidayOffer::FLAT}}">{{ \App\Models\HolidayOffer::FLAT_TEXT}}</option> -->
                                        <option data-select2-id="{{ \App\Models\HolidayOffer::PERCENTAGE}}"   value="{{ \App\Models\HolidayOffer::PERCENTAGE}}">{{ \App\Models\HolidayOffer::PERCENTAGE_TEXT}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="amount">Cash Back Percentage</label>
                                    <input type="number" min="0" max="100" step="any" name="cb_amount"  class="form-control" id="cb_amount" @error('cb_amount') is-invalid @enderror value="{{ old('cb_amount') }}" placeholder="Cash Back amount">
                                    <span class="invalid-feedback" id="cb_amount_err" style="display: inline;" role="alert"></span>
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('cb_amount') }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4 maximum-discount-container">
                                <div class="form-group">
                                    <label for="max amount">Cash Back Max Amount</label>
                                    <input type="number" min="0" step="any" name="max_cashback"  class="form-control" id="max_cashback" @error('max_cashback') is-invalid @enderror value="{{ old('max_cashback') }}" placeholder="Maximum Cash Back amount">
                                    <span class="invalid-feedback" id="max_cashback_err" style="display: inline;" role="alert"></span>
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('max_cashback') }}</span>
                                    @enderror
                                </div>
                            </div>
                           
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="course">Applicable Courses</label>
                                    <!-- <select  class="form-control @error('course') is-invalid @enderror select2" name="course" id="course_id" >
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>-->
                                    <select id="course" name="course_id" class="form-control">
                                        <option></option>
                                        @foreach (\App\Models\Course::all() as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="level">Applicable Levels</label>    
                                    <select name="level_id[]" multiple id="level"  class="form-control select-level" style="width: 100% !important;">
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="package_type">Applicable Package Type</label>
                                    <select multiple class="form-control @error('package_type') is-invalid @enderror select2" name="package_type[]" id="package_type" multiple >
                                    <option value=""></option>
                                    <option value="{{ \App\Models\Package::TYPE_CHAPTER_LEVEL }}">{{ \App\Models\Package::TYPE_CHAPTER_LEVEL_VALUE }}</option>
                                    <option value="{{ \App\Models\Package::TYPE_SUBJECT_LEVEL }}">{{ \App\Models\Package::TYPE_SUBJECT_LEVEL_VALUE }}</option>
                                    <option value="{{ \App\Models\Package::TYPE_CUSTOMIZED }}">{{ \App\Models\Package::TYPE_CUSTOMIZED_VALUE }}</option>
                                    </select>
                    
                                </div>
                            </div>
                           
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="validity"> validity</label>
                                    <input id="validity" name="validity" type="text" class="form-control @error('validity') is-invalid @enderror value="{{ old('validity') }}">
                                    @error('validity')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('validity') }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                           
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="course">Min. Cart Value</label>
                                    <input type="number" min="0"  name="min_cart_value"  class="form-control" id="min_cart_value" @error('min_cart_value') is-invalid @enderror value="{{ old('min_cart_value') }}" placeholder="Min Cart Value">
                                    <span class="invalid-feedback" id="min_cart_value_err" style="display: inline;" role="alert"></span>
                                    @error('amount')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('min_cart_value') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="create-btn"  type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
@stop

@section('js')
  
    <script type="text/javascript">
       



        $(function () {
            $('#course').select2({
                placeholder: 'Course'
            });
            $('#level').select2({
                placeholder: 'Level'
            });
            $('#package_type').select2({
                placeholder: 'Package Type'
            });
            $('#dis_amount_type').change(function(){
                var amount_type = $('#amount_type').val();
                if(amount_type == '1'){
                   
                    $("#dis_amount").removeAttr("max");
                    $("#dis_amount_err").html(''); 
                    $("#dis_amount").toggleClass('is-invalid is-valid');
                    $("#create-btn").prop('disabled', false);
                }else{
                   
                    var amount=$("#dis_amount").val();
                    if(amount>100){
                       
                        $("#dis_amount_err").html('Please enter a value less than or equal to 100.');
                        $("#dis_amount").toggleClass('is-valid is-invalid');
                        $("#create-btn").prop('disabled', true);
                    }else{
                        $("#create-btn").prop('disabled', false);
                        $("#dis_amount_err").html(''); 
                    }
                }
            });
            $('#dis_amount').change(function(){
                var amount=$("#dis_amount").val();
                var amount_type = $('#dis_amount_type').val();
                if(amount_type==2 && amount>100){
                    $("#dis_amount_err").html('Please enter a value less than or equal to 100.');
                    $("#dis_amount").toggleClass('is-valid is-invalid');
                    $("#create-btn").prop('disabled', true);

                }else{
                    $("#dis_amount").toggleClass('is-invalid is-valid');
                    $("#create-btn").prop('disabled', false);
                    $("#dis_amount_err").html(''); 

                }


            });

            $('#cb_amount_type').change(function(){
                var amount_type = $('#cb_amount_type').val();
                if(amount_type == '1'){
                    $('.maximum-discount-container').hide();
                    $("#cb_amount").removeAttr("max");
                    $("#cb_amount_err").html(''); 
                    $("#cb_amount").toggleClass('is-invalid is-valid');
                    $("#create-btn").prop('disabled', false);
                }else{
                    $('.maximum-discount-container').show();
                    var amount=$("#cb_amount").val();
                    if(amount>100){
                       
                        $("#cb_amount_err").html('Please enter a value less than or equal to 100.');
                        $("#cb_amount").toggleClass('is-valid is-invalid');
                        $("#create-btn").prop('disabled', true);
                    }else{
                        $("#create-btn").prop('disabled', false);
                        $("#cb_amount_err").html(''); 
                    }
                }
            });
            
        

            $("#dis_amount_type").select2({
                placeholder: 'Select discount amount type'
            });

            $("#cb_amount_type").select2({
                placeholder: 'Select cash back amount type'
            });

            $("#purchase_status").select2({
                placeholder: 'Select status'
            });

            $('#validity').daterangepicker({
                timePicker: true,
                locale: {
                    format: 'YYYY-MM-DD hh:mm A'
                }
            });
           
        })
    </script>
<script>

$(function () {

    $('#create_holidayoffer_form').validate({        
        rules: {
            dis_amount: {
                        required:function(){
                            if (!$("#cb_amount").val()) {
                                
                             return true;
                        }else{
                            return false;
                        }
                            }
                     },
                     cb_amount: {
                        required:function(){
                            if (!$("#dis_amount").val()) {
                                
                             return true;
                        }else{
                            return false;
                        }
                            }
                     },                     
                 }
    });

    $('#course').on('change', function () {
            
            $('#level').empty();
            var CourseID = $(this).val();

            if (CourseID) {
                $.ajax({
                    url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    
                        $('#level').append('<option disabled selected>  Choose Level </option>');
                        $.each(data, function (key, value) {
                            $('#level').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
           
            }
    });
});
    </script>
    
@stop

