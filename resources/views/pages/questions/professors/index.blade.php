@extends('adminlte::page')

@section('title', 'Ask A Question')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Ask A Question</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- <div class="card-header">
                    <form id="form-filter">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" id="search" type="text" placeholder="Search" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" type="submit">Filter</button>
                                <a class="btn btn-primary ml-2" href="{{ route('questions.professors.index') }}">Clear</a>
                            </div>
                        </div>
                    </form>
                </div> -->
                <div class="card-body">
              
                    <ul class="nav nav-tabs">
                        <li><a class="nav-link active" data-status="answered"   data-toggle="tab" href="#tab-answered">Answered Questions</a></li>
                        <li><a class="nav-link" data-status="pending"   data-toggle="tab" href="#tab-pending">Pending Questions</a></li>
                       
                    </ul>
                    <div class="tab-content">
                        <div id="tab-answered" class="tab-pane active">
                            <div class="card-header">
                                <form id="form-filter">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input class="form-control" id="search" type="text" placeholder="Search" autocomplete="off">
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn btn-primary" type="submit">Filter</button>
                                            <a class="btn btn-primary ml-2" href="{{ route('questions.professors.index') }}">Clear</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                    {!! $answeredQuestions->table(['id' => 'tbl-answered-questions'], true) !!}
                            </div>

                        </div>

                        <div id="tab-pending" class="tab-pane ">
                            <div class="card-header">
                                <div class="card-header">
                                    <form id="form-filter2">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input class="form-control" id="search2" type="text" placeholder="Search" autocomplete="off">
                                            </div>

                                            <div class="col-md-2">
                                                <button class="btn btn-primary" type="submit">Filter</button>
                                                <a class="btn btn-primary ml-2" href="{{ route('questions.professors.index') }}">Clear</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                            </div>

                            <div class="table-responsive">
                                    {!! $pendingQuestions->table(['id' => 'tbl-pending-questions'], true) !!}
                            </div>

                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $answeredQuestions->scripts() !!}

    {!! $pendingQuestions->scripts() !!}

    <script>
        $(function () {
            let tableAnswered = $('#tbl-answered-questions').DataTable();

            tableAnswered.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    search: $('#search').val()
                }
            });

            $('#form-filter').submit(function(e) {
                e.preventDefault();
                tableAnswered.draw();
            });



            let tablePending = $('#tbl-pending-questions').DataTable();

            tablePending.on('preXhr.dt', function(e, settings, data) {
                data.filter = {
                    search: $('#search2').val()
                }
            });

            $('#form-filter2').submit(function(e) {
                e.preventDefault();
                tablePending.draw();
            });
        });
    </script>
@stop
