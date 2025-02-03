@extends('adminlte::page')
@section('title', 'Test Master')
@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Test Master</h1>
        </div>
     </div>
@endsection
@section('content')
@include('quiz.error')
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Create Test => Step - 1</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.test.store') }}" method="post">
                        @csrf
                        {{-- {{dd($data)}} --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Course" style="width: 100%;" name="board" id="board" onchange="getGrade()">
                                        <option value="">Select</option>
                                        @foreach ($boards as $key => $board)
                                        @if($data)
                                        <option {{ $data->board_id == $board->id ? "selected" : "" }} value="{{ $board->id }}">{{ $board->name }}</option>
                                        @else
                                        <option {{ old('board') == $board->id ? "selected" : "" }} value="{{ $board->id }}">{{ $board->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Level</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Level" style="width: 100%;" name="grade" id="grade">
                                        <option value="">Select</option>
                                        @foreach ($grades as $key => $grade)
                                        @if($data)
                                        <option {{ $data->grade_id == $grade->id ? "selected" : "" }} value="{{ $grade->id }}">{{ $grade->name }}</option>
                                        @else
                                        <option {{ old('grade') == $grade->id ? "selected" : "" }} value="{{ $grade->id }}">{{ $grade->name }}</option>
                                        @endif
                                        @endforeach 
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Instruction</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Instruction" style="width: 100%;" name="instruction">
                                        <option value="">Select</option>
                                        @foreach ($instructions as $key => $instruction)
                                        @if ($data)
                                            <option value="{{ $instruction->id }}" @if($data->grade_id == $instruction->id ) selected @endif>{{ $instruction->name }}</option>
                                        @else
                                            <option value="{{ $instruction->id }}" @if(old('instruction') == $instruction->id ) selected @endif>{{ $instruction->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    @if($data)
                                    <input type="text" class="form-control" name="name" id="name" value="{{$data->name}}">
                                    @else
                                    <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}">
                                    @endif
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Test Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Test Type" style="width: 100%;" name="test_type" id="test_type">
                                        <option value="">Select</option>
                                        @foreach(config('constants.et') as $key => $c_type)
                                        {{-- @if ($data)
                                        <option value="{{ $key }}" @if($data->test_type == $key) selected @endif>{{ $c_type }}</option>
                                        @else --}}
                                        <option value="{{ $key }}" selected>{{ $c_type }}</option>
                                        {{-- @endif --}}
                                        @endforeach
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4 sections">
                                <div class="form-group">
                                    <label>No. of Sections / Modules</label>
                                    <input type="number" class="form-control" min="1" name="sections" id="sections" value="1" readonly>
                                </div>
                                <!-- /.form-group -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Show Camera</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Show Camera" style="width: 100%;" name="show_camera" id="show_camera" required="">
                                        <option value="">Select</option>
                                        <option value="1" @if(old('show_camera') == '1') selected="selected" @endif>Yes</option>
                                        <option value="0" @if(old('show_camera') == '0') selected="selected" @endif>No</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Question Selection Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Question Selection Type" style="width: 100%;" name="ques_type" id="ques_type">
                                        <option value="">Select</option>
                                        @if($data)
                                        <option value="auto" @if($data->ques_selection_type == 'auto') selected @endif>Auto/Rubric</option>
                                        <option value="manual" @if($data->ques_selection_type == 'manual') selected @endif>Manual</option>
                                        @else
                                        <option value="auto"  @if(old('ques_type') == 'auto') selected @endif>Auto/Rubric</option>
                                        <option value="manual"  @if(old('ques_type') == 'manual') selected @endif>Manual</option>
                                        @endif
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4 auto_ques_type" style="display: none;">
                                <div class="form-group">
                                    <label>Auto Selection Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Auto Selection Type" style="width: 100%;" name="auto_ques_type" id="auto_ques_type">
                                        <option value="">Select</option>
                                        <option value="subject">Subject Wise</option>
                                        <option value="chapter">Chapter Wise</option>
                                        {{-- <option value="concept">Concept Wise</option> --}}
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4 is_difficulty" style="display: none;">
                                <div class="form-group">
                                    <label>Is Difficulty</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Difficulty" style="width: 100%;" name="is_difficulty" id="is_difficulty">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                @if($data)
                                <input type="hidden" name="updated_id" value="{{ $data->id }}">
                                @endif
                                <input type="submit" value="Next" class="btn btn-success float-right">
                                <a href="{{url('quiz/test')}}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
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
        $(function () {
            $('#test_type').on('change', function (){
                if($(this).val() !== 'Olympiad'){
                    $('#sections').val(1);
                    $('.sections').hide();
                }else{
                    $('.sections').show();
                    $('#sections').val(1);
                }
            });

            $('#ques_type').on('change', function (){
                if($(this).val() === 'auto'){
                    $('.auto_ques_type').show();
                    $('.is_difficulty').show();
                }else{
                    $('.auto_ques_type').hide();
                    $('.is_difficulty').hide();
                }
            });
        });
    </script>
@endsection
