@extends('adminlte::page')

@section('title', 'Usage')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Usage</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('users.usage') }}">
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="select-users">Student</label>
                                    <select class="form-control" id="select-users" name="user_id">
                                        <option value=""></option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @if (request()->input('user_id') == $user->id) selected @endif>{{ $user->name . '(' . $user->email . ')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="select-order-items">Package</label>
                                    <select class="form-control" id="select-order-items" name="order_item_id">
                                        <option value=""></option>
                                        @foreach ($orderItems as $orderItem)
                                            <option value="{{ $orderItem->id }}" @if (request()->input('order_item_id') == $orderItem->id) selected @endif>{{ optional($orderItem->package)->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mb-3">
                            <div class="col-md-1">
                                <button class="btn btn-primary" type="submit">Filter</button>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3>{{ \App\Models\Package::getFormattedDuration($remainingDuration) }}</h3>
                                        <p>Remaining Duration</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-video"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3>{{ \App\Models\Package::getFormattedDuration($totalDuration) }}</h3>
                                        <p>Total Duration</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-video"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! $table->table(['id' => 'table-usages']) !!}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $table->scripts() !!}

    <script>
        $(function () {
            $('#select-users').select2({
                placeholder: 'Student'
            });

            $('#select-order-items').select2({
                placeholder: 'Student'
            });

            $('#select-users').change(function () {
                $(this).closest('form').submit();
            });
        });
    </script>
@stop
