@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">{{ $student->name }}</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-widget bg-primary">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Email</h5>
                                <span>{{ $student->email }}</span>
                            </div>
                        </div>
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Phone</h5>
                                <span class="description-text">{{ $student->phone }}</span>
                            </div>
                        </div>
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Course</h5>
                                <span class="description-text"> @if($student->course){{ $student->course->name }}@else - @endif</span>
                            </div>
                        </div>
                        <div class="col-sm-2 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Level</h5>
                                <span class="description-text"> @if($student->level){{ $student->level->name }}@else - @endif</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">Address</h5>
                                @if ($student->address)
                                    <span>{{ $student->address }}</span><br>
                                @endif
                                <span>{{ $student->city ? $student->city . ', ' : '' }}{{ $student->state ? $student->state->name . ', ' : '' }}{{ $student->country ? $student->country->name . ' - ' : '' }}{{ $student->pin ? $student->pin : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h3 class="m-0 text-dark mb-3">Packages</h3>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" id="search" type="text" placeholder="Search" title="Package name or Subject name">
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="type">
                                    <option value=""></option>
                                    <option value="{{ \App\Models\Package::TYPE_CHAPTER_LEVEL }}">{{ \App\Models\Package::TYPE_CHAPTER_LEVEL_VALUE }}</option>
                                    <option value="{{ \App\Models\Package::TYPE_SUBJECT_LEVEL }}">{{ \App\Models\Package::TYPE_SUBJECT_LEVEL_VALUE }}</option>
                                    <option value="{{ \App\Models\Package::TYPE_CUSTOMIZED }}">{{ \App\Models\Package::TYPE_CUSTOMIZED_VALUE }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="language">
                                    <option value=""></option>
                                    @foreach (\App\Models\Language::all() as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" id="button-search">Search</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>
                        </div>
                    </div>
                    {!! $html->table(['id' => 'tbl-packages'], true) !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')

    <script>
        $(function () {
            $("#tbl-packages").on('preXhr.dt', function (e, settings, data) {
                let status = $('#tbl-packages-tab').find('.nav-link.active').first().data('status');
                data.filter = {
                    status: status,
                    search: $('#search').val(),
                    type: $('#type').val(),
                    language: $('#language').val()
                }
            });

        });
    </script>

    {!! $html->scripts() !!}

    <script>
        $(document).ready(function () {




            let table = $('#tbl-packages');
            table.DataTable().draw();


            $('#type').select2({
                placeholder: 'Type'
            });

            $('#language').select2({
                placeholder: 'Language'
            });


            $('#button-search').click(function() {
                table.DataTable().draw();
            });

            $('#button-clear').click(function() {
                $('#search').val('');
                $('#type').val('').change();
                $('#language').val('').change();
                table.DataTable().draw();
            });
        });
    </script>
@stop

