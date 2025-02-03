@extends('adminlte::page')

@section('title', 'Package Order')

@section('content_header')
    <div class="row">
        <div class="col">
            <div class="mb-2">
                <a class="btn btn-primary" href="{{ url('packages', $package->id) }}"><i class="fas fa-chevron-circle-left"></i> Back</a>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('packages', $package->id) }}">{{ $package->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Package Order</li>
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
                    <form method="POST" action="{{ url("packages/$package->id/chapter-packages/order") }}">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-success float-right mb-3 button-save">Save</button>
                            </div>
                        </div>
                        <div class="data-container">
                            <div class="package-container sortable-table"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop


@section('js')
    <script>
        let checkedPackages = JSON.parse('{!! json_encode($chapterPackageIDs) !!}');

        $(function () {
            $.ajax({
                url: '{{ url('api/packages/group') }}',
                data: {
                    packages: checkedPackages,
                    package_id: '{{ $package->id }}'
                }
            }).done(function(response) {
                if (! response) {
                    $('.button-save').remove();
                    $('.data-container').html('<p class="text-center mt-3">No data available<p>');
                } else {
                    $('.package-container').html(response);
                }
            });

            $(document).on('click', '.sortable-table .sortable-tbody', function() {
                $(this).sortable({
                    update: function() {
                        $(this).find('tr').each(function(i, e) {
                            $(e).find('.order').text(i + 1);
                            $(e).find('.package-order').val(i + 1);
                        });
                    }
                });
            });
        });
    </script>
@stop
