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
@endsection
@section('content')
@include('quiz.error')
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Create Event</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.event.store') }}" method="post" enctype="multipart/form-data" id="eventSubmit">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="select2 select2-hidden-accessible board" data-placeholder="Select Course" style="width: 100%;" name="board" id="board">
                                        <option></option>
                                        @foreach ($boards as $key => $board)
                                        <option value="{{ $board->id }}" @if($board->id == old('board')) selected="selected" @endif>{{ $board->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Level</label>

                                    <select class="select2 select2-hidden-accessible grade" data-placeholder="Select Level" style="width: 100%;" name="grade" id="grade">
                                        <option></option>
                                        @foreach ($grades as $key => $grade)
                                            <option value="{{ $grade->id }}" @if($grade->id == old('grade')) selected="selected" @endif>{{ $grade->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Instruction</label>
                                    <select class="select2 select2-hidden-accessible instruction" data-placeholder="Select Instruction" style="width: 100%;" name="instruction" id="instruction" >
                                        <option></option>
                                        @foreach ($instructions as $key => $instruction)
                                            <option value="{{ $instruction->id }}" @if($instruction->id == old('grade')) selected="selected" @endif>{{ $instruction->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Event Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Type" style="width: 100%;" name="type" id="type" onchange="isPractice(this)">
                                        <option></option>
                                        <!-- <option value="Practice"  @if("Practice" == old('type')) selected="selected" @endif>Practice</option> -->
                                        <option value="Competition"  @if("Competition" == old('type')) selected="selected" @endif>Competition</option>
                                        <!-- <option value="Olympiad"  @if("Olympiad" == old('type')) selected="selected" @endif>Olympiad</option> -->
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Mode</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Type" style="width: 100%;" name="mode" id="mode">
                                        <option></option>
                                        <option value="online" @if("online" == old('mode')) selected="selected" @endif>Online</option>
                                        <option value="offline" @if("offline" == old('mode')) selected="selected" @endif>Offline</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Device</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Type" style="width: 100%;" name="device" id="device">
                                        <option></option>
                                        <option value="computer" @if("computer" == old('device')) selected="selected" @endif>Computer</option>
                                        <option value="tablet" @if("tablet" == old('device')) selected="selected" @endif>Tablet</option>
                                        <option value="moblie" @if("moblie" == old('device')) selected="selected" @endif>Moblie</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Is Free</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="is_free" id="is_free" onchange="isFree(this)" value="{{ old('is_free') }}">
                                        <option value="1" @if(  1 == old('is_free')) selected="" @endif>Yes</option>
                                        <option value="0" @if(  0 == old('is_free')) selected="" @endif  @if(!old('is_free')) selected="" @endif>No</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3 price-div" @if(old('is_free') == 1) style="display: none" @else style="display: block;" @endif>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" min="0" class="form-control" name="price" id="price" value="{{ old('price') }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Access Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Type" style="width: 100%;" name="access_type" id="access_type">
                                        <option value="public" @if("public" == old('access_type')) selected="selected" @endif>Public</option>
                                        {{-- <option value="private" @if("private" == old('access_type')) selected="selected" @endif>Private</option> --}}
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3 rounds-div">
                                <div class="form-group">
                                    <label>Rounds</label>
                                    <input type="number" min="0" class="form-control" name="rounds" id="rounds" value="{{ old('rounds') }}" @if(old('rounds')) readonly @endif>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-3 event-hide">
                                <div class="form-group">
                                    <label>Event Start Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="start_date" id="start_date" value="{{ old('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3 event-hide">
                                <div class="form-group">
                                    <label>Event End Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="end_date" id="end_date" value="{{ old('end_date') }}">
                                </div>
                            </div>
                            {{-- <div class="col-md-3 event-hide">
                                <div class="form-group">
                                    <label>Enroll Start Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="enroll_start_date" id="enroll_start_date" value="{{ old('enroll_start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3 event-hide">
                                <div class="form-group">
                                    <label>Enroll End Date</label>
                                    <input type="text" readonly="readonly" class="form-control" name="enroll_end_date" id="enroll_end_date" value="{{ old('enroll_end_date') }}">
                                </div>
                            </div>  --}}

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Select Logo/File</label>
                                    <input type="file" class="form-control" name="attachment" id="attachment">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- <div class="col-md-3 event-hide" @if("Practice" == old('type')) style="display: none" @endif>
                                <div class="form-group">
                                    <label>Is Sample</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Is Sample" style="width: 100%;" name="is_sample" id="is_sample" onchange="isSample(this)" value="{{old('is_sample') }}">
                                        <option></option>
                                        <option value="1"  @if(1 == old('is_sample')) selected="selected" @endif>Yes</option>
                                        <option value="0"  @if(0 == old('is_sample')) selected="selected" @endif>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 sample_test-div event-hide" @if("0" == old('is_sample') || "Practice" == old('type')) style="display: none" @endif>
                                <div class="form-group">
                                    <label>Sample Test</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Sample" style="width: 100%;" name="sample_test">
                                        <option></option>
                                        @foreach ($tests as $key => $test)
                                            <option value="{{ $test->id }}" @if($test->id == old('sample_test')) selected="selected" @endif>{{ $test->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-3 practice_test-div" @if("Practice" != old('type')) style="display: none" @endif>
                                <div class="form-group">
                                    <label>Test</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Test" style="width: 100%;" name="practice_test" id="practice_test">
                                        <option></option>
                                        @foreach ($practices as $key => $practice)
                                            <option value="{{ $practice->id }}" @if($practice->id == old('practice_test')) selected="selected" @endif>{{ $practice->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 quiz_test-div" @if("Competition" != old('type')) style="display: none" @endif>
                                <div class="form-group">
                                    <label>Test</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Test" style="width: 100%;" name="quiz_test" id="quiz_test">
                                        <option></option>
                                        @foreach ($competitions as $key => $competition)
                                            <option value="{{ $competition->id }}"  @if($competition->id == old('quiz_test')) selected="selected" @endif>{{ $competition->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3  quiz_test-div" @if("Competition" != old('type')) style="display: none" @endif>
                                <div class="form-group">
                                    <label>Start Time</label>
                                    <input type="time" class="form-control" name="start_time" id="start_time" value="{{old('start_time') }}">
                                </div>
                            </div>
                            <div class="col-md-3  quiz_test-div" @if("Competition" != old('type')) style="display: none" @endif>
                                <div class="form-group">
                                    <label>End Time</label>
                                    <input type="time" class="form-control" name="end_time" id="end_time" value="{{ old('end_time') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Is Published</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Type" style="width: 100%;" name="is_published" id="is_published">
                                        <option value="1" @if("1" == old('is_published')) selected="selected" @endif>Published</option>
                                        <option value="0" @if("0" == old('is_published')) selected="selected" @endif>Unpublished</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Order By</label>
                                    <input type="number" min="1" class="form-control" name="order_by" id="order_by" value="{{ old('order_by') }}">
                                </div>
                            </div> -->
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status" id="status">
                                        @if (!empty(old('status')))
                                        <option value="1" @if (old('status') == 1 )  selected @endif>Enable</option>
                                        <option value="0" @if (old('status') == 0 )  selected @endif>Disable</option>
                                        @endif
                                        <option value="1" selected="">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div> --}}
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <input type="button" value="Save" class="btn btn-success float-right" onclick="eventSubmit()">
                                <a href="{{ route('quiz.event.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>

                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
    <script>
        //  var today = new Date().toISOString().split('T')[0];
        // document.getElementById("start_date")[0].setAttribute('min', today);
        // document.getElementById("end_date")[0].setAttribute('min', today);
        $('document').ready(function(){
            // $('#start_date').datepicker({
            //     format: 'dd-mm-yyyy',
            //     startDate: new Date(),
            // });
            // $('#end_date').datepicker({
            //     format: 'dd-mm-yyyy'
            // });
            $( document ).on( 'focus', '#start_date', function(event) {
                $('#end_date').val('');
                $(this).datepicker({
                    format: 'dd-mm-yyyy',
                    startDate: new Date(),
                }).on('change', function(e, date) {
                  var alt_date = $('#start_date').val();
                  $('#end_date').datepicker({
                    format: 'dd-mm-yyyy',
                    startDate: alt_date,
                  })
                });
            });


            $('#enroll_start_date').datepicker({
                format: 'dd-mm-yyyy'
            });
            $('#enroll_end_date').datepicker({
                format: 'dd-mm-yyyy'
            });

            if (window.jQuery().datetimepicker) {
                $('.datetimepicker').datetimepicker({
                    // Formats
                    // follow MomentJS docs: https://momentjs.com/docs/#/displaying/format/
                    format: 'DD-MM-YYYY hh:mm A',

                    // Your Icons
                    // as Bootstrap 4 is not using Glyphicons anymore
                    icons: {
                        time: 'fa fa-clock-o',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-check',
                        clear: 'fa fa-trash',
                        close: 'fa fa-times'
                    }
                });
            }
        });

        function eventSubmit(){
            var start_date = $('#start_date').val();
            var end_date   = $('#end_date').val();
            if(start_date > end_date){
                alert("Start date cannot be greater than end date");
                return false;
            }else{
                $('#eventSubmit').submit();
            }
        }

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
        function isPractice(currselect){
            if(currselect.value == 'Practice'){
                $('.event-hide').hide();
                $('.practice_test-div').show();
                $('.quiz_test-div').hide();
                $('#rounds').val(1);
                $('#rounds').prop('readonly', true);
                $('#is_sample').val(0).trigger('change.select2');
            }
            else if(currselect.value == 'Competition'){
                $('.quiz_test-div').show();
                $('.practice_test-div').hide();
                $('.event-hide').show();
                $('#rounds').val(1);
                $('#rounds').prop('readonly', true);
                $('#is_sample').val(1).trigger('change.select2');

            }
            else{
                $('.event-hide').show();
                $('.practice_test-div').hide();
                $('.quiz_test-div').hide();
                $('#rounds').val('');
                $('#rounds').prop('readonly', false);
                $('#is_sample').val(1).trigger('change.select2');
            }
        }
    </script>
@endsection
