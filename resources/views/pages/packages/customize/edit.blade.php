@extends('adminlte::page')

@section('title', 'Update Customized Package')

@section('content_header')
<style>
    .dt-buttons{
        display:none;
    }
    .select2-container {
  
    display: block;
   
}
#datatablec_length {
    display: none;
}
#datatablec_filter {
    display: none;
}
    </style>
    <div class="row">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('packages', $package->id) }}">{{ $package->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Customized Package</li>
                </ol>
            </nav>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('packages.customize.update', $package->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                        @if(!empty(old('course_id', $package->course_id)))
                                            <option value="{{ old('course_id', $package->course_id) }}" selected>{{ old('course_id_text', $package->course->name) }}</option>
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
                                        @if(!empty(old('level_id', $package->level_id)))
                                            <option value="{{ old('level_id', $package->level_id) }}" selected>{{ old('level_id_text', $package->level->name) }}</option>
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
                                    <select class="form-control" id="package_type" name="package_type">
                                        <option value="">Choose Type</option>
                                        @foreach($types as $type)
                                        @if(!empty($type->packagetype))
                                        <option value="{{$type->packagetype->id}}" @if($package->package_type == $type->packagetype->id) selected @endif>{{$type->packagetype->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Package Name</label>
                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Language</label>
                                    <x-inputs.language id="language_id" related="#language_id" style="width: 100%;">
                                        @if(!empty(old('language_id', $package->language_id)))
                                            <option value="{{ old('language_id', $package->language_id) }}" selected>{{ old('language_id_text', $package->language->name) }}</option>
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
                                    <textarea  id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $package->description) }}</textarea>
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

                                    <input id="professor_revenue" name="professor_revenue" required type="number" min="0" class="form-control @error('professor_revenue') is-invalid @enderror" value="{{ old('professor_revenue', $package->professor_revenue) }}">

                                    @error('professor_revenue')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('professor_revenue') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="price">Price</label>

                                    <input id="price" name="price" type="number"  min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $package->price) }}">

                                    @error('price')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('price') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="is_freemium">Publish Free Trial</label>
                                    <div class="input-group"> 
                                        <div class="col">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="radio" id="is_freemium_enable" name="is_freemium" value="1" {{ $package->is_freemium === 1 ? 'checked' : '' }}>
                                                <label for="is_freemium_enable" class="custom-control-label">Enable</label>
                                            </div>
                                        </div>                                                    
                                        
                                        <div class="col">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="radio" id="is_freemium_disable" name="is_freemium" value="0" {{ $package->is_freemium === 0 ? 'checked' : '' }}>
                                                <label for="is_freemium_disable" class="custom-control-label">Disable</label>
                                            </div>
                                        </div>                                                    
                                    </div>
                                    @error('is_freemium')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('is_freemium') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="freemium_content">Freemium Percentage</label>

                                    <div class="input-group">
                                        @if(!empty($package->freemium_content))
                                            <input id="freemium_content" name="freemium_content" type="number" min="0" class="form-control @error('freemium_content') is-invalid @enderror" value="{{ old('freemium_content', $package->freemium_content ) }}">
                                        @else
                                            <input id="freemium_content" name="freemium_content" type="number" min="0" class="form-control @error('freemium_content') is-invalid @enderror" value="{{ old('freemium_content', 10 ) }}">
                                        @endif
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="freemium-content-percentage">0%</span>
                                        </div>
                                    </div>

                                    @error('freemium_content')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('freemium_content') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="discounted_price">Discounted Price</label>

                                    <div class="input-group">
                                        <input id="discounted_price" name="discounted_price" type="number" min="0" class="form-control @error('discounted_price') is-invalid @enderror" value="{{ old('discounted_price', $package->discounted_price) }}">
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

                                    <input id="discounted_price_expiry_at" name="discounted_price_expiry_at" type="date" class="form-control @error('discounted_price_expiry_at') is-invalid @enderror" value="{{ old('discounted_price_expiry_at', $package->discounted_price_expire_at ? $package->discounted_price_expire_at->toDateString() : '') }}">

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
                                        <input id="special_price" name="special_price" type="number"  min="0" class="form-control @error('special_price') is-invalid @enderror" value="{{ old('special_price', $package->special_price) }}">
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
                                    <input id="special_price_active_from" name="special_price_active_from" type="date" class="form-control @error('special_price_active_from') is-invalid @enderror" value="{{ old('special_price_active_from', $package->special_price_active_from ? $package->special_price_active_from->toDateString() : '') }}">
                                    @error('special_price_expiry_at')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price_active_from') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="special_price_expiry_at">Special Price Expiry At</label>
                                    <input id="special_price_expiry_at" name="special_price_expiry_at" type="date" class="form-control @error('special_price_expiry_at') is-invalid @enderror" value="{{ old('special_price_expiry_at', $package->special_price_expire_at ? $package->special_price_expire_at->toDateString() : '') }}">
                                    @error('special_price_expiry_at')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price_expiry_at') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="attempt">Attempt</label>
                                <input id="attempt" name="attempt" type="text" class="form-control" placeholder="Attempt" value="{{ $package->attempt ? Carbon\Carbon::parse($package->attempt)->format('m-Y') : '' }}">
                                @error('attempt')
                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('attempt') }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="duration">Duration</label>

                                <input id="duration" name="duration" type="text" class="form-control" placeholder="Duration" value="{{ $package->duration ? $package->duration : '' }}">
                            
                               
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
                                    <option value="1" @if($package->expiry_type ==1) selected @endif>
                                        Month
                                    </option>
                                    <option value="2"  @if($package->expiry_type ==2) selected @endif>
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
                                    <option value="{{$i}}" @if($package->expiry_month ==$i) selected @endif >{{$i}}</option>
                                    @endfor
                                    </select>
                                </div>
                                <div class="form-group date-div" hidden>
                                    <label for="alt">Expiry date</label>
                                    <input class="form-control" type="date" min="" name="expiry_date" value="{{ old('expiry_date', $package->expire_at ? $package->expire_at->toDateString() : '') }}"/>
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="alt">Alternative Text for Image</label>
                                    <input class="form-control @error('alt') is-invalid @enderror" id="alt"
                                           name="alt" type="text" value="{{ old('alt', $package->alt) }}"
                                           placeholder="Alt">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('alt') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="study_material_price">Study Material Price</label>
                                    <input class="form-control @error('study_material_price') is-invalid @enderror" id="study_material_price"
                                           name="study_material_price" type="text" value="{{ old('study_material_price', $package->study_material_price) }}"
                                           placeholder="Study Material Price">
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
                                    @if ($package->image)
                                        <div class="row mb-3">
                                            <div class="col-sm-12">
                                                <img src="{{ $package->image_url }}">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="custom-file">
                                        <input class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" type="file" accept="image/*">
                                        <label class="custom-file-label" for="image">{{ $package->image ?? '' }}</label>
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
                                    <label for="image">Video</label>
                                    <div class="row mb-3">
                                         <input type="hidden" id="video_id" name="video_id" value="{{@$video_details->id }}" >
                                            <div class="col-sm-12" id="video_details">
                                          @if($video_id!=0)
                                          <a class="popup-iframe dropdown-item" href="{{route('videos.show', $video_details->id)}}">
        <i class="fas fa-play"></i>{{ $video_details->title}}
    </a> <span><a href="javascript: void(0)" class="unlink_video"   id="{{$package->id}}"><i class="fas fa-trash ml-3"></i></a></span>
                                          @endif
                                            </div>
                                        </div>
                                                         <div class="row">
                                        <div class="col-sm-12">
                                        <button type="button" class="btn btn-warning sel-pkg" data-toggle="modal" data-target="#myModal"">Select Video</button>
                                        </div>
                                    </div> 
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox" style="top: 6px;">
                                    <input class="custom-control-input" type="checkbox" id="checkbox-cseet" name="cseet" value="1"  @if ($package->is_cseet) checked @endif>
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
                                        @if(count($packageFeatures)>0)
                                            @foreach($packageFeatures as $packageFeature)
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input class="form-control description char-limit" name="features[]" id="features" placeholder="Please Enter Feature" maxlength="250" value="{{ $packageFeature->feature }}" >

                                                                <div class="input-group-append">
                                                                    @if($loop->first)
                                                                        <button class="btn btn-success button-add-feature" type="button"><i class="fas fa-plus"></i></button>
                                                                    @else
                                                                        <button class="btn btn-danger button-remove-feature" type="button"><i class="fas fa-trash"></i></button>
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="feature-container">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="radio" id="checkbox-full-package" name="type" value="full" @if (! $package->is_mini && ! $package->is_crash_course) checked @endif>
                                    <label for="checkbox-full-package" class="custom-control-label">Is Full-Package</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="radio" id="checkbox-mini-package" value="mini" name="type" @if ($package->is_mini) checked @endif>
                                    <label for="checkbox-mini-package" class="custom-control-label">Is Mini-Package</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="radio" id="checkbox-crash-course" value="crash" name="type" @if ($package->is_crash_course) checked @endif>
                                    <label for="checkbox-crash-course" class="custom-control-label">Is Crash Course</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkbox-pendrive" name="pendrive" @if ($package->pendrive) checked @endif>
                                    <label for="checkbox-pendrive" class="custom-control-label">Pen Drive</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkbox-g-drive" name="g-drive" @if ($package->g_drive) checked @endif>
                                    <label for="checkbox-g-drive" class="custom-control-label">G-Drive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Update</button>
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
    <!---Video modal----->
    <div class="modal" id="myModal">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        
        <h4 class="modal-title">Select Video</h4>
        <button type="button" class="btn btn-warning sel-pkg" id="add-video" disabled data-dismiss="modal">Add</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <div class="card-header">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Filter</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select id="select-professor" class="form-control">
                                                    <option></option>
                                                    @foreach(\App\Models\Professor::orderby('name','asc')->get() as $professor)
                                                        <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="course">
                                                <option value=""></option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select name="level_id" id="level"  class="form-control select-level" style="width: 100% !important;">
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="package_type_m" name="package_type" style="width: 100%">
                                            <option value="">Choose Type</option>                                            
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="subject_m">

                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select id="select-chapter" class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <select id="select-module" class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control" id="search" type="text" placeholder="Search" title="Video name or Video description">
                                        </div>
                                        <div class="col-sm-4">
                                            <label></label>
                                            <button id="btn-filter" class="btn btn-primary">Filter</button>
                                            <label></label>
                                            <button id="btn-clear" class="btn btn-primary">Clear</button>
                                        </div>
                                    </div>
                                </div>
      <div class="table-responsive">
      {!! $html->table(['id' => 'datatablec'], true) !!}
                                </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger modal-close" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
@stop

@section('js')
{!! $html->scripts() !!}
    <script>
         $('#course').on('change', function () {
            $('#level').empty();
            $('#package_type_m').empty();
            $('#subject_m').empty();
            $('#select-chapter').empty();
            $('#select-module').empty();
            var CourseID = $(this).val();

            if (CourseID) {
                $.ajax({
                    url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    //    $('#level').empty();
                        $('#level').append('<option disabled selected>  Choose Level </option>');
                        $.each(data, function (key, value) {
                            $('#level').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
            //    $('#level').empty();
            }
        });

        var package_type;
            $('#level').on('change', function () {
                $('#package_type_m').empty();
                $('#subject_m').empty();
                $('#select-chapter').empty();
                $('#select-module').empty();
                var LevelID = $(this).val();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    //    $('#package_type_m').empty();
                        $('#package_type_m').append('<option disabled selected>  Choose Type </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#package_type_m').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                        });
                        getSubjectM(package_type,LevelID);
                    }
                });
              

                } else {
                //    $('#package_type_m').empty();
                }
            });

            $('#package_type_m').on('change', function () {
                $('#subject_m').empty();
                $('#select-chapter').empty();
                $('#select-module').empty();
                var package_type = $(this).val();
                var level_id=$("#level").val();
                if(package_type && level_id){
                    getSubjectM(package_type,level_id);

                }
            });
            function getSubjectM(package_type,level_id){
               
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
                  
                //    $('#subject_m').empty();
                    if(response.length>0){
                        $('#subject_m').append('<option disabled selected>  Choose Subject </option>');
                       
                        $.each(response, function( index, value ) {
                            var item = value.id;
                           
                           
                            $('#subject_m').append('<option value="' + value.id + '">' + value.name + '</option>');

                        });
                        // $("#no_subjects_available").addClass('d-none');
                    }
                    else{
                       
                    }

                });
            }

        $('#subject_m').on('change', function () {
            $('#select-chapter').empty();
            $('#select-module').empty();
            var SubjectID = $(this).val();
            SubjectChapters(SubjectID);
        });

        function SubjectChapters(SubjectID) {
            if (SubjectID) {
                $.ajax({
                    url: '{{ url('/subject-chapters/ajax') }}' + '/' + SubjectID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    //    $('#select-chapter').empty();
                        $('#select-chapter').append('<option disabled selected>  Choose Chapter </option>');
                        $.each(data, function (key, value) {
                            $('#select-chapter').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }
                });
            } else {
            //    $('#select-chapter').empty();
            }
        }

        $('#select-chapter').on('change', function () {
            $('#select-module').empty();
            var ChapterID = $(this).val();
            ChapterModules(ChapterID);
        });

        function ChapterModules(ChapterID){
            if(ChapterID){
                $.ajax({
                    url: '{{ url('/chapter-module/ajax') }}' + '/' + ChapterID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    //    $('#select-module').empty();
                        $('#select-module').append('<option disabled selected>  Choose Module </option>');
                        $.each(data, function (key, value) {
                            $('#select-module').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }
                });
            }else{
            //    $('#select-module').empty();
            }
        }

        $(document).ready(function () {
            $('#select-chapter').select2({
                placeholder: 'Chapter'
            });

            $('#select-professor').select2({
                placeholder: 'Professor'
            });

            $('#select-module').select2({
                placeholder: 'Module'
            });

            $('#course').select2({
                placeholder: 'Course'
            });

            $('#level').select2({
                placeholder: 'Level'
            });
            $('#subject_m').select2({
                placeholder: 'Subject'
            });
            $('#subject').select2({
                placeholder: 'Subject'
            });
            $('#package_type').select2({
                placeholder: 'Type'
            });
            $('#package_type_m').select2({
                placeholder: 'Type'
            });
        let tableDraft = $('#datatablec').DataTable();

tableDraft.on('preXhr.dt', function(e, settings, data) {
    data.filter = {
        chapter: $('#select-chapter').val(),
        professor: $('#select-professor').val(),
        module: $('#select-module').val(),
        course: $('#course').val(),
        level: $('#level').val(),
        subject: $('#subject').val(),
        search: $('#search').val(),
        package_type: $('#package_type_m').val(),
        subject: $('#subject_m').val(),
        
    }
});

$('#btn-filter').click(function() {
    tableDraft.draw();
});
$('#btn-clear').click(function() {
                $('#select-chapter').val('').trigger('change');
                $('#select-professor').val('').trigger('change');
                $('#select-module').val('').trigger('change');
                $('#course').val('').trigger('change');
                $('#level').val('').trigger('change');
                $('#subject').val('').trigger('change');
                $('#search').val('').trigger('change');
                $('#package_type_m').val('').trigger('change');
                tableDraft.draw();
            });
            $(".modal-close").click(function(){
              
           //    $("#video_id").val('');
});
            
          $("#add-video").click(function(){
            var video_id = $("input[type=radio][name=videos]:checked").val();
          $("#video_id").val(video_id);
            $.ajax({
                          url: '{{ url('packages/getvideodetails') }}' + '/' + video_id,
                          type: "GET",
                          dataType: "html",
                          success: function (data) {
                              $('#video_details').show();
                            $("#video_details").html(data);
                            activatePopup();
                          }
                      });

          });
          $(document).on('click', '.video', function () {
              $('#add-video').removeAttr('disabled');
          });
            $('#create').validate({
                rules: {
                    course_id: {
                        required: true
                    },
                    level_id: {
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

            $(document).on('input', '#freemium_content', function() {
                let freemiumContent = $(this).val();
                let percentage = parseInt(100 - (parseInt(freemiumContent)) );

                if (isNaN(percentage) || percentage < 0) {
                    percentage = 0;
                }

                $('#freemium-content-percentage').text(percentage + '%');
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


            var packageFeatureArray = {!! json_encode($packageFeatures->toArray()) !!};

            var packageIndex;

            if(packageFeatureArray.length == 0){
                packageIndex = 1;
                cloneFeature();
            }
            else{
                packageIndex = packageFeatureArray.length + 1;
            }


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
            $(function () {
            if ($('.popup-iframe').length) {
                $('.popup-iframe').magnificPopup({
                  
                    disableOn: 700,
                    type: 'iframe',
                    mainClass: 'mfp-fade',
                    removalDelay: 160,
                    preloader: false,
                    fixedContentPos: false
                });
            }

            $(".unlink_video").click(function () {
            let confirmation = confirm("Are you sure to unlink this video");
              var  id=$('#video_id').val();
              var pk_id=this.id;
            
              if (confirmation) {
               
                $.ajax({
                    url: "{{ url('unlink_demo_video') }}",
                    type: "POST",
                    dataType :"JSON",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        package_id:pk_id,
                    },
                    success: function(result) {
                        if (result) {
                            location.reload();
                        }
                    }
                });
            }
            
              
            });
           
        }); $(document).on('click', '#unlink_video', function () {
            $('#video_id').val(0);
            $('#video_details').hide();
            });

           function activatePopup(){
            $('.popup-iframe').magnificPopup({
                  
                  disableOn: 700,
                  type: 'iframe',
                  mainClass: 'mfp-fade',
                  removalDelay: 160,
                  preloader: false,
                  fixedContentPos: false
              });
           }
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
    </script>
@stop
