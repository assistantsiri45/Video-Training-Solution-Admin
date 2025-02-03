@extends('adminlte::page')

@section('title', 'Add Extension')

@section('content_header')
    <div class="pb-3">
        <a href="{{ route('package-extensions.index') }}" class="btn btn-primary">Back</a>
    </div>
    <h1 class="m-0 text-dark">Package Extension</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-widget bg-primary">
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-3 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Order ID</h5>
                                <span>{{ $order_item->order_id }}</span>
                            </div>
                        </div>
                        <div class="col-sm-3 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Receipt No</h5>
                                <span class="description-text">{{ $order_item->payment->receipt_no ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-3 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Package Name</h5>
                                <span class="description-text"> {{ $order_item->package->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-3 ">
                            <div class="description-block">
                                <h5 class="description-header">Student Name</h5>
                                <span class="description-text"> {{ $order_item->user->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('package-extensions.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="extended_date" name="order_item_id"  value="{{ $order_item->id }}" >
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="extended_date">Extension Date</label>
                                    <input type="date" id="extended_date" name="extended_date" class="form-control @error('extended_date') is-invalid @enderror" value="{{ old('extended_date') }}" >
                                    @error('extended_date')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('extended_date') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="extended_hours">Extension Hours</label>
                                    <input type="text" id="extended_hours" name="extended_hours" class="form-control @error('email') is-invalid @enderror" value="{{ old('extended_hours') }}" placeholder="Enter hours">
                                    <small>Enter extension in hours.</small>
                                    @error('extended_hours')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('extended_hours') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
{{--                <div class="p-3">--}}
{{--                    <h2 class="m-0 text-dark" style="font-size: 25px">Extension History</h2>--}}
{{--                </div>--}}
                <div class="row p-3">
                    <div class="col">
                        <h2 class="m-0 text-dark" style="font-size: 25px">Extension History</h2>
                    </div>
                    <div class="col text-right">
                        <button  id="delete-" type="button" class="btn btn-danger">Delete</button>
                    </div>
                </div>
                {!! $html->table(['id' => 'datatable'], true) !!}
            </div>
        </div>
    </div>
@stop

@section('js')

    {!! $html->scripts() !!}

    <script>
        $(document).ready(function () {
            $('.buttons-csv').remove();
            $('.buttons-pdf').remove();

            $('#create').validate({
                rules: {
                    extended_hours: {
                        number: true,
                    },
                    extended_date: {
                    }
                }
            });

            (function($){
                $("#delete-").click(function () {
                    let confirmation = confirm("Delete the oldest extension?");
                    let table = $('#datatable');

                    if (confirmation) {
                        $.ajax({
                            url: "{{ route('package-extensions.destroy', $order_item->id) }}",
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(result) {
                                if (result == true) {
                                    table.DataTable().draw();
                                }
                                if (result ==false) {
                                    toastr.error('No data to delete');
                                }

                            }
                        });
                    }
                });
            })(jQuery);
        });

    </script>

@stop
