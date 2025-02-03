@extends('adminlte::page')

@section('title', 'Questions')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">@if(request()->input('answered')==1)Answered Questions @elseif(request()->input('answered')==2) Pending Questions @endif</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <form id="form-filter">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="form-control" id="search" type="text" placeholder="Search" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" id="from-date" placeholder="From Date" value="{{ \Carbon\Carbon::now()->subWeek()->format('d-m-Y') }}" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" id="to-date" placeholder="To Date" value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" value="{{request()->input('professor_user_id')}}" id="sel_prof" />
                                <select class="form-control" id="professor">
                                    <option value=""></option>
                                    @foreach (\App\Models\Professor::query()->orderby('name','asc')->get() as $professor)
                                        <option value="{{ $professor->user_id }}" @if (request()->input('professor_user_id') == $professor->user_id) selected @endif>{{ $professor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" id="package">
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" id="q_type" name="q_type" value="{{request()->input('answered')}}"/>
                                <button class="btn btn-primary" type="submit">Filter</button>
                                <button class="btn btn-primary ml-2" id="button-clear">Clear</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    {!! $table->table(['id' => 'table-questions'], true) !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $table->scripts() !!}

    <script>
        prof_id=$('#sel_prof').val();
        getPackage(prof_id);
        $(function () {
            let tableQuestions = $('#table-questions').DataTable();

            tableQuestions.on('preXhr.dt', function ( e, settings, data ) {
                data.filter = {
                    search: $('#search').val(),
                    from_date: $('#from-date').val(),
                    to_date: $('#to-date').val(),
                    professor: $('#professor').val(),
                    package: $('#package').val(),
                    q_type: $('#q_type').val()
                }
            });

            tableQuestions.draw();

            $('#form-filter').submit(function (e) {
                e.preventDefault();
                tableQuestions.draw();
            });

            $('#from-date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('#to-date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });

            $('#professor').select2({
                placeholder: 'Professor'
            });

            $('#package').select2({
                placeholder: 'Package'
            });


            $('#button-clear').click(function() {
                prof_id=$('#sel_prof').val();
                $('#search').val('');
                $('#professor').val(prof_id).change();
                $('#from-date').val('');
                $('#to-date').val('');
                $('#package').val('').change();           
                tableQuestions.draw();
            });
        });

        $('#professor').on('change', function () {
                var professorID = $(this).val();
                getPackage(professorID);
            });

            function getPackage(professorID){
                if (professorID) {
                $.ajax({
                    url: '{{ url('/getprofessorPackages/ajax') }}' + '/' + professorID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        $('#package').empty();
                        $('#package').append('<option disabled selected>  Choose Package </option>');
                        $.each(data, function (key, value) {
                            $('#package').append('<option value="' + value.id + '">' + value.name + '</option>');
                    
                        });

                    }
                });
                } else {
                    $('#package').empty();
                }
            }
    </script>
@stop
