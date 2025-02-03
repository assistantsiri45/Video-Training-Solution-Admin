@extends('adminlte::page')

@section('title', 'Types')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Types</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('type.create') }}" type="button" class="btn btn-success">Create</a>
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
                    let course;
                    let courses = [];

                    $(this).find('tr').each(function() {
                        course = $(this).find('.course-id').val();
                        courses.push(course);
                    });
                    $.ajax({
                        url: '{{ route('courses.change-order') }}',
                        type: 'POST',
                        data: {
                            courses: courses,
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
