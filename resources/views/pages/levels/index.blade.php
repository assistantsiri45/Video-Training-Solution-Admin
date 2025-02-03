@extends('adminlte::page')

@section('title', 'Levels')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Levels</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('levels.create') }}" type="button" class="btn btn-success">Create</a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" id="search" type="text" placeholder="Search" title="Course name or level name">
                            </div>
                            <div class="col-md-2">
                                <select id="course" class="form-control" style="width: 100%">
                                    <option></option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <button class="btn btn-primary" id="button-search">Search</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>
                        </div>
                    </div>
                {!! $html->table(['id' => 'datatable'], true) !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function () {

            let table = $('#datatable');

            $('#course').select2({
                placeholder: 'Course'
            });

            $("#datatable").on('preXhr.dt', function (e, settings, data) {
                data.filter = {
                    search: $('#search').val(),
                    course: $('#course').val(),

                }
            });

            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#course').val('').change();
                table.DataTable().draw();
            });


            $('#datatable tbody').sortable({
                update: function() {
                    let level;
                    let levels = [];

                    $(this).find('tr').each(function() {
                        level = $(this).find('.level-id').val();
                        levels.push(level);
                    });
                    $.ajax({
                        url: '{{ route('levels.change-order') }}',
                        type: 'POST',
                        data: {
                            levels: levels,
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
