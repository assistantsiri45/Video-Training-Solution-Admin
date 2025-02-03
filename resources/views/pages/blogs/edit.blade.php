@extends('adminlte::page')

@section('title', 'Edit Blog')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Blog</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="edit" method="POST" action="{{ route('blogs.update', $blog->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title" value="{{ old('title', $blog->title) }}" autocomplete="off">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="title">Slug</label>
                                    <input class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" placeholder="Slug" value="{{ old('slug', $blog->slug) }}" autocomplete="off">
                                    @error('slug')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('slug') }}</span>
                                    @enderror
                                </div>
                            </div>
{{--                            <div class="col-sm-12">--}}
{{--                                <label for="category">Category</label>--}}
{{--                                <div class="form-group">--}}
{{--                                    <select class="w-100" id="category" name="category" required>--}}
{{--                                        <option></option>--}}
{{--                                        @foreach ($categories as $category)--}}
{{--                                            <option value="{{ $category->id }}" @if ($category->id == $blog->category_id) selected @endif>{{ $category->name }}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                    @error('category')--}}
{{--                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('category') }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="author">Author</label>
                                    <input class="form-control @error('author') is-invalid @enderror" id="author" name="author" placeholder="Author" value="{{ old('author', $blog->author) }}" autocomplete="off">
                                    @error('author')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('author') }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <input hidden type="text" id="image-src" value="{{url('storage/blogs/images/'.$blog->image) }}">
                                <div id="hidden-inputs-container"></div>
                                <div class="form-group row">
                                    <label class="col-form-label">Image<span class="required">*</span> (Size: 720 X 480 PX) </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="upload" name="file" @error('file') is-invalid @enderror data-toggle="modal" data-target="#exampleModalCenter">
                                            <label class="custom-file-label" for="inputGroupFile02">Choose file</label>
                                        </div>
                                        <div class="col-12 mt-3 p-0">
                                            <img width="130" src="{{$file_path}}"  class="img-thumbnail">
                                        </div>
                                        @error('file')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('file') }}</span>
                                        @enderror
                                    </div>
                                </div>
{{--                                <div class="form-group">--}}
{{--                                    <label for="image">Image</label>--}}
{{--                                    <div class="custom-file">--}}
{{--                                        <input class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" type="file" accept="image/*">--}}
{{--                                        <label class="custom-file-label" id="image-label" for="image">Choose Image</label>--}}
{{--                                    </div>--}}
{{--                                    @error('image')--}}
{{--                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('image') }}</span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Body</label>
                                    <div class="border w-100" id="editorjs"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button class="btn btn-sm btn-primary float-right" id="button-preview" type="button"><i class="fas fa-eye"></i></button>
                            </div>
                            <div class="col-sm-12">
                                <label for="related-blogs">Related Blogs</label>
                                <div class="form-group">
                                    <select class="w-100" id="related-blogs" name="related_blogs[]" multiple="multiple">
                                        @foreach ($relatedBlogs as $relatedBlog)
                                            <option value="{{ $relatedBlog->id }}">{{ $relatedBlog->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <label for="tags">Topics</label>
                                <div class="form-group">
                                    <select class="w-100" id="tags" name="tags[]" multiple="multiple" required>
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('tag')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('tag') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <input id="body" name="body" type="hidden">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="crop-tool">
                                    <div id="upload-demo"></div>
                                    <div class="col-md-1" id="upload-demo-i" name="image_viewport"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 offset-4 crop-tool" hidden>
                            <div class="edit-pic" >
                                <img width="450"  id="photo" class="img-thumbnail" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" id="crop-btn">Click here to Crop Image</button>
                    <button type="button" class="btn btn-secondary" id="image-save">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-preview" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>PREVIEW</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="preview-content"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        !function(t,e){"object"==typeof exports&&"undefined"!=typeof module?module.exports=e():"function"==typeof define&&define.amd?define(e):(t=t||self).edjsHTML=e()}(this,(function(){"use strict";var t={delimiter:function(){return"<br/>"},header:function(t){var e=t.data;return"<h"+e.level+"> "+e.text+" </h"+e.level+">"},paragraph:function(t){return"<p> "+t.data.text+" </p>"},list:function(t){var e=t.data,n="unordered"===e.style?"ul":"ol",r="";return e.items&&(r=e.items.map((function(t){return"<li> "+t+" </li>"})).reduce((function(t,e){return t+e}),"")),"<"+n+"> "+r+" </"+n+">"},image:function(t){var e=t.data,n=e.caption?e.caption:"Image";return'<img src="'+(e.file?e.file.url:"")+'" alt="'+n+'" />'},quote:function(t){var e=t.data;return"<blockquote> "+e.text+" </blockquote> - "+e.caption}};function e(t){return new Error('[31m The Parser function of type "'+t+'" is not defined. \n\n  Define your custom parser functions as: [34mhttps://github.com/pavittarx/editorjs-html#extend-for-custom-blocks [0m')}return function(n){return void 0===n&&(n={}),Object.assign(t,n),{parse:function(n){return n.blocks.map((function(n){return t[n.type]?t[n.type](n):e(n.type)}))},parseBlock:function(n){return t[n.type]?t[n.type](n):e(n.type)}}}}));
    </script>
    <script>
        $(function () {
            let body = @json($blog->body);
            let data = JSON.parse(body);

            const editor = new EditorJS({
                placeholder: 'Body',
                tools: {
                    header: {
                        class: Header,
                        placeholder: 'Header'
                    },
                    image: {
                        class: ImageTool,
                        config: {
                            endpoints: {
                                byFile: '{{ route('blogs.images.store') }}'
                            }
                        }
                    }
                },
                data: data
            });

            $('#edit').validate({
                ignore: '#editorjs *',
                rules: {
                    title: {
                        required: true
                    },
                    slug: {
                        required: true,
                        pattern: /^[A-Za-z\d-.]+$/
                    },
                    author: {
                        required: true
                    }
                }
            });

            $('#edit').on('submit', function (e) {
                e.preventDefault();

                if ($('#edit').valid()) {
                    editor.save().then((data) => {
                        $('#body').val(JSON.stringify(data));
                        $('#edit')[0].submit();
                    }).catch((error) => {
                        console.log(error);
                    });
                }
            });

            $('#image').on('change', function() {
                let imageName = $(this).val().split('\\').pop();
                $(this).siblings('#image-label').addClass('selected').html(imageName);
            });

            // $('#category').select2({
            //     placeholder: 'Category'
            // });

            $('#related-blogs').select2({
                placeholder: 'Related Blogs'
            });

            let relatedBlogIDs = JSON.parse('{!! $relatedBlogIDs !!}');

            $('#related-blogs').val(relatedBlogIDs);
            $('#related-blogs').trigger('change');

            $('#tags').select2({
                placeholder: 'Topics'
            });

            let blogTagIDs = JSON.parse('{!! $blogTagIDs !!}');

            $('#tags').val(blogTagIDs);
            $('#tags').trigger('change');


            var uploadCrop = $('#upload-demo').croppie({
                enableExif: true,
                viewport: {
                    width: 720,
                    height: 480,
                    type: 'rectangle'
                },
                boundary: {
                    width: 730,
                    height: 490
                }
            });
            uploadCrop.croppie('bind', {

                url: $("#image-src").val()
            });

            $('#crop-btn').on('click', function () {
                uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp) {
                    $(".crop-tool").attr("hidden", false);
                    $('#photo').attr('src', resp);
                    $('#edit').on('submit', function () {
                        console.log(resp);
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
                    }).then(function () {
                        $("#crop-btn").trigger("click");
                    });
                };
                reader.readAsDataURL(this.files[0]);
            });
            $('#image-save').on('click', function () {
                $('#exampleModalCenter').modal('toggle');
            });

            $('#button-preview').click(function () {
                editor.save().then((data) => {
                    const editorJSParser = edjsHTML();
                    $('#preview-content').html(editorJSParser.parse(data));
                    $('#preview-content').find('img').addClass('img-fluid');
                }).catch((error) => {
                    console.log(error);
                });

                $('#modal-preview').modal('toggle');
            });
        });
    </script>
@stop

@push('css')
    <style>
        .ce-block__content,
        .ce-toolbar__content {
            max-width: unset;
            padding-left: 12px;
            padding-right: 12px;
        }
    </style>
@endpush
