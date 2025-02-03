@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <div class="row">
        <div class="col-sm-8">
            <h1 class="m-0 text-dark">{{$package->name}}</h1>
        </div>
        <div class="col">
            <a href="{{route('packages.prebook-package.show', $package->id)}}" class="btn btn-sm btn-primary float-right">Prebook</a>
            <a href="{{ route('packages.chapter.edit', $package->id) }}" class="btn btn-sm btn-primary float-right mr-3"><i class="fas fa-edit"></i></a>
            @if ($package->is_approved)
                <a href="#modal-un-publish" data-toggle="modal" class="btn btn-sm btn-danger float-right mr-3" title="Un-Publish"><i class="fas fa-times"></i> Un-Publish</a>
            @else
                <a href="#" id="modal-publish-button" class="btn btn-sm btn-success float-right mr-3" title="Publish"><i class="fas fa-check"></i> Publish</a>
            @endif
        </div>
    </div>
@stop

@section('content')

        <div class="card card-widget bg-primary">
            <div class="card-header">Package Details</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Type</h5>
                        <p>Chapter Level</p>
                    </div>
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Price</h5>
                        @if($package->is_prebook!=1)
                        @foreach ($package->strike_prices as $price)
                            <span><del>₹{{ $price }}, </del></span>
                        @endforeach
                        <span>₹{{ $package->selling_price }}</span>
                        @else
                    @php
                    $test = [];
                    foreach ($package->strike_prices as $data)
                    {
                        $test[] = $data;
                    }
                    $result = collect($test)->all();                    
                    $price = implode(', ', $result);                    
                    if($price != null) {
                        $c=count($result);
                            if($c>=1){
                                $dip_amount=last($result);
                                $price_x=array_pop($result);
                                $price = implode(', ', $result);
                                echo '<del>'.$price.'</del>';
                                if(!empty($price)){
                                echo '<br>';
                                }echo  $dip_amount;
                            }
                    }
                        @endphp
                    @endif
                    </div>
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Total Videos</h5>
                        <span>{{ $package->total_videos }}</span>
                    </div>
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Total Duration</h5>
                        <p>{{ $package->total_duration_formatted }}</p>
                    </div>
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Expiry Date</h5>
                        <p><?php if($package->expiry_type=='1')  echo $package->expiry_month.' Months'; elseif($package->expiry_type=='2') echo $package->expire_at->toFormattedDateString(); ?></p>
                    </div>
                    <div class="col-md-2 description-block">
                        <h5 class="description-header">Language</h5>
                        <p>{{ $package->language->name ?? '' }}</p>
                    </div>
                </div>
                <div class="row border-top">
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Attempt</h5>
                        <p>{{ $package->attempt->format('F Y') ?? '' }}</p>
                    </div>
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Duration</h5>
                        <p> {{ $package->duration }} Times</p>
                    </div>
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Professor Revenue</h5>
                        <p>  {{ $package->professor_revenue }}%</p>
                    </div>
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Study Material Price</h5>
                        <p>{{ $package->study_material_price }}</p>
                    </div>
                    <div class="col-md-2 description-block border-right">
                        <h5 class="description-header">Is Published</h5>
                        @if ($package->is_approved)
                            <i class="fas fa-check"></i>
                           
                        @else
                            <i class="fas fa-times"></i>
                        @endif
                    </div>
                    <div class="col-md-2 description-block">
                        <h5 class="description-header">Published By</h5>
                        @if ($package->is_approved)
                            {{ $package->user->name ?? '' }}
                            <p> <?php if($package->published_at) echo "On ". date('d-m-Y',strtotime($package->published_at)); ?></p>
                        @endif
                    </div>
                </div>
                
                <div class="row border-top">
                    <div class="col-md-12 description-block ">
                        <h5 class="description-header">Description</h5>
                        <p style="text-align: left;"> {!! nl2br($package->description) !!}</p>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card card-custom-height card-widget bg-primary">
                    <div class="card-header">Professors</div>
                    <div class="card-body">
                        <div class="row ">
                            @foreach($professorNames as $key => $professorName)
                                <div class="col-md-6">
                                    <p class="professors">{{$key+1}}. {{$professorName}}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if(count($professorNames)>10)
                                    <a href="" class="text-white" data-toggle="modal" data-target="#professorModal">More...</a>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @if($package->is_prebook)
                <div class="card card-widget card-custom-height bg-primary">
                    <div class="card-header">Prebook Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 description-block border-right">
                                <h5 class="description-header">Is Prebook</h5>
                                <p>@if($package->is_prebook)
                                        <i class="fas fa-check"></i>
                                    @else
                                        <i class="fas fa-times"></i>
                                    @endif</p>
                            </div>
                            <div class="col-md-6 description-block">
                                <h5 class="description-header">Launch Date</h5>
                                <p>{{ $package->prebook_launch_date->toFormattedDateString() ?? '' }}</p>
                            </div>
                        </div>
                        <div class="row border-top">
                            <div class="col-md-6 description-block border-right">
                                <h5 class="description-header">Price</h5>
                                @foreach ($package->strike_prices as $price)
                                    <span><del>₹{{ $price }}, </del></span>
                                @endforeach
                                <span>₹{{ $package->selling_price }}</span>
                            </div>
                            <div class="col-md-6 description-block">
                                <h5 class="description-header">Booking Amount</h5>
                                <p>{{ $package->booking_amount }}</p>
                            </div>

                        </div>
                    </div>
                </div>
                @else
                    <div class="card card-widget card-custom-height bg-primary">
                        <div class="card-header">Prebook Details</div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-6 description-block">
                                    <h5 class="description-header">Prebook not enabled</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-3">
                @if($package->is_freemium)
                <div class="card card-widget card-custom-height bg-primary">
                    <div class="card-header">Freemium Package</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 description-block border-right">
                                <h5 class="description-header">Freemium Package Enabled</h5>
                                <p>@if($package->is_freemium)
                                        <i class="fas fa-check"></i>
                                    @else
                                        <i class="fas fa-times"></i>
                                    @endif</p>
                            </div>
                            <div class="col-md-6 description-block">
                                <h5 class="description-header">Freemium Percentage</h5>
                                <p>{{ $package->freemium_content ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="card card-widget card-custom-height bg-primary">
                        <div class="card-header">Freemium Package</div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-6 description-block">
                                    <h5 class="description-header">Freemium Package Disabled</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-3">
                <div class="card card-widget card-custom-height bg-primary">
                    <div class="card-header">Image</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 description-block">
                                <img class="align-self-center" width="75%"  title="{{$package->title_tag}}" alt="{{$package->alt}}" src="{{$package->image_url ?? asset('images/placeholder.png')}}">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row">
                            <div class="col text-left">
                                <h3>Videos</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{url("packages/$package->id/videos/order")}}" type="button" class="btn btn-success mr-2">Change Order</a>
                                <a href="{{url("packages/$package->id/videos")}}" type="button" class="btn btn-warning">Add/Edit</a>
                            </div>
                        </div>
                        <form id="search-video-form">
                            <div class="row mt-3">
                                <div class="col-sm-4">
                                    <input id="video-name" type="text" class="form-control" placeholder="Search Title">
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" id="button-video-filter" class="btn btn-primary">Filter</button>
                                    <button type="button" class="btn btn-primary ml-2" id="button-video-clear">Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        {!! $videodatatable->table(['id' => 'chapter-videos'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row">
                            <div class="col text-left">
                                <h3>Study Materials</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{url("packages/$package->id/study-materials")}}" type="button" class="btn btn-warning">Add/Edit</a>
                            </div>
                        </div>

                        <form id="search-form">
                            <div class="row mt-3">
                                <div class="col-sm-2">
                                    <input id="text-search" type="text" class="form-control" placeholder="Search Title">
                                </div>
                                <div class="col-sm-2">
                                    <select id="select-type" style="width: 100%">
                                        <option value=""></option>
                                        <option value="{{ \App\Models\StudyMaterialV1::STUDY_MATERIALS }}">{{ \App\Models\StudyMaterialV1::STUDY_MATERIALS_TEXT }}</option>
                                        <option value="{{ \App\Models\StudyMaterialV1::STUDY_PLAN }}">{{ \App\Models\StudyMaterialV1::STUDY_PLAN_TEXT }}</option>
                                        <option value="{{ \App\Models\StudyMaterialV1::TEST_PAPER }}">{{ \App\Models\StudyMaterialV1::TEST_PAPER_TEXT }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select id="select-language" style="width: 100%">
                                        <option value=""></option>
                                        @foreach (\App\Models\Language::all() as $language)
                                            <option value="{{ $language->id }}">{{ $language->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <x-inputs.professor id="select-professor" class="{{ $errors->has('professor_id') ? ' is-invalid' : '' }}" style="width: 100%;">
                                            @if(!empty(old('professor_id')))
                                                <option value="{{ old('professor_id') }}" selected>{{ old('professor_id_text') }}</option>
                                            @endif
                                        </x-inputs.professor>

                                        @if ($errors->has('professor_id'))
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('professor_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" id="button-filter" class="btn btn-primary">Filter</button>
                                    <button type="button" class="btn btn-primary ml-2" id="button-clear">Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        {!! $html->table(['id' => 'datatable'], true) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-publish" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ url("packages/$package->id/publish") }}">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="d-none text-danger" id="empty-study-material"> <i class="fas fa-check"></i>There is no study materials added with this package</p>
                        <p>Are you sure you want to publish this package?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Yes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-un-publish" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ url("packages/$package->id/publish") }}">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to un-publish this package?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Yes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-incomplete-package" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                @csrf
                <div class="modal-header">
                    <h3>Incomplete Package</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Add videos or packages </p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-notification" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-notification" method="POST" action="{{ route('notifications.store') }}">
                    @csrf
                    <input name="package_id" type="hidden" value="{{ session()->get('notification.package_id') }}">
                    <div class="modal-header">
                        <h4 class="modal-title">NOTIFICATION</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input class="form-control" id="title" name="title" placeholder="Title" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="body">Body</label>
                                    <textarea class="form-control" id="body" name="body" rows="5" placeholder="Body"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">SEND</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop


@section('js')
    {!! $html->scripts() !!}
    {!! $videodatatable->scripts() !!}

    <script>
        $(function() {
            var package_video_count = {{$package_video_count}};

            var study_material_count = {{$study_material_count}};

            var is_prebook = {{$package->is_prebook}};

            $('.buttons-html5').remove();

            let tableStudyMaterials = $('#datatable').DataTable();
            tableStudyMaterials.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#text-search').val(),
                    type: $('#select-type').val(),
                    language: $('#select-language').val(),
                    professor: $('#select-professor').val(),
                }
            });

            $('#button-filter').click(function (e) {
                e.preventDefault();
                tableStudyMaterials.draw();
            });

            $('#button-clear').click(function () {
                $('#text-search').val('');
                $('#select-type').val("").trigger('change');
                $('#select-language').val("").trigger('change');
                $('#select-professor').val("").trigger('change');
                tableStudyMaterials.draw();
            });



            $('#modal-publish-button').click(function () {

                if(package_video_count>0 || is_prebook) {
                    if(study_material_count == 0){
                        $("#empty-study-material").removeClass('d-none')
                        $("#modal-publish").modal('show');
                    }
                    else {
                        $("#modal-publish").modal('show');
                    }

                }
                else{
                    $("#modal-incomplete-package").modal('toggle');
                }
            });


            $('#select-type').select2({
                placeholder: 'Choose Type'
            });

            $('#select-language').select2({
                placeholder: 'Choose Language'
            });


            let tableVideo = $('#chapter-videos').DataTable();
            tableVideo.on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#video-name').val(),
                }
            });

            $('#button-video-filter').click(function (e) {
                e.preventDefault();
                tableVideo.draw();
            });

            $('#button-video-clear').click(function () {
                $('#video-name').val(''),
                tableVideo.draw();
            });

            @if (session()->has('notification'))
                $('#modal-notification').modal('toggle');
            @endif

            $('#form-notification').validate({
                rules: {
                    title: {
                        required: true
                    },
                    body: {
                        required: true
                    }
                }
            });
        });
    </script>
@stop

