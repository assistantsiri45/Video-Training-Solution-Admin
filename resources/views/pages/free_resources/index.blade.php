@extends('adminlte::page')

@section('title', 'Free Resource')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Free Resources</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('free-resource.create') }}" type="button" class="btn btn-success">Create</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            $('#datatable tbody').sortable({
                update: function() {
                    let resource;
                    let resources = [];

                    $(this).find('tr').each(function() {
                        resource = $(this).find('.resource-id').val();
                        resources.push(resource);
                    });

                    $.ajax({
                        url: '{{ url('change-resources-order') }}',
                        data: {
                            resources: resources
                        }
                    }).done(function() {
                        $('#datatable').DataTable().draw();
                    });
                }
            });
        });
    </script>
@stop
