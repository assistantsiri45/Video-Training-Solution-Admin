@extends('adminlte::page')

@section('title', 'Edit Package')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <form id="form-edit-package" method="POST" action="{{ route('packages.update', $package->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div id="new-package-wizard">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#step-1">
                                    <strong>Select Package Type</strong>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#step-2">
                                    <strong>Package Details</strong>
                                </a>
                            </li>
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a class="nav-link" href="#step-3">--}}
                            {{--                                    <strong>Select Videos</strong>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                            {{--                            <li class="nav-item">--}}
                            {{--                                <a class="nav-link" href="#step-4">--}}
                            {{--                                    <strong>Finish</strong>--}}
                            {{--                                </a>--}}
                            {{--                            </li>--}}
                        </ul>
                        <div class="tab-content">
                            <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                <div class="p-5">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    <div class="row">
                                                        <div class="col">
                                                            <input class="custom-control-input" type="radio" id="radio-video-only" name="category" value="video_only" @if ($package->category == 1) checked @endif>
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
                                </div>
                            </div>
                            <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                <div class="row">
                                    <div class="col-md-9 col-sm-12 p-5">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Course</label>

                                                    <x-inputs.course id="course_id" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}" style="width: 100%;">
                                                        @if(!empty(old('course_id', $package->course_id)))
                                                            <option value="{{ old('course_id', $package->course_id) }}" selected>{{ old('course_id_text', empty($package->course) ? '' : $package->course->name) }}</option>
                                                        @endif
                                                    </x-inputs.course>

                                                    @if ($errors->has('course_id'))
                                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Level</label>
                                                    <x-inputs.level id="level_id" related="#course_id" style="width: 100%;">
                                                        @if(!empty(old('level_id', $package->level_id)))
                                                            <option value="{{ old('level_id', $package->level_id) }}" selected>{{ old('level_id_text', empty($package->level) ? '' : $package->level->name) }}</option>
                                                        @endif
                                                    </x-inputs.level>

                                                    @if ($errors->has('level_id'))
                                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Subject</label>
                                                    <x-inputs.subject id="subject_id" related="#level_id" style="width: 100%;">
                                                        @if(!empty(old('subject_id', $package->subject_id)))
                                                            <option value="{{ old('subject_id', $package->subject_id) }}" selected>{{ old('subject_id_text', empty($package->subject) ? '' : $package->subject->name) }}</option>
                                                        @endif
                                                    </x-inputs.subject>

                                                    @if ($errors->has('subject_id'))
                                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('subject_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Chapter</label>
                                                    <x-inputs.chapter id="chapter_id" related="#subject_id" style="width: 100%;">
                                                        @if(!empty(old('chapter_id', $package->chapter_id)))
                                                            <option value="{{ old('chapter_id', $package->chapter_id) }}" selected>{{ old('chapter_id_text', empty($package->chapter) ? '' : $package->chapter->name) }}</option>
                                                        @endif
                                                    </x-inputs.chapter>

                                                    @if ($errors->has('chapter_id'))
                                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('chapter_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Package Name</label>
                                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}">
                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Language</label>
                                                    <x-inputs.language id="language_id" related="#language_id" style="width: 100%;">
                                                        @if(!empty(old('language_id', $package->language_id)))
                                                            <option value="{{ old('language_id', $package->language_id) }}" selected>{{ old('language_id_text', empty($package->language) ? '' : $package->language->name) }}</option>
                                                        @endif
                                                    </x-inputs.language>

                                                    @if ($errors->has('language_id'))
                                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('language_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea  id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $package->description) }}</textarea>

                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label for="price">Price</label>

                                                    <input id="price" name="price" type="text" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $package->price) }}">

                                                    @error('price')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('price') }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="discounted_price">Discounted Price</label>
                                                    <div class="input-group">
                                                        <input id="discounted_price" name="discounted_price" type="number" class="form-control @error('discounted_price') is-invalid @enderror" value="{{ old('discounted_price', $package->discounted_price) }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="discounted-price-percentage">0%</span>
                                                        </div>
                                                    </div>

                                                    @error('discounted_price')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('discounted_price') }}</span>
                                                    @enderror

                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="discounted_price_expiry_at">Discounted Price Expiry At</label>

                                                    <input id="discounted_price_expiry_at" name="discounted_price_expiry_at" type="date" class="form-control @error('discounted_price_expiry_at') is-invalid @enderror" value="{{ old('discounted_price_expiry_at', $package->discounted_price_expire_at ? $package->discounted_price_expire_at->toDateString() : '') }}">

                                                    @error('discounted_price_expiry_at')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('discounted_price_expiry_at') }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="special_price">Special Price</label>

                                                    <div class="input-group">
                                                        <input id="special_price" name="special_price" type="number" class="form-control @error('special_price') is-invalid @enderror" value="{{ old('special_price', $package->special_price) }}">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text" id="special-price-percentage">0%</span>
                                                        </div>
                                                    </div>

                                                    @error('special_price')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price') }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="special_price_expiry_at">Special Price Expiry At</label>

                                                    <input id="special_price_expiry_at" name="special_price_expiry_at" type="date" class="form-control @error('special_price_expiry_at') is-invalid @enderror" value="{{old('special_price_expiry_at', $package->special_price_expire_at ? $package->special_price_expire_at->toDateString() : '') }}">


                                                    @error('special_price_expiry_at')
                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price_expiry_at') }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div id="hidden-inputs-container"></div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-control-label" for="image_file">Image</label>
                                                            <div class="crop-tool">
                                                                <div id="upload-demo" ></div>
                                                                <div class="col-md-1" id="upload-demo-i" name="image_viewport"></div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="input-group mb-3">
                                                                    <input  class="form-control" type="file"  id="upload" name="file" @error('file') is-invalid @enderror>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-secondary" type="button" id="crop-btn" >Click here to Crop Image</button>
                                                                    </div>
                                                                    @error('file')
                                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('file') }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 crop-tool" @if (!$package->image_url) hidden @endif>
                                                        <div class="edit-pic p-5" >
                                                            <img src="{{ $package->image_url }}" width="100%"  id="photo" class="img-thumbnail" >
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        {{--                                        <div class="row">--}}
                                        {{--                                            <div class="col-sm-12 col-md-6">--}}
                                        {{--                                                <div class="form-group">--}}
                                        {{--                                                    <label for="special_price">Special Price</label>--}}

                                        {{--                                                    <input id="special_price" name="special_price" type="text" class="form-control @error('special_price') is-invalid @enderror" value="{{ old('special_price', $package->special_price) }}">--}}

                                        {{--                                                    @error('special_price')--}}
                                        {{--                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price_expiry_at') }}</span>--}}
                                        {{--                                                    @enderror--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                            <div class="col-sm-12 col-md-6">--}}
                                        {{--                                                <div class="form-group">--}}
                                        {{--                                                    <label for="special_price_expiry_at">Special Price Expiry At</label>--}}

                                        {{--                                                    <input id="special_price_expiry_at" name="special_price_expiry_at" type="date" class="form-control @error('special_price_expiry_at') is-invalid @enderror" value="{{ old('special_price_expiry_at', $package->special_price_expire_at ? $package->special_price_expire_at->toDateString() : '') }}">--}}

                                        {{--                                                    @error('special_price_expiry_at')--}}
                                        {{--                                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price_expiry_at') }}</span>--}}
                                        {{--                                                    @enderror--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                        <div class="row pt-3">
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="checkbox-mini-package" name="is_mini" @if ($package->is_mini) checked @endif >
                                                    <label for="checkbox-mini-package" class="custom-control-label">Is Mini-Package</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="checkbox-crash-course" name="is_crash_course" @if ($package->is_crash_course) checked @endif >
                                                    <label for="checkbox-crash-course" class="custom-control-label">Is Crash Course</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="checkbox-pendrive"  @if ($package->pendrive) checked @endif name="pendrive"  >
                                                    <label for="checkbox-pendrive" class="custom-control-label">Pen Drive</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{--                            <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">--}}
                            {{--                                <div class="p-5">--}}
                            {{--                                    <div class="card">--}}
                            {{--                                        <div class="card-header">--}}
                            {{--                                            <div class="row">--}}
                            {{--                                                <div class="col-sm-4">--}}
                            {{--                                                    <label>Professor</label>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                            <div class="row">--}}
                            {{--                                                <div class="col-sm-4">--}}
                            {{--                                                    <div class="form-group">--}}
                            {{--                                                        <x-inputs.professor id="professor_id" class="{{ $errors->has('professor_id') ? ' is-invalid' : '' }}" style="width: 100%;">--}}
                            {{--                                                            @if(!empty(old('professor_id')))--}}
                            {{--                                                                <option value="{{ old('professor_id') }}" selected>{{ old('professor_id_text') }}</option>--}}
                            {{--                                                            @endif--}}
                            {{--                                                        </x-inputs.professor>--}}

                            {{--                                                        @if ($errors->has('professor_id'))--}}
                            {{--                                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('professor_id') }}</span>--}}
                            {{--                                                        @endif--}}
                            {{--                                                    </div>--}}
                            {{--                                                </div>--}}
                            {{--                                                <div class="col-sm-4">--}}
                            {{--                                                    <label></label>--}}
                            {{--                                                    <button id="btn-filter" class="btn btn-primary">Filter</button>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="card-body">--}}
                            {{--                                            {!! $html->table() !!}--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">--}}
                            {{--                                <div class="row p-5">--}}
                            {{--                                    <div class="col-md-3">--}}
                            {{--                                        <div class="custom-control custom-checkbox">--}}
                            {{--                                            <input class="custom-control-input" type="checkbox" id="checkbox-mini-package" name="is_mini" @if ($package->is_mini) checked @endif >--}}
                            {{--                                            <label for="checkbox-mini-package" class="custom-control-label">Is Mini-Package</label>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="col-md-3">--}}
                            {{--                                        <div class="custom-control custom-checkbox">--}}
                            {{--                                            <input class="custom-control-input" type="checkbox" id="checkbox-crash-course" name="is_crash_course" @if ($package->is_crash_course) checked @endif >--}}
                            {{--                                            <label for="checkbox-crash-course" class="custom-control-label">Is Crash Course</label>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @include('pages.image_crop.image-upload')
@stop

@push('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            let wizard = $('#new-package-wizard');
            wizard.smartWizard({
                selected: 0,
                theme: 'arrows',
                transition: {
                    animation: 'slide-horizontal',
                },
            });

            let nextBtn = $('.sw-btn-next');
            let createPackageForm = $('#form-edit-package');

            createPackageForm.validate({
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
                    price: {
                        required: true,
                        number: true,
                        maxlength: 11
                    },
                    discounted_price: {
                        number: true,
                        maxlength: 11
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
                                if ($('#special_price').val()) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        },
                    },
                    special_price: {
                        number: true,
                        maxlength: 11
                    },
                }
            });

            wizard.on("leaveStep", function(e, anchorObject, stepIndex) {
                if (stepIndex === 1) {
                    if (! createPackageForm.valid()) {
                        e.preventDefault();
                    }
                }
            });

            wizard.on("showStep", function(e, anchorObject, stepIndex) {
                console.log(stepIndex);
                if (stepIndex === 1) {
                    nextBtn.removeClass('disabled');
                    nextBtn.text('Finish');

                    nextBtn.click(function() {
                        $('#form-edit-package').submit();
                    });
                } else {
                    nextBtn.text('Next');
                }
            });

            $('.dropify').dropify();

            $('#image_file').on('dropify.afterClear', function (event, element){
                $('#image').val("")
            });

            let table = $('#dataTableBuilder').DataTable();

            $('#btn-filter').click(function(e) {
                e.preventDefault();
                table.draw();
            });

            table.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    professor: $('#professor_id').val()
                }
            });

            table.buttons('.buttons-html5').remove();

            // var uploadCrop = $('#upload-demo').croppie({
            //     enableExif: true,
            //     viewport: {
            //         width: 400,
            //         height: 200,
            //         type: 'rectangle',
            //         enableZoom : true
            //     },
            //     boundary: {
            //         width: 450,
            //         height: 250
            //     }
            // });



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

            var uploadCrop = $('#upload-demo').croppie({
                enableExif: true,
                viewport: {
                    width: 400,
                    height: 200,
                    type: 'rectangle',
                    enableZoom : true
                },
                boundary: {
                    width: 450,
                    height: 250
                }
            });

            $('#crop-btn').on('click', function (){
                uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp) {
                    $(".crop-tool").attr("hidden",false);
                    $('#photo').attr('src',resp);
                    $('#form-edit-package').on('submit', function () {
                        $('#hidden-inputs-container').html(`<input type="hidden" name="image" value="${resp}">`);
                    });
                });
            });

            $('#upload').on('change', function () {
                var filename = $(this).val().split('\\').pop();
                $('#file-text').val(filename);
                var reader = new FileReader();
                reader.onload = function (e) {
                    uploadCrop.croppie('bind', {
                        url: e.target.result
                    }).then(function(){
                        $( "#crop-btn" ).trigger( "click" );
                    });
                };
                reader.readAsDataURL(this.files[0]);
            });

            // $('#upload').on('change', function () {
            //     var filename = $(this).val().split('\\').pop();
            //     $('#file-text').val(filename);
            //     var reader = new FileReader();
            //     reader.onload = function (e) {
            //         uploadCrop.croppie('bind', {
            //             url: e.target.result
            //         }).then(function(){
            //             $('#crop-btn').on('click', function (){
            //                 uploadCrop.croppie('result', {
            //                     type: 'canvas',
            //                     size: 'viewport'
            //                 }).then(function (resp) {
            //                     $('#photo').attr('src',resp);
            //                     $('#form-edit-package').on('submit', function () {
            //                         $('#hidden-inputs-container').html(`<input type="hidden" name="image" value="${resp}">`);
            //                     });
            //                 });
            //             });
            //         });
            //     };
            //     reader.readAsDataURL(this.files[0]);
            // });
        });
    </script>
@endpush
