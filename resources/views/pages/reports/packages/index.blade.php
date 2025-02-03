@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Packages</h1>
        </div>
    </div>
@stop
<style>
    div.dataTables_wrapper div.dataTables_processing {
        position: absolute;
        top: 3% !important;
    }
</style>

@section('content')
{{--    <div class="row ">--}}
{{--        <div class="col-md-2">--}}
{{--            <div class="card text-white bg-info ">--}}
{{--                <div class="card-header">TOTAL PACKAGES</div>--}}
{{--                <div class="card-body">--}}
{{--                    <h1 class="text-center">{{ $totalPackageCount }}</h1>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-2">--}}
{{--            <div class="card text-white bg-info ">--}}
{{--                <div class="card-header ">ACTIVE PACKAGES</div>--}}
{{--                <div class="card-body">--}}
{{--                    <h1 class="text-center">{{ $activePackageCount }}</h1>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <input id="sales" type="text" class="form-control" placeholder="No. of Sales">
                        </div>
                        <div class="col-md-2">
                            <input id="amount" type="text" class="form-control" placeholder="Amount">
                        </div>
                        <div class="col-md-2">
                            <select id="rating" class="form-control">
                                <option></option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="professor" class="form-control">
                                <option></option>
                                @foreach (\App\Models\Professor::all() as $professor)
                                    <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <input id="package" type="text" class="form-control" placeholder="Pacakge Name">
                        </div>
                        <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control" id="course">
                                                <option value=""></option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                         </div>
                         <div class="col-md-2">
                                 <select name="level_id" id="level"  class="form-control select-level" style="width: 100% !important;">
                                 </select>
                         </div>
                         <div class="col-md-2">
                                <select class="form-control" id="package_type" name="package_type" style="width: 100%">
                                    <option value="">Choose Type</option>                                            
                               </select>
                         </div>
                        <div class="col-md-3">
                            <button id="button-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                            <button id="button-export" class="btn btn-primary ml-2">Export</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                {!! $html->table(['id' => 'datatable'], true) !!}
                </div>
            </div>
        </div>
    </div>

    <div id="modal-edit-package" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form-update-package" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expire_at">Expire At</label>
                                        <input id="expire_at" name="expire_at" type="date" class="form-control @error('expire_at') is-invalid @enderror" style="width: 100%">
                                        @error('expire_at')
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('expire_at') }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="duration">Duration</label>
                                    <select id="duration" class="form-control" name="duration" style="width: 100%">
                                        <option value=""></option>
                                        <option value="Unlimited">Unlimited</option>
                                        <option value="1.5">1.5</option>
                                        <option value="2.0">2.0</option>
                                        <option value="2.5">2.5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <form id="form-export" method="POST" action="{{ url('reports/packages/export') }}">
        @csrf
        <input id="export-sales" type="hidden" name="export_sales">
        <input id="export-amount" type="hidden" name="export_amount">
        <input id="export-rating" type="hidden" name="export_rating">
        <input id="export-professor" type="hidden" name="export_professor">
    </form>
@stop

@section('js')
    {!! $html->scripts() !!}

    <script>
        $(function() {
            $('#rating').select2({
                placeholder: 'Rating'
            });

            $('#professor').select2({
                placeholder: 'Professor'
            });

            let table = $('#datatable').DataTable();

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    sales: $('#sales').val(),
                    amount: $('#amount').val(),
                    rating: $('#rating').val(),
                    professor: $('#professor').val(),
                    package:$('#package').val(),
                    course:$('#course').val(),
                    level:$('#level').val(),
                    package_type:$('#package_type').val(),
                }
            });

            $('#button-filter').click(function() {
                table.draw();
            });

            $('#duration').select2({
                placeholder: 'Duration'
            });
            $('#course').select2({
                placeholder: 'Course'
            });
            $('#level').select2({
                placeholder: 'Level'
            });
            $('#package_type').select2({
                placeholder: 'Package Type'
            });

            table.on('click', '.modal-package-edit', function() {
                $('#duration').val($(this).data('duration')).change();
                $('#expire_at').val($(this).data('expire-at')).change();
                $('#form-update-package').attr('action', '{{ url('package-reports') }}' + '/' + $(this).data('id'));
            });

            $('#btn-clear').click(function() {
                $('#sales').val('');
                $('#amount').val('');
                $('#rating').val('').change();
                $('#professor').val('').change();
                $('#package').val('');
                $('#course').val('').change();
                $('#level').val('').change();
                $('#package_type').val('').change();
                table.draw();
            });

            $('#button-export').click(function() {
                $('#export-sales').val($('#sales').val());
                $('#export-amount').val($('#amount').val());
                $('#export-rating').val($('#rating').val());
                $('#export-professor').val($('#professor').val());
                $('#form-export').submit();
            });
        }); 
        $('#course').on('change', function () {
            $('#level').empty();
            $('#package_type').empty();
            var CourseID = $(this).val();

            if (CourseID) {
                $.ajax({
                    url: '{{ url('/course-levels/ajax') }}' + '/' + CourseID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    //    $('#level').empty();
                        $('#level').append('<option disabled selected>  Choose Level </option>');
                        $.each(data, function (key, value) {
                            $('#level').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
            //    $('#level').empty();
            }
        });
        $('#level').on('change', function () {
            $('#package_type').empty();
                var LevelID = $(this).val();
                if (LevelID) {
                $.ajax({
                    url: '{{ url('/gettypes/ajax') }}' + '/' + LevelID,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                    //    $('#package_type').empty();
                        $('#package_type').append('<option disabled selected>  Choose Type </option>');
                        $.each(data, function (key, value) {
                            if(jQuery.isEmptyObject(value.packagetype)!=true){
                            $('#package_type').append('<option value="' + value.packagetype.id + '">' + value.packagetype.name + '</option>');
                            }
                        });
                    }
                });
              

                } else {
                //    $('#package_type').empty();
                }
            });
    </script>
@stop
