@extends('adminlte::page')

@section('title', 'Create Chapter Package')

@section('content_header')
    <h1 class="m-0 text-dark">Create Chapter Package</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('packages.chapter.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Package Type</label>
                                    <div class="custom-control custom-radio">
                                        <div class="row">
                                            <div class="col">
                                                <input class="custom-control-input" type="radio" id="radio-video-only" name="category" value="video_only" checked>
                                                <label for="radio-video-only" class="custom-control-label">Video Only</label>
                                            </div>
                                            <div class="col">
                                                <input class="custom-control-input" type="radio" id="radio-video-test" name="category" disabled>
                                                <label for="radio-video-test" class="custom-control-label">Video + Test</label>
                                            </div>
                                            <div class="col">
                                                <input class="custom-control-input" type="radio" id="radio-video-quiz" name="category" disabled>
                                                <label for="radio-video-quiz" class="custom-control-label">Video + Quiz</label>
                                            </div>
                                            <div class="col">
                                                <input class="custom-control-input" type="radio" id="radio-video-test-quiz" name="category" disabled>
                                                <label for="radio-video-test-quiz" class="custom-control-label">Video + Test + Quiz</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Course</label>

                                    <x-inputs.course id="course_id" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}" style="width: 100%;">
                                        @if(!empty(old('course_id')))
                                            <option value="{{ old('course_id') }}" selected>{{ old('course_id_text') }}</option>
                                        @endif
                                    </x-inputs.course>

                                    @if ($errors->has('course_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Level</label>
                                    <x-inputs.level id="level_id" related="#course_id" style="width: 100%;">
                                        @if(!empty(old('level_id')))
                                            <option value="{{ old('level_id') }}" selected>{{ old('level_id_text') }}</option>
                                        @endif
                                    </x-inputs.level>

                                    @if ($errors->has('level_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control select2" id="package_type" name="package_type">
                                        <option value="">Choose Type</option>
                                       
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Subject</label>
                                    <select class="form-control select2" id="subject" name="subject_id">
                                        <option value="">Choose Subject</option>
                                       
                                    </select>
                                

                                    @if ($errors->has('subject_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('subject_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Chapter</label>
                                    <x-inputs.chapter id="chapter_id" related="#subject" style="width: 100%;">
                                        @if(!empty(old('chapter_id')))
                                            <option value="{{ old('chapter_id') }}" selected>{{ old('chapter_id_text') }}</option>
                                        @endif
                                    </x-inputs.chapter>

                                    @if ($errors->has('chapter_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('chapter_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Package Name</label>
                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Language</label>
                                    <x-inputs.language id="language_id" related="#language_id" style="width: 100%;">
                                        @if(!empty(old('language_id')))
                                            <option value="{{ old('language_id') }}" selected>{{ old('language_id_text') }}</option>
                                        @endif
                                    </x-inputs.language>
                                    @if ($errors->has('language_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('language_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea  id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('description') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="price">Professor revenue (%)</label>

                                    <input id="professor_revenue" name="professor_revenue" required type="number" min="0" class="form-control @error('professor_revenue') is-invalid @enderror value="{{ old('professor_revenue') }}">

                                    @error('professor_revenue')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('professor_revenue') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="price">Price</label>

                                    <input id="price" name="price" type="number" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">

                                    @error('price')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('price') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="discounted_price">Discounted Price</label>

                                    <div class="input-group">
                                        <input id="discounted_price" name="discounted_price" type="number" min="0" class="form-control @error('discounted_price') is-invalid @enderror" value="{{ old('discounted_price') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="discounted-price-percentage">0%</span>
                                        </div>
                                    </div>

                                    @error('discounted_price')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('discounted_price') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="discounted_price_expiry_at">Discounted Price Expiry At</label>

                                    <input id="discounted_price_expiry_at" name="discounted_price_expiry_at" type="date" class="form-control @error('discounted_price_expiry_at') is-invalid @enderror" value="{{ old('discounted_price_expiry_at') }}">

                                    @error('discounted_price_expiry_at')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('discounted_price_expiry_at') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="special_price">Special Price</label>

                                    <div class="input-group">
                                        <input id="special_price" name="special_price" type="number" min="0" class="form-control @error('special_price') is-invalid @enderror" value="{{ old('special_price') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="special-price-percentage">0%</span>
                                        </div>
                                    </div>

                                    @error('special_price')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="special_price_expiry_at">Special Price Active From</label>
                                    <input id="special_price_active_from" name="special_price_active_from" type="date" class="form-control @error('special_price_active_from') is-invalid @enderror" value="{{ old('special_price_active_from') }}">
                                    @error('special_price_expiry_at')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price_active_from') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="special_price_expiry_at">Special Price Expiry At</label>
                                    <input id="special_price_expiry_at" name="special_price_expiry_at" type="date" class="form-control @error('special_price_expiry_at') is-invalid @enderror" value="{{ old('special_price_expiry_at') }}">
                                    @error('special_price_expiry_at')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price_expiry_at') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="attempt">Attempt</label>
                                <input id="attempt" name="attempt" type="text" class="form-control">
                                @error('attempt')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('attempt') }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="duration">Duration</label>

                                <input id="duration" name="duration" type="text" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}">
                                
                           
                                @error('duration')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('duration') }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="expire_at">Expiry</label>
                                    <select id="expiry_name" class="form-control" name="expiry_name">
                                    <option></option>
                                    <option value="1">
                                        Month
                                    </option>
                                    <option value="2">
                                       Date
                                    </option>
                                </select>                                      
                                    @error('expiry_name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('expiry_name') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group month-div" hidden>
                                    <label for="alt">Select no of Months</label>
                                    <select id="expiry_month" class="form-control" name="expiry_month">
                                        <option></option>                                    
                                        @for($i=1;$i<=24;$i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                    </select>
                                </div>
                                <div class="form-group date-div" hidden>
                                    <label for="alt">Expiry date</label>
                                    <input class="form-control" type="date" min="" name="expiry_date"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="alt">Alternative Text for Image</label>
                                    <input class="form-control @error('alt') is-invalid @enderror" id="alt"
                                           name="alt" type="text" value="{{ old('alt') }}">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('alt') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="study_material_price">Study Material Price</label>
                                    <input class="form-control @error('study_material_price') is-invalid @enderror" id="study_material_price"
                                           name="study_material_price" type="text" value="{{ old('study_material_price') }}">
                                    @error('study_material_price')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('study_material_price') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" type="file" accept="image/*">
                                        <label class="custom-file-label" for="image">Choose file</label>
                                    </div>
                                    @error('image')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $message }}</span>
                                    @enderror
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <small class="text-muted"><i class="fas fa-info-circle"></i> Dimension: 400PX x 200PX, Size: Less than 150 KB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">&nbsp;</label>
                                    <div class="custom-control custom-checkbox" style="top: 6px;">
                                        <input class="custom-control-input" type="checkbox" id="checkbox-cseet" name="cseet" value="1">
                                        <label for="checkbox-cseet" class="custom-control-label">CSEET</label>
                                    </div>
                                </div>
                            </div>  
                        </div>
        
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="features">Features</label>
                                    <div class="col-md-12">
                                        <div class="feature-container">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="radio" id="checkbox-full-package" name="type" value="full" checked="">
                                    <label for="checkbox-full-package" class="custom-control-label">Is Full-Package</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="radio" id="checkbox-mini-package" value="mini" name="type">
                                    <label for="checkbox-mini-package" class="custom-control-label">Is Mini-Package</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="radio" id="checkbox-crash-course" value="crash" name="type">
                                    <label for="checkbox-crash-course" class="custom-control-label">Is Crash Course</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkbox-pendrive" name="pendrive">
                                    <label for="checkbox-pendrive" class="custom-control-label">Pen Drive</label>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkbox-g-drive" name="g-drive">
                                    <label for="checkbox-g-drive" class="custom-control-label">G-Drive</label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="feature-template d-none">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control description char-limit" name="features[]" id="features" placeholder="Please Enter Feature" maxlength="250" >

                        <div class="input-group-append">
                            {button}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#subject').select2({
                placeholder: 'Subject'
            });
            $('#package_type').select2({
                placeholder: 'Type'
            });
            $('#create').validate({
                rules: {
                    course_id: {
                        required: true
                    },
                    level_id: {
                        required: true
                    },
                    subject_id: {
                        required: true
                    },
                    chapter_id: {
                        required: true
                    },
                    name: {
                        required: true,
                        maxlength: 191
                    },
                    professor_revenue: {
                        required: true,
                        number: true,
                        max: 100
                    },
                    price: {
                        required: true,
                        number: true,
                        maxlength: 11
                    },
                    discounted_price: {
                        number: true,
                        maxlength: 11
                    },
                    special_price: {
                        number: true,
                        max: function(element){
                               return $('#price').val()-1;
                                
                            },
                        
                        maxlength: 11
                    },
                    attempt: {
                        required: true
                    },
                    duration: {
                        required: true,
                        number:true
                    },
                    expire_at: {
                        required: true
                    },
                    study_material_price: {
                        required: true,
                        number: true
                    },
                    image: {
                        required: true
                    },
                    discounted_price_expiry_at: {
                        required: {
                            depends: function(element){
                                if ($('#discounted_price').val()) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        },
                    },
                    special_price_expiry_at: {
                        required: {
                            depends: function(element){
                                if ($('#special_price').val() > 0 && $('#special_price').val() != '') {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        },
                    },
                    special_price_active_from: {
                        required: {
                            depends: function(element){
                                if ($('#special_price').val() > 0 && $('#special_price').val() != '') {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        },
                    },
                }
            });

            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
            });
            $('#special_price_active_from').change(function(){
                document.getElementById('special_price_expiry_at').min = $('#special_price_active_from').val();
            });

            $(document).on('input', '#price', function() {
                let price = $(this).val();
                let discountedPrice = $('#discounted_price').val();
                let discountPricePercentage = parseInt(100 - (parseInt(discountedPrice) / parseInt(price) ) * 100);

                if (isNaN(discountPricePercentage) || discountPricePercentage < 0) {
                    discountPricePercentage = 0;
                }

                $('#discounted-price-percentage').text(discountPricePercentage + ' %');

                let specialPrice = $('#special_price').val();
                let specialPricePercentage = parseInt(100 - (parseInt(specialPrice) / parseInt(price) ) * 100);

                if (isNaN(specialPricePercentage) || specialPricePercentage < 0) {
                    specialPricePercentage = 0;
                }

                $('#special-price-percentage').text(specialPricePercentage + ' %');
            });

            $(document).on('input', '#discounted_price', function() {
                let discountedPrice = $(this).val();
                let price = $('#price').val();
                let percentage = parseInt(100 - (parseInt(discountedPrice) / parseInt(price) ) * 100);

                if (isNaN(percentage) || percentage < 0) {
                    percentage = 0;
                }

                $('#discounted-price-percentage').text(percentage + '%');
            });

            $(document).on('input', '#special_price', function() {
                let specialPrice = $(this).val();
                let price = $('#price').val();
                let percentage = parseInt(100 - (parseInt(specialPrice) / parseInt(price) ) * 100);

                if (isNaN(percentage) || percentage < 0) {
                    percentage = 0;
                }

                $('#special-price-percentage').text(percentage + '%');
            });

            $('#attempt').datepicker({
                format: 'mm-yyyy',
                viewMode: 'months',
                minViewMode: 'months',
                autoclose: true
            });

            $('#duration').select2({
                placeholder: 'Duration'
            });


            let packageIndex = 1;

            function cloneFeature() {
                let packageTemplate = $('.feature-template').clone();

                packageTemplate = packageTemplate.html();
                packageTemplate = packageTemplate.replaceAll("{i}", packageIndex);

                if (packageIndex === 1) {
                    packageTemplate = $(packageTemplate.replaceAll('{button}', '<button class="btn btn-success button-add-feature" type="button"><i class="fa fa-plus"></i></button>'));
                } else {
                    packageTemplate = $(packageTemplate.replaceAll('{button}', '<button class="btn btn-danger button-remove-feature" type="button"><i class="fa fa-trash"></i></button>'));
                }

                packageTemplate = $(packageTemplate)
                $('.feature-container').append(packageTemplate);
                packageIndex++;

            }

            cloneFeature();

            $(document).on('click', '.button-add-feature', function () {
                cloneFeature();
            });

            $(document).on('click', '.button-remove-feature', function () {
                $(this).closest('.row').remove();
            });

        });
        $('#expiry_name').change(function() {
                let value = $(this).val();
                let monthDiv = $('.month-div');
                let dateDiv = $('.date-div');

                if (value === '1') {
                    monthDiv.attr('hidden', false);
                    dateDiv.attr('hidden', true);
                }

                if (value === '2') {
                    monthDiv.attr('hidden', true);
                    dateDiv.attr('hidden', false);
                }
            }).change();
            var package_type;
            $('#level_id').on('change', function () {
                var LevelID = $(this).val();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#package_type').empty();
                        $('#package_type').append('<option disabled selected>  Choose Type </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#package_type').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                        });
                        getSubject(package_type,LevelID);
                    }
                });
              

                } else {
                    $('#package_type').empty();
                }
            });
            $('#package_type').on('change', function () {
                var package_type = $(this).val();
                var level_id=$("#level_id").val();
                if(package_type && level_id){
                    getSubject(package_type,level_id);

                }
            });
            function getSubject(package_type,level_id){
               
                let url = '{{ url('get-subjects-by-level') }}';

                $.ajax({
                    url: url,
                    type: "GET",
                    dataType: 'json',
                    data: {
                       
                        "level_ids" : level_id ,
                        "type_id"  : package_type   ,
                    }
                }).done(function (response) {
                  
                    $('#subject').empty();
                    if(response.length>0){
                        $('#subject').append('<option disabled selected>  Choose Subject </option>');
                       
                        $.each(response, function( index, value ) {
                            var item = value.id;
                           
                           
                            $('#subject').append('<option value="' + value.id + '">' + value.name + '</option>');

                        });
                        // $("#no_subjects_available").addClass('d-none');
                    }
                    else{
                        // if(subjectsArray.length==0){
                            // $("#subject-container").empty();
                            // $("#no_levelt_selected").addClass('d-none');
                            // $("#no_subjects_available").removeClass('d-none');
                        // }
                    }

                });
            }
    </script>
@stop
