@extends('adminlte::page')

@section('title', 'Upload Video')

@section('content_header')
    <h1 class="m-0 text-dark">Upload Video</h1>
@stop

@section('content')
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form role="form" id="form-rulebook-create" method="POST" action="{{ route('rule-book.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">    
                                    <label for="package_id">Search for a Package:</label>
                                    <select class="form-control select2" id="package_id" name="package_id">
                                        <option value="">Search & Choose Package</option>
                                       
                                    </select>

                                    @if ($errors->has('package_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('package_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">    
                                    <label for="title">Rulebook title:</label>
                                    <input type="text" class="form-control" id='title' name="title" placeholder="Title" >

                                    @if ($errors->has('title'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
               
                        <div class="folder-container">
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="rulebook">Rulebook</label>
                                        <div class="input-group">
                                            <input id="rulebook" name="rulebook" type="text" class="form-control" placeholder="Rulebook Files" readonly value="{{ old('rulebook') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button" id="button-addon2" data-toggle="modal" data-target="#modal-choose-rulebook">Choose</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label for="uploadS3">Upload to s3</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="uploadS3" name="file" @error('file') is-invalid @enderror>
                                                <label class="custom-file-label" for="uploadS3">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="progress" style="display:none;">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                        </div>
                                        <div id="message"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('pages.rulebook.choose-folder-modal-s3')
@stop

@section('js')
    <script>
        $(document).ready(function () {

            $('#form-rulebook-create').validate({
                rules: {
                    package_id: {
                        required: true
                    },
                    rulebook: {
                        required: true
                    },
                    title: {
                        required: true
                    },
                },
                messages: {
                    video_count: {
                        required: "Please Select a valid Rulebook",
                    }
                }

            });

            $('#modal-choose-rulebook').on('rulebook_choose', function (e, path) {
                // Remove extra spaces before or after slashes
                path = path.trim().replace(/\s*\/\s*/g, '/');
                // Replace spaces between folder names with a single underscore
                path = path.replace(/\s+/g, '_');
                // Remove multiple consecutive slashes
                path = path.replace(/\/{2,}/g, '/');

                $('#rulebook').val(path);
            });

            $('#uploadS3').change(function(){
                $('.progress').show();
                $('#message').html('');
                var file = $(this).prop('files')[0];

                if(file == undefined){
                    $('#message').html('<div class="alert alert-danger">Please select a file to upload.</div>');
                    return false;
                }

                var ext = file.name.split('.').pop().toLowerCase();
                if($.inArray(ext, ['pdf']) == -1) {
                    $('#message').html('<div class="alert alert-danger">Please select a valid pdf file to upload.</div>');
                    return false;
                }                

                var formData = new FormData();
                formData.append('file', file);

                $.ajax({
                    type:'POST',
                    url: "{{ route('s3rulebook.uploadS3.index') }}",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    xhr: function(){
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e){
                            if(e.lengthComputable){
                                var percent = Math.round((e.loaded / e.total) * 98);
                                $('.progress-bar').attr('aria-valuenow', percent).css('width', percent+'%').text(percent+'%');
                            }
                        });
                        return xhr;
                    },
                    success:function(data){
                        console.log(data);
                        $('.progress').hide();
                        selectedRulebook = data.url;
                        let $modal = $('#modal-choose-rulebook');
                        $modal.trigger('rulebook_choose', [selectedRulebook]);
                        $('#message').html('<div class="alert alert-success">'+data.success+'</div>');
                        $('#uploadS3').val('');
                    },
                    error: function(xhr, status, error){
                        $('.progress').hide();
                        var errorMessage = xhr.status + ': ' + xhr.statusText
                        $('#message').html('<div class="alert alert-danger">'+errorMessage+'</div>');
                        $('#uploadS3').val('');
                    }
                });
            });
     
            $('.select2').select2({
                ajax: {
                    url: "{{ route('search.rulebook.packages') }}",
                    dataType: 'json',
                    delay: 0,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                placeholder: 'Search for a Package',
                minimumInputLength: 2,
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: formatPackage,
                templateSelection: formatPackageSelection
            });

            function formatPackage(package) {
                if (package.loading) {
                    return package.text;
                }
                var markup =
                    '<div class="clearfix">' +
                    '<div>' + package.name + '</div>' +
                    '</div>';
                return markup;
            }

            function formatPackageSelection(package) {
                return package.name || package.text;
            }

            $('#package_id').on('select2:select', function (e) {
                var selectedPackage = e.params.data;
                // Send an AJAX request to check if the selected package exists in package_rulebook table
                $.ajax({
                    type: 'GET',
                    url: '{{ route("check.package.rulebook.exists") }}',
                    data: {
                        package_id: selectedPackage.id
                    },
                    success: function (data) {
                        if (data.exists) {
                            // Package exists in the package_rulebook table
                            alert('Package already exists in the package_rulebook table!');
                            // Clear the Select2 dropdown by setting the value to an empty string
                            $('#package_id').val(null).trigger('change');
                        } else {
                            // Package does not exist in the package_rulebook table
                            // You can perform any additional actions here if needed
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle errors if necessary
                        console.error(error);
                    }
                });
            });
            
        });

    </script>
@stop