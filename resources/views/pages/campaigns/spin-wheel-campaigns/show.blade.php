@extends('adminlte::page')

@section('title', 'Spin Wheel Campaigns')

@section('content_header')
    <div class="row">
        <div class="col-12">
            <h1 class="m-2 text-dark">{{$campaign->title}}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{$totalCount}}</h3>
                                    <p>Total Registration</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <a href="{{ url('students') }}" class="small-box-footer">
                                    More Info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{$totalRedeem}}</h3>
                                    <p>Total Redeem</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <a href="{{ url('orders') }}" class="small-box-footer">
                                    More Info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{$totalSpin}}</h3>
                                    <p>Total Spins</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-rupee-sign"></i>
                                </div>
                                <a href="{{ url('orders') }}" class="small-box-footer">
                                    More Info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <input id="search" type="text" class="form-control" placeholder="Search">
                        </div>
                        <div class="col-md-3">
                            <button id="button-filter" class="btn btn-primary">Filter</button>
                            <button id="btn-clear" class="btn btn-primary ml-2">Clear</button>
                            {{--                            <button id="button-export" class="btn btn-primary ml-2">Export</button>--}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!! $html->table(['id' => 'datatable'], true) !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! $html->scripts() !!}
    <script>
        $(function() {
            let table = $('#datatable').DataTable();

            table.on('preXhr.dt', function( e, settings, data) {
                data.filter = {
                    search: $('#search').val()
                }
            });

            $('#button-filter').click(function() {
                table.draw();
            });
            $('#btn-clear').click(function() {
                search: $('#search').val('');
                table.draw();
            });
        });
    </script>
@stop
