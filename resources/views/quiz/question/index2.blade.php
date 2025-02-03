@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
@yield('css')
<style type="text/css">
    .btn-align{
        margin-right: 10px;
    }
    #admin td.details-control {
        background: url('public/quiz/dist/img/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    .sub-table tr.shown td.details-control {
        background: url('public/quiz/dist/img/details_close.png') no-repeat center center;
    }
</style>
@section('title', 'Paragraph Questions')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Paragraph Questions</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="/Documents/ExcelSample/excel.zip" class="pull-right btn btn-danger btn-align" download>Excel Sample</a>
                <a href="{{ route('quiz.uploadExcelView') }}" class="pull-right btn btn-success btn-align">Upload Excel</a>
                <a href="{{ route('quiz.question.create') }}" class="pull-right btn btn-info btn-align">Add New</a>
            </ol>
        </div>
    </div>
@endsection

@section('content')
@include('quiz.error')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="admin" class="table table-condensed">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Board</th>
                                    <th>Grade</th>
                                    <th>Subject</th>
                                    <th>Chapter</th>
                                    <th>Concept</th>
                                    <th>Question Type</th>
                                    <th>Content Type</th>
                                    <th>Paragraph</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($datas) > 0)
                                    @foreach($datas as $data)
                                        <tr data-toggle="collapse" data-target="#demo{{ $data->id }}" class="accordion-toggle">
                                            <td>
                                                <button class="btn btn-default btn-xs details-control" style="background-color: #5cb85c">
                                                    <span class="nav-icon fas fa-plus"></span>
                                                </button>
                                            </td>
                                            <td>
                                                @if(!empty($data->getBoard))
                                                    {{ $data->getBoard->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($data->getGrade))
                                                    {{ $data->getGrade->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($data->getConcept->getChapter->getSubject))
                                                    {{ $data->getConcept->getChapter->getSubject->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($data->getConcept->getChapter))
                                                    {{ $data->getConcept->getChapter->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($data->getConcept))
                                                    {{ $data->getConcept->name }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ config('constants.qt.'.$data->question_type) }}
                                            </td>
                                            <td>
                                                {{ config('constants.ct.'.$data->content_type) }}
                                            </td>
                                            <td>
                                                @if(!empty($data->getParagraph))
                                                    {{ $data->getParagraph->name }}
                                                @endif
                                            </td>
                                            <td>{{ $data->status == 1 ? 'Enable' : 'Disable' }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="12" class="hiddenRow">
                                                <div class="accordian-body collapse" id="demo{{ $data->id }}">
                                                    <table class="table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Question</th>
                                                            <th>Difficulty</th>
                                                            <th>Score</th>
                                                            <th>Time</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        // dd($data->getParagraph->getQuestions);
                                                        ?>
                                                        @foreach($data->getParagraph->getQuestions as $data1)

                                                        <tr>
                                                            <td>{!! $data1->question !!}</td>
                                                            <td>{{ $data1->difficulty }}</td>
                                                            <td>{{ $data1->score }}</td>
                                                            <td>{{ str_pad(floor($data1->time/ 60), 2, 0, STR_PAD_LEFT).':'.str_pad($data1->time %60, 2, 0)  }}</td>
                                                            <td>{{ $data->status == 1 ? 'Enable' : 'Disable' }}</td>
                                                            <td>
                                                                <a href="{{ route('quiz.question.edit', ['question' => $data1->id]) }}" class="btn btn-success btn-xs">
                                                                    Edit
                                                                </a>
                                                                <form action="{{ route('quiz.question.destroy', ['question' => $data1->id]) }}" method="post" enctype="multipart/form-data" style="display: inline-block;">
                                                                    @csrf
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                    <button type="submit" class="btn btn-danger btn-xs" data-toggle="confirmation">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
    <script>
        $(function () {
            $('.details-control').on('click', function (){
                if($(this).children('span').hasClass('fa-plus')){
                    $(this).children('span').removeClass('fa-plus');
                    $(this).children('span').addClass('fa-minus');
                    $(this).css('background-color', '#d9534f');
                }else if($(this).children('span').hasClass('fa-minus')){
                    $(this).children('span').removeClass('fa-minus');
                    $(this).children('span').addClass('fa-plus');
                    $(this).css('background-color', '#5cb85c');
                }
            });
        });
    </script>
@endsection
