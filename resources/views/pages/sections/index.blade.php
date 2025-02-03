@extends('adminlte::page')

@section('title', 'Sections')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Sections</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('sections.create') }}" type="button" class="btn btn-success">Create</a>
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

    <script>
        $(function () {
            $('#datatable tbody').sortable({
                update: function() {
                    let section;
                    let sections = [];

                    $(this).find('tr').each(function() {
                        section = $(this).find('.section-id').val();
                        sections.push(section);
                    });
                    $.ajax({
                        url: '{{ route('sections.change-order') }}',
                        type: 'POST',
                        data: {
                            sections: sections,
                            "_token": "{{ csrf_token() }}",
                        }
                    }).done(function() {
                        $('#datatable').DataTable().draw();
                    });
                }
            });
        })
    </script>
@stop
