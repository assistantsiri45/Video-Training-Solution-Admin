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
                    <h3 class="card-title">Edit Test => Step - 1</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.test.update', ['test' => $data->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Course" style="width: 100%;" name="board" disabled>
                                        <option value="">Select</option>
                                        @foreach ($boards as $key => $board)
                                            <option value="{{ $board->id }}" @if($data->board_id == $board->id) selected="selected" @endif>{{ $board->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Level</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Level" style="width: 100%;" name="grade" disabled>
                                        <option value="">Select</option>
                                        @foreach ($grades as $key => $grade)
                                            <option value="{{ $grade->id }}" @if($data->grade_id == $grade->id) selected="selected" @endif>{{ $grade->name }}</option>
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
                                            <option value="{{ $instruction->id }}" @if($data->instruction_id == $instruction->id) selected="selected" @endif>{{ $instruction->name }}</option>
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
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $data->name }}">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Test Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Test Type" style="width: 100%;" name="test_type" id="test_type">
                                        <option value="">Select</option>
                                        @foreach(config('constants.et') as $key => $c_type)
                                            <option value="{{ $key }}" @if($data->test_type == $key) selected="selected" @endif>{{ $c_type }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4 sections" @if($data->test_type != 'Olympiad') style="display: none" @endif>
                                <div class="form-group">
                                    <label>No. of Sections / Modules</label>
                                    <input type="number" class="form-control" min="1" name="sections" id="sections" value="{{ $data->sections }}">
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
                                        <option value="1" @if($data->show_camera == '1') selected="selected" @endif>Yes</option>
                                        <option value="0" @if($data->show_camera == '0') selected="selected" @endif>No</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Question Selection Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Question Selection Type" style="width: 100%;" name="ques_type" id="ques_type" readonly disabled>
                                        <option value="">Select</option>
                                        <option value="auto" @if($data->ques_selection_type == 'auto') selected="selected" @endif>Auto/Rubrik</option>
                                        <option value="manual" @if($data->ques_selection_type == 'manual') selected="selected" @endif>Manual</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4 auto_ques_type" @if($data->ques_selection_type == 'manual') style="display: none;" @endif>
                                <div class="form-group">
                                    <label>Auto Selection Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Auto Selection Type" style="width: 100%;" name="auto_ques_type" id="auto_ques_type" readonly disabled>
                                        <option value="">Select</option>
                                        <option value="subject" @if($data->auto_selection_type == 'subject') selected="selected" @endif>Subject Wise</option>
                                        <option value="chapter" @if($data->auto_selection_type == 'chapter') selected="selected" @endif>Chapter Wise</option>
                                        <option value="concept" @if($data->auto_selection_type == 'concept') selected="selected" @endif>Concept Wise</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4 is_difficulty" @if($data->ques_selection_type == 'manual') style="display: none;" @endif>
                                <div class="form-group">
                                    <label>Is Difficulty</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Difficulty" style="width: 100%;" name="is_difficulty" id="is_difficulty">
                                        <option value="1" @if($data->is_difficulty == 1) selected="selected" @endif>Yes</option>
                                        <option value="0" @if($data->is_difficulty == 0) selected="selected" @endif>No</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 attempt" @if($data->test_type == 'Practice') style="display: none" @endif>
                                <div class="form-group">
                                    <label>Max Attempt</label>
                                    <input type="number" class="form-control" name="attempt" id="attempt" value="{{ $data->attempt }}">
                                </div>
                                <!-- /.form-group -->
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Negative (%)</label>
                                    <input type="number" class="form-control" name="negative" id="negative" min="0" max="100" value="{{ $data->negative }}">
                                </div>
                                <!-- /.form-group -->
                            </div>

                            <!-- div class="col-md-4">
                                <div class="form-group">
                                    <label>Order By</label>
                                    <input type="number" class="form-control" name="order" id="order" value="{{ $data->order_by }}" min="1">
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Is Feedback</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Is Feedback" style="width: 100%;" name="is_feedback" id="is_feedback">
                                        <option value="">Select</option>
                                        <option value="1" @if($data->is_feedback == 1) selected="selected" @endif>Yes</option>
                                        <option value="0" @if($data->is_feedback == 0) selected="selected" @endif>No</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4 feedback-type" @if($data->is_feedback == 0) style="display: none;" @endif>
                                <div class="form-group">
                                    <label>Feedback Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Feedback Type" style="width: 100%;" name="feedback" id="feedback" >
                                        <option value="">Select</option>
                                        <option value="question_wise" @if($data->feedback_type == 'question_wise') selected="selected" @endif>Question Wise</option>
                                        <option value="answer_wise" @if($data->feedback_type == 'answer_wise') selected="selected" @endif>Answer Wise</option>
                                        <option value="option" @if($data->feedback_type == 'option') selected="selected" @endif>Show answer</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                                        <option value="1" @if($data->status == 1) selected="selected" @endif>Enable</option>
                                        <option value="0" @if($data->status == 0) selected="selected" @endif>Disable</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="qorder" id="qorder" @if($data->ques_ordered == 1) checked="checked" @endif>
                                    <label class="form-check-label" for="name">Order wise Questions</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="aorder" id="aorder" @if($data->ans_ordered == 1) checked="checked" @endif>
                                    <label class="form-check-label" for="name">Order wise Answers</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <!--<div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Content Library</h3>
                                    </div>
                                    <div class="card-body">
                                        <table id="admin" class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Content Type</th>
                                                <th>Taxomony</th>
                                                <th>Learning</th>
                                                <th>Subject</th>
                                                <th>Chapter</th>
                                                <th>Concept</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(!empty($learnings))
                                            @foreach($learnings as $learning)
                                                <tr>
                                                    <td>{{ $learning->name }}</td>
                                                    <td>{{ $learning->content_type }}</td>
                                                    <td>{{ $learning->getTaxonomy->name }}</td>
                                                    <td>{{ $learning->getLearning->name }}</td>
                                                    <td>
                                                        @if(!empty($learning->getSubject))
                                                            {{ $learning->getSubject->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($learning->getChapter))
                                                            {{ $learning->getChapter->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($learning->getContentConcept))
                                                            {{ $learning->getContentConcept->name }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" class="learning_id" name="learning_id[]" value="{{ $learning->id }}" id="learning_id_{{ $learning->id }}" @if(in_array($learning->id, $data->getContent->pluck('content_id')->toArray())) checked="checked" @endif>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-12">
                                <input type="submit" value="Update" class="btn btn-success float-right">
                                <a href="{{ route('quiz.test.index') }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Test Modules</h3>
                        @if(count($modules) < $data->sections)
                        <h3 class="card-title float-right"><a href="{{ route('quiz.module.getStep2', ['ID' => $data->id]) }}" class="pull-right btn btn-info">Add New Module</a></h3>
                        @endif
                    </div>
                <div class="card-body">
                    <table id="admin-datatable" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Module Name</th>
                            <th>No. of Questions</th>
                            <th>Time</th>
                            <th>Cutoff</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($modules as $module)
                            <tr>
                                <td>{{ $module->name }}</td>
                                <td>{{ $module->no_of_ques }}</td>
                                <td>{{ str_pad(floor($module->time/ 60), 2, 0, STR_PAD_LEFT).':'.str_pad($module->time %60, 2, 0)  }}</td>
                                <td>{{ $module->cut_off }}%</td>
                                <td>{{ $module->status == 1 ? 'Enable' : 'Disable' }}</td>
                                <td>
                                    <a href="{{ route('quiz.modules.edit', ['module' => $module->id]) }}" class="btn btn-success btn-xs">
                                        Edit
                                    </a>
                                    <!-- <form action="{{ route('quiz.modules.destroy', ['module' => $module->id]) }}" method="post" enctype="multipart/form-data" style="display: inline-block;"> -->
                                        <!-- @csrf -->
                                        <!-- <input type="hidden" name="_method" value="DELETE"> -->
                                        {{-- <button onclick="confirmAlert('/quiz/modules/destroy/<?php echo $module->id ?>')" type="submit" class="btn btn-danger btn-xs" data-toggle="confirmation">
                                            Delete
                                        </button> --}}
                                    <!-- </form> -->
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
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

                if($(this).val() === 'Practice'){
                    $('.attempt').hide();
                }else{
                    $('.attempt').show();
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

            $('#is_feedback').on('change', function (){
                if($(this).val() != 1){
                    $('.feedback-type').hide();
                }else{
                    $('.feedback-type').show();
                }
            });
        });
    </script>
@endsection
