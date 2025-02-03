@extends('adminlte::page')

@section('title', 'Free Resources')

@section('content_header')
    <h1 class="m-0 text-dark">Update Free Resource</h1>
@stop

@section('content')
<style>
    .dt-buttons{
    display: none;
}
</style>
    <div class="row">
        <div class="col-md-9">
            <div class="card card-primary">
                <form id="form-free-resource" method="POST"
                      action="{{ route('free-resource.update', $freeResource->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                    <input type="hidden" name="free_id" id="free_id" value="{{$freeResource->id}}"/>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Course &ensp; &ensp;</label>
                                    <x-inputs.course id="course_id"
                                                     class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                        @if(!empty(old('course_id', $freeResource->course_id)))
                                            <option value="{{ old('course_id', $freeResource->course_id) }}" selected>
                                                {{ old('course_id_text', @$freeResource->course->name) }}
                                            </option>
                                        @endif
                                    </x-inputs.course>
                                    @if ($errors->has('course_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">
                                            {{ $errors->first('course_id') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Level  &ensp; &ensp;</label>
                                    <x-inputs.level id="level_id" related="#course_id">
                                        @if(!empty(old('level_id', $freeResource->level_id)))
                                            <option value="{{ old('level_id', $freeResource->level_id) }}" selected>
                                                {{ old('level_id_text', $freeResource->level->name) }}
                                            </option>
                                        @endif
                                    </x-inputs.level>
                                    @if ($errors->has('level_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">
                                            {{ $errors->first('level_id') }}
                                        </span>
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
                                        @if($freeResource->package_type_id == $type->packagetype->id))
                                            <option value="{{ old('package_type_id', $freeResource->package_type->id) }}" selected>{{ old('level_id_text', $freeResource->package_type->name) }}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                <label>Type</label>
                                    <select class="form-control" id="package_type" name="package_type">
                                    <option value="">Choose Type</option>
                                        @foreach($types as $type)
                                        @if(!empty($type->packagetype))
                                        @if($freeResource->package_type_id == $type->packagetype->id))
                                            <option value="{{ old('package_type_id', $freeResource->package_type->id) }}" selected>{{ old('level_id_text', $freeResource->package_type->name) }}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Professor</label>
                                    <x-inputs.professor  id="professor_id">
                                        @if(!empty(old('professor_id', $freeResource->professor_id)))
                                            <option value="{{ old('professor_id', $freeResource->professor_id) }}" selected>
                                                {{ old('professor_id_text', $freeResource->professor->name) }}
                                            </option>
                                        @endif
                                    </x-inputs.professor>
                                    @if ($errors->has('professor_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">
                                            {{ $errors->first('professor_id') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input id="title" class="form-control @error('title') is-invalid @enderror"
                                           name="title" type="text"
                                           value="{{ old('title', $freeResource->title) }}" placeholder="Title">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">
                                        {{ $errors->first('title') }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                       
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" class="form-control @error('description')
                                        is-invalid @enderror" name="description" placeholder="Description"
                                              rows="3">{{ old('description', $freeResource->description) }}</textarea>
                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">
                                            {{ $errors->first('description') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label>Resource Type</label>
                                <select id="resource-type" class="form-control" name="resource_type">
                                    <option></option>
                                    <option value="{{ \App\Models\FreeResource::YOUTUBE_ID }}" @if ($freeResource->type == \App\Models\FreeResource::YOUTUBE_ID) selected @endif>
                                        {{ \App\Models\FreeResource::YOUTUBE_ID_TEXT }}
                                    </option>
                                    <option value="{{ \App\Models\FreeResource::NOTES }}" @if ($freeResource->type == \App\Models\FreeResource::NOTES) selected @endif>
                                        {{ \App\Models\FreeResource::NOTES_TEXT }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-sm-6 youtube-div" hidden>
                                <label for="youtube-id">Youtube ID</label>
                                <input id="youtube-id"  class="form-control @error('youtube_id') is-invalid @enderror"
                                       name="youtube_id" type="text" value="{{ old('youtube_id', $freeResource->youtube_id) }}"
                                       placeholder="Youtube ID">
                                @error('youtube_id')
                                <span class="invalid-feedback" role="alert" style="display: inline;">
                                    {{ $errors->first('youtube_id') }}
                                </span>
                                @enderror
                            </div>
                            </div>
                              <!---Added BY TE-->
                              <div class="row youtube-div-package" hidden>
                              <div class="col-sm-6  mt-3" >
                                  Linked Package: 
                                  @if(!empty($sel_pkgs))
                                    @foreach($sel_pkgs as $sel_pk)
                                   <p class="link-pkg_{{$sel_pk->id}}">{{$sel_pk->name}}<a href="#" class="unlink_package" id="{{$sel_pk->id}}"><i class="fas fa-trash ml-3"></i></a></p>
                                   @endforeach
                                    @endif
                              </div>
                            <div class="col-sm-6  mt-3">
                            <button type="button" class="btn btn-warning sel-pkg" onclick="show_pack()">Edit Packages</button>
                            </div>
                            <input name="demo_package_id" id="demo_package_id" type="hidden" value=""/>
                            <div class="pkg-list" hidden>
                           
                            {!! $html->table(['id' => 'demopack'], true) !!}
                           
                            </div>
                            </div>
                            <!----end TE Modification-->
                            <div class="col-sm-6 document-div" hidden>
                                <label>Document</label>
                                <div class="custom-file">
                                    <input id="document" class="custom-file-input @error('document')
                                        is-invalid @enderror" name="document" type="file" accept="application/pdf">
                                    <label for="document" class="custom-file-label">Choose file</label>
                                    @error('document')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">
                                        {{ $errors->first('document') }}
                                    </span>
                                    @enderror
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
@stop

@push('js')
{!! $html->scripts() !!}
    <script>
        $(function() {
            $('#resource-type').select2({
                placeholder: 'Select Type of Resource'
            });
            $('#package_type').select2({
                placeholder: 'Type'
            });
            $('#resource-type').trigger('change');

            $(".custom-file-input").on("change", function() {
                let fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });

            $('#form-free-resource').validate({
                rules: {
                    title: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    resource_type: {
                        required: true
                    },
                    youtube_id: {
                        required: true
                    }
                }
            });

            let table = $('#demopack');
            table.DataTable().draw();
            $('#resource-type').change(function() {
                let value = $(this).val();
                let youtubeDiv = $('.youtube-div');
                let documentDiv = $('.document-div');

                if (value === '1') {
                    youtubeDiv.attr('hidden', false);
                    documentDiv.attr('hidden', true);
                    $(".youtube-div-package").attr('hidden',false);
                   $(".pkg-list").attr('hidden',true);
                }

                if (value === '3') {
                    youtubeDiv.attr('hidden', true);
                    documentDiv.attr('hidden', false);
                    $(".youtube-div-package").attr('hidden',true);
                   $(".pkg-list").attr('hidden',true);
                }
            }).change();
        });
        function show_pack(){
         $(".pkg-list").attr('hidden',false);
          
        }

        $(document).on('click', '[name="packages"]', function () {
            var p_id= $(this).val();
            $("#demo_package_id").val(p_id);
        });
    </script>
     <script>
    $(function() {
        $(".unlink_package").click(function () {
            let confirmation = confirm("Are you sure to unlink this package");
              var  id=$('#free_id').val();
              var pk_id= this.id;
            if (confirmation) {
                $.ajax({
                    url: "{{ url('unlink_demo_package') }}",
                    type: "POST",
                    dataType :"JSON",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        package_id:pk_id,
                    },
                    success: function(result) {
                        if (result) {
                            $(".link-pkg_"+pk_id).attr('hidden',true);
                        }
                    }
                });
            }
        });
    });
    
    $('#level_id').on('change', function () {
                var LevelID = $(this).val();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#package_type').empty();
                        //$('#level').append('<option disabled selected>  Choose Level </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#package_type').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                        });

                    }
                });
                } else {
                    $('#package_type').empty();
                }
            });
</script>
@endpush
