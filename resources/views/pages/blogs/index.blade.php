@extends('adminlte::page')

@section('title', 'Blogs')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Blogs</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('blogs.create') }}" type="button" class="btn btn-success">Create</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                {!! $tableBlogs->table(['id' => 'tableBlogs'], true) !!}
            </div>
        </div>
    </div>

    <div id="modal-preview" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">BLOG PREVIEW</h3>
                </div>
                <div class="modal-body" style="font-family: 'Libre Franklin', sans-serif;">
                    <div class="row">
                        <div class="col-sm-10 offset-1">
                            <div class="row my-3">
                                <div class="col-sm-12">
                                    <h1 class="text-secondary my-3 blog-preview-title" style="color: #3b3485 !important"></h1>
                                </div>
                            </div>
                            <div class="row my-3">
                                <div class="col-md-12">
                                    <span class="text-muted small"><i class="fas fa-calendar"></i> <span class="blog-preview-published-at"></span> | <i class="fas fa-user"></i> <span class="blog-preview-author"></span> | <i class="fas fa-list"></i> <span class="blog-preview-category-name"></span></span>
                                </div>
                            </div>
                            <hr class="my-5" />
                            <div class="row my-3">
                                <div class="col-sm-12">
                                    <div class="blog-preview-image"></div>
                                </div>
                            </div>
                            <div class="preview-container" style="font-size: 1rem;font-weight: 400;line-height: 1.5;"></div>
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
    {!! $tableBlogs->scripts() !!}

    <script>
        $(function  () {
            $('.buttons-html5').remove();

            $('#tableBlogs').on('click', '.button-delete', function (e) {
                e.preventDefault();
                let confirm = window.confirm('Delete Blog?');
                let url = $(this).attr('href');

                if (confirm) {
                    $.ajax({
                        url: url,
                        type: 'DELETE'
                    }).done(function (response) {
                        toastr.success(response.success);
                        $('#tableBlogs').DataTable().draw();
                    });
                }
            });

            $('#tableBlogs').on('click', '.button-publish', function (e) {
                e.preventDefault();
                let confirm = window.confirm('Publish Blog?');
                let url = $(this).attr('href');

                if (confirm) {
                    $.ajax({
                        url: url,
                        type: 'POST'
                    }).done(function (response) {
                        toastr.success(response.success);
                        $('#tableBlogs').DataTable().draw();
                    });
                }
            });

            $('#tableBlogs').on('click', '.button-preview', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');

                $.ajax({
                    url: url
                }).done(function (response) {
                    console.log(response);

                    $('.blog-preview-title').text(response.title);
                    $('.blog-preview-image').html('<img class="img-fluid" src="' + response.image_url + '" alt="">');
                    $('.blog-preview-published-at').text(response.published_at ? new Date(response.published_at).toLocaleDateString('en-GB', { year: 'numeric', month: 'short', day: 'numeric' }) : new Date(response.created_at).toLocaleDateString('en-GB', { year: 'numeric', month: 'short', day: 'numeric' }));
                    $('.blog-preview-author').text(response.author);
                    $('.blog-preview-category-name').text(response.category.name);
                    $('.preview-container').html(response.body);
                    $('#modal-preview').modal('toggle');
                });
            });

            $('#tableBlogs tbody').sortable({
                update: function() {
                    let blog;
                    let blogs = [];

                    $(this).find('tr').each(function() {
                        blog = $(this).find('.blog-id').val();
                        blogs.push(blog);
                    });

                    $.ajax({
                        url: '{{ url('blogs/change-order') }}',
                        type: 'POST',
                        data: {
                            blogs: blogs
                        }
                    }).done(function() {
                        $('#tableBlogs').DataTable().draw();
                    });
                }
            });
        });
    </script>
@stop
