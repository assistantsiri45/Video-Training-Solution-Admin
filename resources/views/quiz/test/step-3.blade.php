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
                    <h3 class="card-title">Create Test => Step - 3</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.test.step3-submit', ['QID' => $data->id]) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-4" @if($data->test_type == 'Practice') style="display: none" @endif>
                                <div class="form-group">
                                    <label>Max Attempt</label>
                                    <input type="number" class="form-control" name="attempt" id="attempt">
                                </div>
                                <!-- /.form-group -->
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Negative (%)</label>
                                    <input type="number" class="form-control" name="negative" id="negative" min="0" max="100">
                                </div>
                                <!-- /.form-group -->
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Order By</label>
                                    <input type="number" class="form-control" name="order" min="1" id="order">
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Is Feedback</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Is Feedback" style="width: 100%;" name="is_feedback" id="is_feedback">
                                        <option value="">Select</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4 feedback-type">
                                <div class="form-group">
                                    <label>Feedback Type</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Feedback Type Type" style="width: 100%;" name="feedback" id="feedback">
                                        <option value="">Select</option>
                                        <option value="option">Show answer</option>
                                        <option value="question_wise">Question Wise</option>
                                        <option value="answer_wise">Answer Wise</option>
                                    </select>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                                        <option value="1" selected="selected">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                        <br>
                        <br>
                        <!-- <div class="row">
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
                                                        <input type="checkbox" class="learning_id" name="learning_id[]" value="{{ $learning->id }}" id="learning_id_{{ $learning->id }}">
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
                                <input type="submit" value="Submit" class="btn btn-success float-right">
                                <a href="{{url('quiz/test/getStep2/'.$data->id).'?ids='.$data->id}}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
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
