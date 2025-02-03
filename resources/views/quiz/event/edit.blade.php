@extends('adminlte::page')
@section('title', 'Event Master')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Event Master</h1>
        </div>
     </div>
@endsection
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.min.css" integrity="sha512-tjNtfoH+ezX5NhKsxuzHc01N4tSBoz15yiML61yoQN/kxWU0ChLIno79qIjqhiuTrQI0h+XPpylj0eZ9pKPQ9g==" crossorigin="anonymous" />
@endsection
@section('content')
@include('quiz.error')
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Edit Event</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.event.update', ['event' => $data->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" value="{{ $data->id }}" class="event_id" id="event_id">
                        @if($data->event_type == "Practice")
                            @php($event_hide = 'style=display:none')
                        @else
                            @php($event_hide = "")
                        @endif
                        @if($data->event_type == "Olympiad")
                            @php($olympiad = '')
                        @else
                            @php($olympiad = 'style=display:none')
                        @endif
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="select2 select2-hidden-accessible board" data-placeholder="Select Course" style="width: 100%;" name="board" id="board" onchange="getGrade()">
                                        <option></option>
                                        @foreach ($boards as $key => $board)
                                            <option value="{{ $board->id }}" @if($data->board_id == $board->id) selected="selected" @endif>{{ $board->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Level</label>
                                    <select class="select2 select2-hidden-accessible grade" data-placeholder="Select Level" style="width: 100%;" name="grade" id="grade" onchange="getSubject()">
                                        <option></option>
                                        @foreach ($grades as $key => $grade)
                                            <option value="{{ $grade->id }}" @if($data->grade_id == $grade->id) selected="selected" @endif>{{ $grade->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Instruction</label>
                                    <select class="select2 select2-hidden-accessible instruction" data-placeholder="Select Instruction" style="width: 100%;" name="instruction" id="instruction" onchange="getSubject()">
                                        <option></option>
                                        @foreach ($instructions as $key => $instruction)
                                            <option value="{{ $instruction->id }}" @if($data->event_details == $instruction->id) selected="selected" @endif>{{ $instruction->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Event Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Type" style="width: 100%;" name="type" id="type" onchange="isPractice(this)">
                                        <option></option>
                                        <!-- <option value="Practice" @if($data->event_type == "Practice") selected="selected" @endif>Practice</option> -->
                                        <option value="Competition" @if($data->event_type == "Competition") selected="selected" @endif>Competition</option>
                                        <!-- <option value="Olympiad" @if($data->event_type == "Olympiad") selected="selected" @endif>Olympiad</option> -->
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mode</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Mode" style="width: 100%;" name="mode" id="mode">
                                        <option></option>
                                        <option value="online" @if($data->mode == "online") selected="selected" @endif>Online</option>
                                        <option value="offline" @if($data->mode == "offline") selected="selected" @endif>Offline</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Device</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Device" style="width: 100%;" name="device" id="device">
                                        <option></option>
                                        <option value="computer" @if($data->device == "computer") selected="selected" @endif>Computer</option>
                                        <option value="tablet" @if($data->device == "tablet") selected="selected" @endif>Tablet</option>
                                        <option value="moblie" @if($data->device == "moblie") selected="selected" @endif>Moblie</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Is Free</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="is_free" id="is_free" onchange="isFree(this)">
                                        <option value="1" @if($data->is_free == 1) selected="selected" @endif>Yes</option>
                                        <option value="0"@if($data->is_free == 0) selected="selected" @endif>No</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            @if($data->is_free == 1)
                                @php($is_free = 'style=display:none')
                            @else
                                @php($is_free = "")
                            @endif
                            <div class="col-md-3 price-div" {{ $is_free }}>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" min="0" class="form-control" name="price" id="price" value="{{ $data->price }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Access Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Type" style="width: 100%;" name="access_type" id="access_type">
                                        <option></option>
                                        <option value="public" @if($data->access_type == 'public') selected="selected" @endif>Public</option>
                                        <option value="private" @if($data->access_type == 'private') selected="selected" @endif>Private</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3 rounds-div">
                                <div class="form-group">
                                    <label>Rounds</label>
                                    <input type="number" min="0" class="form-control" name="rounds" id="rounds" value="{{ $data->rounds }}" @if($data->event_type == "Competition") readonly="readonly" @endif>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3 event-hide" {{ $event_hide }}>
                                <div class="form-group">
                                    <label>Event Start Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="start_date" id="start_date" value="@if(!empty($data->start_date)){{ date('d/m/Y', strtotime($data->start_date)) }}@endif">
                                </div>
                            </div>
                            <div class="col-md-3 event-hide" {{ $event_hide }}>
                                <div class="form-group">
                                    <label>Event End Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="end_date" id="end_date" value="@if(!empty($data->end_date)){{ date('d/m/Y', strtotime($data->end_date)) }}@endif">
                                </div>
                            </div>
                            {{-- <div class="col-md-3 event-hide" {{ $event_hide }}>
                                <div class="form-group">
                                    <label>Enroll Start Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="enroll_start_date" id="enroll_start_date" value="@if(!empty($data->enroll_start_date)){{ date('d/m/Y', strtotime($data->enroll_start_date)) }}@endif">
                                </div>
                            </div>
                            <div class="col-md-3 event-hide" {{ $event_hide }}>
                                <div class="form-group">
                                    <label>Enroll End Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="enroll_end_date" id="enroll_end_date" value="@if(!empty($data->enroll_end_date)){{ date('d/m/Y', strtotime($data->enroll_end_date)) }}@endif">
                                </div>
                            </div> --}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Select Logo/File</label>
                                    <a href="{{ asset($data->logo) }}" target="_blank"><img src="{{ asset($data->logo) }}" style="width: 50px" /></a>
                                    <input type="file" class="form-control" name="attachment" id="attachment">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- <div class="col-md-3 event-hide" {{ $event_hide }}>
                                <div class="form-group">
                                    <label>Is Sample</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="is_sample" id="is_sample" onchange="isSample(this)">
                                        <option value="1" @if($data->is_sample == 1) selected="selected" @endif>Yes</option>
                                        <option value="0" @if($data->is_sample == 0) selected="selected" @endif>No</option>
                                    </select>
                                </div>
                            </div>
                            @if($data->event_type != "Practice" && $data->is_sample != 0)
                                @php($is_sample = '')
                            @else
                                @php($is_sample = 'style=display:none')
                            @endif
                            <div class="col-md-3 sample_test-div event-hide" {{ $is_sample }}>
                                <div class="form-group">
                                    <label>Sample Test</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Sample Test" style="width: 100%;" name="sample_test">
                                        <option></option>
                                        @foreach ($tests as $key => $test)
                                            <option value="{{ $test->id }}" @if($data->sample_test == $test->id) selected="selected" @endif>{{ $test->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            @if($data->event_type == "Practice")
                                @php($practice_test = '')
                                @php($quiz_test = 'style=display:none')
                            @elseif($data->event_type == "Competition")
                                @php($practice_test = 'style=display:none')
                                @php($quiz_test = '')
                            @else
                                @php($practice_test = 'style=display:none')
                                @php($quiz_test = 'style=display:none')
                            @endif
                            @if($data->event_type != "Olympiad")
                            <div class="col-md-3  practice_test-div" {{ $practice_test }}>
                                <div class="form-group">
                                    <label>Test</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Test" style="width: 100%;" name="practice_test" id="practice_test">
                                        <option></option>
                                        @foreach ($practices as $key => $practice)
                                            <option value="{{ $practice->id }}" @if($practice->id == $data->getEventRounds()->first()->test_id) selected="selected" @endif>{{ $practice->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3  quiz_test-div" {{ $quiz_test }}>
                                <div class="form-group">
                                    <label>Test</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Test" style="width: 100%;" name="quiz_test" id="quiz_test">
                                        <option></option>
                                        @foreach ($quizs as $key => $quiz)
                                            <option value="{{ $quiz->id }}"  @if($quiz->id == $data->getEventRounds()->first()->test_id) selected="selected" @endif>{{ $quiz->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3  quiz_test-div" {{ $quiz_test }}>
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input type="time" class="form-control" name="start_time" id="start_time" value="{{ date('H:i', strtotime($data->getEventRounds()->first()->start_datetime)) }}">
                                </div>
                            </div>
                            <div class="col-md-3  quiz_test-div" {{ $quiz_test }}>
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time" class="form-control" name="end_time" id="end_time" value="{{ date('H:i', strtotime($data->getEventRounds()->first()->end_datetime)) }}">
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Is Published</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Type" style="width: 100%;" name="is_published" id="is_published">
                                        <option value="1" @if($data->is_published == 1) selected="selected" @endif>Published</option>
                                        <option value="0" @if($data->is_published == 0) selected="selected" @endif>Unpublished</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Order By</label>
                                    <input type="number" min="1" class="form-control" name="order_by" id="order_by"  value="{{ $data->order_by }}">
                                </div>
                            </div> -->
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status" id="status">
                                        <option value="1" @if($data->status == 1) selected="selected" @endif>Enable</option>
                                        <option value="0" @if($data->status == 0) selected="selected" @endif>Disable</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-6">
                                @if(count($data->getTests) < $data->rounds)
                                    @php($disabled = '')
                                @else
                                    @php($disabled = 'disabled')
                                @endif
                                <button id="add-module" type="button" class="btn btn-info float-left" {{ $olympiad.' '.$disabled }}>Add Rounds</button>
                            </div>
                            <div class="col-6">
                                <input type="submit" value="Update" class="btn btn-success float-right">
                                <a href="{{ route('quiz.event.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
    <div class="card-body is_olympiad" {{ $olympiad }}>
        <input type="hidden" id="round_count" class="round_count" value="{{ count($data->getTests) }}">
        <table id="admin-datatable" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Test Name</th>
                <th>Start Date Time</th>
                <th>End Date Time</th>
                <th>Order</th>
                {{-- <th>Status</th> --}}
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php($items= [])
            @if(count($data->getTests) > 0)
                @foreach($data->getTests as $data1)
                    @php(array_push($items,$data1->pivot->order_by))
                    <tr>
                        <td>{{ $data1->name }}</td>
                        <td>{{ date('d-m-Y H:i:s A', strtotime($data1->pivot->start_datetime)) }}</td>
                        <td>{{ date('d-m-Y H:i:s A', strtotime($data1->pivot->end_datetime)) }}</td>
                        <td>{{ $data1->pivot->order_by }}</td>
                        {{-- <td>{{ $data1->pivot->status == 1 ? 'Enable' : 'Disable' }}</td> --}}
                        <td>
                            <a href="javascript:void(0);" class="btn btn-success btn-xs edit-module" data-id="{{ $data1->pivot->id }}">
                                Edit
                            </a>
                            <form action="{{ route('quiz.event.deleteRound', ['event_id' => $data->id,'round' => $data1->id]) }}" method="get" enctype="multipart/form-data" style="display: inline-block;">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-xs" data-toggle="confirmation">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div >
    <!-- /.card-body -->
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
    </section>
    <div class="modal fade" id="moduleModal" tabindex="-1" role="dialog" aria-labelledby="moduleModal" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="form-horizontal" action="{{ route('quiz.event.addRound') }}" method="post" id="module-form">
                    @csrf
                    <input type="hidden" name="event_id" id="event_id" value="{{ $data->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Round</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Test</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Test" style="width: 100%;" name="test_id" id="test_id">
                                        <option value="">Select</option>
                                        @foreach ($olympiads as $key => $olympiad)
                                            <option value="{{ $olympiad->id }}" @if($olympiad->id == old('test_id')) selected="selected" @endif>{{ $olympiad->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="start_date_time" id="start_date_time" value="{{ old('start_date_time') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input type="time" class="form-control" name="start_time" id="start_time" value="{{ old('start_time') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="end_date_time" id="end_date_time" value="{{ old('end_date_time') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time" class="form-control" name="end_time" id="end_time" value="{{ old('end_time') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 row">
                            {{-- 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Order</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Test" style="width: 100%;" name="order" id="order">
                                        <option value="">Select</option>
                                        @for($i=1; $i<=$data->rounds; $i++)
                                            @if(!in_array($i,$items))
                                            <option value="{{ $i }}" @if($data->id == old('order')) selected="selected" @endif>{{ $i }}</option>
                                            @endif
                                        @endfor
                                    </select>
                                   <input type="number" min="0" class="form-control" name="order" id="order" value="{{ old('order') }}">--}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                                        <option value="1" selected="selected">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModal" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form class="form-horizontal" action="{{ route('quiz.event.updateRound') }}" method="post" id="update-module-form">
                    @csrf
                    <input type="hidden" name="event_id" id="event_id" value="{{ $data->id }}">
                    <input type="hidden" name="round_id" id="round_id" value="{{ $data->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Update Round</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Test</label>
                                    <select class="select2 select2-hidden-accessible board" data-placeholder="Select Test" style="width: 100%;" name="test" id="test">
                                        <option value="">Select</option>
                                        @foreach ($olympiads as $key => $olympiad)
                                            <option value="{{ $olympiad->id }}">{{ $olympiad->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="start_date_time" id="start_date_time" value="{{ old('start_date_time') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input type="time" class="form-control" name="start_time" id="start_time" value="{{ old('start_time') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="end_date_time" id="end_date_time" value="{{ old('end_date_time') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time" class="form-control" name="end_time" id="end_time" value="{{ old('end_time') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Order</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Test" style="width: 100%;" name="order" id="order">
                                        <option class="append_order" value="" style="display: none" selected="selected"></option>
                                        @for($i=1; $i<=$data->rounds; $i++)
                                            @if(!in_array($i,$items))
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endif
                                        @endfor
                                    </select>
{{--                                    <input type="number" min="0" class="form-control" name="order" id="order" value="{{ old('order') }}">--}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                                        <option value="1" selected="selected">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha512-GDey37RZAxFkpFeJorEUwNoIbkTwsyC736KNSYucu1WJWFK9qTdzYub8ATxktr6Dwke7nbFaioypzbDOQykoRg==" crossorigin="anonymous"></script>
    <script>
        $('document').ready(function(){
            $('#start_date').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true
            });
            $('#end_date').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true
            });
            $('#enroll_start_date').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true
            });
            $('#enroll_end_date').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true
            });

            $('#moduleModal #start_date_time').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                startDate: "{{ date('d-m-Y', strtotime($data->start_date)) }}",
                endDate: "{{ date('d-m-Y', strtotime($data->end_date)) }}"
            });

            $('#moduleModal #end_date_time').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                startDate: "{{ date('d-m-Y', strtotime($data->start_date)) }}",
                endDate: "{{ date('d-m-Y', strtotime($data->end_date)) }}"
            });

            $('#updateModal #start_date_time').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                startDate: "{{ date('d-m-Y', strtotime($data->start_date)) }}",
                endDate: "{{ date('d-m-Y', strtotime($data->end_date)) }}"
            });

            $('#updateModal #end_date_time').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                startDate: "{{ date('d-m-Y', strtotime($data->start_date)) }}",
                endDate: "{{ date('d-m-Y', strtotime($data->end_date)) }}"
            });

            // if (window.jQuery().datetimepicker) {
            //     $('.datetimepicker').datetimepicker({
            //         // Formats
            //         // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
            //         format: 'DD-MM-YYYY hh:mm A',
            //
            //         // Your Icons
            //         // as Bootstrap 4 is not using Glyphicons anymore
            //         icons: {
            //             time: 'fa fa-clock-o',
            //             date: 'fa fa-calendar',
            //             up: 'fa fa-chevron-up',
            //             down: 'fa fa-chevron-down',
            //             previous: 'fa fa-chevron-left',
            //             next: 'fa fa-chevron-right',
            //             today: 'fa fa-check',
            //             clear: 'fa fa-trash',
            //             close: 'fa fa-times'
            //         }
            //     });
            // }

            $('#add-module').on('click', function (){
                $('#moduleModal #module-form')[0].reset();
                $('#moduleModal #event_id').val($('#event_id').val())
                $('#moduleModal').modal('toggle');
            });

            $('.edit-module').on('click', function (){
                $('#updateModal #update-module-form')[0].reset();
                var token = $('input[name=_token]').val();
                var eid = $('#event_id').val();
                var tid = $(this).attr('data-id');
                var order_loop = [];
                $('#updateModal #event_id').val(eid);
                $('#updateModal #round_id').val(tid);

                $.ajax({
                    url: "{{ route('quiz.event.editRound') }}",
                    type: "POST",
                    data: {
                        _token:token,
                        eid:eid,
                        tid:tid
                    },
                    success: function(response) {
                        // alert(response.details.pivot.order_by);
                        $('#update-module-form #test option[value="'+response.details.pivot.test_id+'"]').attr('selected', 'selected');
                        var dt = new Date(response.details.pivot.start_datetime);
                        var d = dt.getDate();
                        var m = dt.getMonth() + 1;
                        var y = dt.getFullYear();
                        var h = dt.getHours();
                        var i = dt.getMinutes();
                        var dateString = (d <= 9 ? '0' + d : d) + '-' + (m <= 9 ? '0' + m : m) + '-' + y;
                        var timeString = (h <= 9 ? '0' + h : h) + ':' + (i <= 9 ? '0' + i : i);
                        // alert(timeString);
                        // $('#update-module-form #start_date_time').val(dateString);
                        $('#update-module-form #start_date_time').datepicker('update', dateString);
                        $('#update-module-form #start_time').val(timeString);
                        var dt1 = new Date(response.details.pivot.end_datetime);
                        var d1 = dt1.getDate();
                        var m1 = dt1.getMonth() + 1;
                        var y1 = dt1.getFullYear();
                        var h1 = dt1.getHours();
                        var i1 = dt1.getMinutes();
                        var dateString1 = (d1 <= 9 ? '0' + d1 : d1) + '-' + (m1 <= 9 ? '0' + m1 : m1) + '-' + y1;
                        var timeString1 = (h1 <= 9 ? '0' + h1 : h1) + ':' + (i1 <= 9 ? '0' + i1 : i1);
                        // $('#update-module-form #end_date_time').val(dateString1);
                        $('#update-module-form #end_date_time').datepicker('update', dateString1);
                        $('#update-module-form #end_time').val(timeString1);
                        // $('#update-module-form #order option[value="'+response.details.pivot.order_by+'"]').attr('selected', 'selected');
                        $('.append_order').val(response.details.pivot.order_by).text(response.details.pivot.order_by).show();

                        $('#update-module-form #status').html('');
                        if(response.details.pivot.status === 1){ selected = "selected" }else{ selected = "" }
                        $('#update-module-form #status').append('<option value="1">Enable</option>');
                        if(response.details.pivot.status === 0){ selected = "selected" }else{ selected = "" }
                        $('#update-module-form #status').append('<option value="0">Disable</option>');

                        $('#update-module-form #test').select2();
                        $('#update-module-form #order').select2();
                    }
                });
                $('#updateModal').modal('toggle');
            });
        });

        function isFree(currselect){
            if(currselect.value == 0){
                $('.price-div').show();
            }else{
                $('.price-div').hide();
            }
        }
        function isSample(currselect){
            if(currselect.value == 0){
                $('.sample_test-div').hide();
            }else{
                $('.sample_test-div').show();
            }
        }
        function isPractice(currselect) {
            if (currselect.value == 'Practice') {
                $('.event-hide').hide();
                $('.practice_test-div').show();
                $('.quiz_test-div').hide();
                $('#rounds').val(1);
                $('#rounds').prop('readonly', true);
            } else if (currselect.value == 'Competition') {
                $('.quiz_test-div').show();
                $('.practice_test-div').hide();
                $('.event-hide').show();
                $('#rounds').val(1);
                $('#rounds').prop('readonly', true);

            } else {
                $('.event-hide').show();
                $('.practice_test-div').hide();
                $('.quiz_test-div').hide();
                $('#rounds').val('');
                $('#rounds').prop('readonly', false);
            }
        }
    </script>
@endsection
