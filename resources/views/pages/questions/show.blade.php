@extends('adminlte::page')

@section('title', 'Questions')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Questions</h1>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>User Details</h3>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th style="width: 25%" scope="row">Name</th>
                                        <td style="width: 75%">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 25%" scope="row">Email</th>
                                        <td style="width: 75%">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 25%" scope="row">Phone</th>
                                        <td style="width: 75%">{{ $user->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 25%" scope="row">Course</th>
                                        <td style="width: 75%">{{ optional(optional($user->student)->course)->name }}</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 25%" scope="row">Course</th>
                                        <td style="width: 75%">{{ optional(optional($user->student)->level)->name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3>Package Details</h3>
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <th style="width: 25%" scope="row">Name</th>
                                    <td style="width: 75%">{{ $package->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 25%" scope="row">Course</th>
                                    <td style="width: 75%">{{ optional($package->course)->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 25%" scope="row">Level</th>
                                    <td style="width: 75%">{{ optional($package->course)->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 25%" scope="row">Subject</th>
                                    <td style="width: 75%">{{ optional($package->subject)->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 25%" scope="row">Chapter</th>
                                    <td style="width: 75%">{{ optional($package->chapter)->name }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="row p-5">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                Q: <strong>{{ $question->question }}</strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                A: {{ optional($question->answer)->answer }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
