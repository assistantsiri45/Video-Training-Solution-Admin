@extends('adminlte::page')
@section('title', 'Module')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Module</h1>
        </div>
     </div>
<style type="text/css">
         /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
@endsection
@section('content')
@include('quiz.error')
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Create Module</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.module.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="select2 select2-hidden-accessible board" data-placeholder="Select Board" style="width: 100%;" name="board" id="board" onchange="getGrade()">
                                        <option value="">Select</option>
                                        @foreach ($boards as $key => $board)
                                        <option value="{{ $board->id }}" @if($board->id == old('board')) selected="selected" @endif>{{ $board->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Level</label>
                                    <select class="select2 select2-hidden-accessible grade" data-placeholder="Select Grade" style="width: 100%;" name="grade" id="grade" onchange="getSubject()">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Subject</label>
                                    <select class="select2 select2-hidden-accessible subject" data-placeholder="Select Subject" style="width: 100%;" name="subject" id="subject" onchange="getChapter()">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Chapter</label>
                                    <select class="select2 select2-hidden-accessible chapter" data-placeholder="Select Chapter" style="width: 100%;" name="chapter" id="chapter">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <div class="form-group">
                                    <label>Concept</label>
                                    <select class="select2 select2-hidden-accessible concept" data-placeholder="Select Concept" style="width: 100%;" name="concept" id="concept">
                                        <option value="0">Select</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                                        <option value="1" selected="">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>

                            <!-- /.col -->

                            <!-- /.col -->
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                <div class="col-md-6 border-right">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>No. of Questions</label>
                                            <input type="number" class="form-control" name="ques" id="ques" min="0"  value="0" readonly="readonly">
                                            <span>Max Questions: <b id="total-ques">0</b></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Minutes</label>
                                            <input type="number" min="0" class="form-control" name="minutes" id="minutes" value="{{ old('minutes') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Seconds</label>
                                            <input type="number" min="0" max="59" class="form-control" name="seconds" id="seconds" value="{{ old('seconds') }}">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="qorder" id="qorder">
                                            <label class="form-check-label" for="name">Order wise Questions</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="aorder" id="aorder">
                                            <label class="form-check-label" for="name">Order wise Answers</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h2>Difficulty</h2>
                                    <div class="form-group row">
                                        <div class="col-sm-1"></div>
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Easy <span class="badge easy-count" style="background-color: #777;border-radius: 10px;">0</span></label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" name="easy" id="easy" min="0"  value="0">
                                            <input type="hidden" class="form-control" id="easy-value" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-1"></div>
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Medium <span class="badge medium-count" style="background-color: #777;border-radius: 10px;">0</span></label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" name="medium" id="medium" min="0"  value="0">
                                            <input type="hidden" class="form-control" id="medium-value" placeholder="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-1"></div>
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Hard <span class="badge hard-count" style="background-color: #777;border-radius: 10px;">0</span></label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" name="hard" id="hard" min="0"  value="0">
                                            <input type="hidden" class="form-control" id="hard-value" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <input type="submit" value="Save" class="btn btn-success float-right">
                                <a href="{{ route('quiz.module.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>

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
    <script>
        $(document).ready(function (){

            $('.concept').on('change', function (){

                var token   = $('input[name=_token]').val();
                var concept = $('.concept option:selected').val();
                $.ajax({
                    url: "{{ route('quiz.getQuestions') }}",
                    type: "POST",
                    data: {
                        _token:token,
                        concept:concept
                    },
                    success: function(response) {

                        $('.easy-count').text(response.easy);
                        $('.medium-count').text(response.medium);
                        $('.hard-count').text(response.hard);

                        $('#easy-value').val(response.easy);
                        $('#medium-value').val(response.medium);
                        $('#hard-value').val(response.hard);

                        $('#easy').attr('max',response.easy);
                        $('#medium').attr('max',response.medium);
                        $('#hard').attr('max',response.hard);

                        var total = response.easy + response.medium + response.hard;

                        $('#ques').attr('max',total);
                        $('#total-ques').text(total);


                    }
                });
            });

            $(document).on('keyup', '#easy', function(evtobj) {
                if (!(evtobj.altKey || evtobj.ctrlKey || evtobj.shiftKey)) {
                    if (evtobj.keyCode == 16) {
                        return false;
                    }
                    if (evtobj.keyCode == 17) {
                        return false;
                    }
                }
                var easy   = $('#easy').val();
                var medium = $('#medium').val();
                var hard   = $('#hard').val();

                var ques = parseInt(easy)+parseInt(medium)+parseInt(hard);
                $('#ques').val(ques);
                // $('#total-ques').text(ques);
            });

             $(document).on('keyup', '#medium', function(evtobj) {
                if (!(evtobj.altKey || evtobj.ctrlKey || evtobj.shiftKey)) {
                    if (evtobj.keyCode == 16) {
                        return false;
                    }
                    if (evtobj.keyCode == 17) {
                        return false;
                    }
                }
                var easy   = $('#easy').val();
                var medium = $('#medium').val();
                var hard   = $('#hard').val();

                var ques = parseInt(easy)+parseInt(medium)+parseInt(hard);
                $('#ques').val(ques);
                // $('#total-ques').text(ques);
            });

             $(document).on('keyup', '#hard', function(evtobj) {
                if (!(evtobj.altKey || evtobj.ctrlKey || evtobj.shiftKey)) {
                    if (evtobj.keyCode == 16) {
                        return false;
                    }
                    if (evtobj.keyCode == 17) {
                        return false;
                    }
                }
                var easy   = $('#easy').val();
                var medium = $('#medium').val();
                var hard   = $('#hard').val();

                var ques = parseInt(easy)+parseInt(medium)+parseInt(hard);
                $('#ques').val(ques);
                // $('#total-ques').text(ques);
            }); 

        });
    </script>
@endsection
