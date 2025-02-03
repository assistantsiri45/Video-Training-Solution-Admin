@extends('adminlte::page')

@section('title', 'Video Order')

@section('content_header')
    <div class="row">
        <div class="col">
            <div class="mb-2">
                <a class="btn btn-primary" href="{{ url('packages', $package->id) }}"><i class="fas fa-chevron-circle-left"></i> Back</a>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('packages', $package->id) }}">{{ $package->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Video Order</li>
                </ol>
            </nav>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ url("packages/$package->id/videos/order") }}">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-success float-right mb-3 button-save">Save</button>
                            </div>
                        </div>
                        <div class="data-container">
                            <div class="module-container sortable-tables"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop


@section('js')
    <script>
        let checkedVideos = JSON.parse('{!! json_encode($packageVideoIDs) !!}');

        $(function () {
            $.ajax({
                url: '{{ url('api/videos/group') }}',
                data: {
                    videos: checkedVideos,
                    package_id: '{{ $package->id ?? null }}'
                }
            }).done(function(response) {
                if (! response) {
                    $('.button-save').remove();
                    $('.data-container').html('<p class="text-center mt-3">No data available<p>');
                } else {
                    $('.module-container').html(response);
                }
            });

            $('.sortable-tables').sortable({
                update: function() {
                    $('.table-module').each(function(i, e) {
                        $(e).find('th .order').text(i + 1);
                        $(e).find('.sortable-tbody .module-order').val(i + 1);
                    });
                }
            });

            $(document).on('click', '.sortable-tables .sortable-tbody', function() {
                $(this).sortable({
                    update: function() {
                        $(this).find('tr').each(function(i, e) {
                            $(e).find('.order').text(i + 1);
                            $(e).find('.video-order').val(i + 1);
                        });
                    }
                });
            });
        });
    </script>
@stop



