@extends('adminlte::page')

@section('title', 'Banners')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Banners</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('banners.create') }}" type="button" class="btn btn-success">Create</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}
    <script type="text/javascript">
        $(function() {
            $('.buttons-csv').hide();
            $('.buttons-pdf').hide();

            $('#datatable tbody').sortable({
                update: function() {
                    let banner;
                    let banners = [];

                    $(this).find('tr').each(function() {
                        banner = $(this).find('.banner-id').val();
                        banners.push(banner);
                    });

                    $.ajax({
                        url: '{{ url('change-banners-order') }}',
                        data: {
                            banners: banners
                        }
                    }).done(function() {
                        $('#datatable').DataTable().draw();
                    });
                }
            });
        });
    </script>
@stop
