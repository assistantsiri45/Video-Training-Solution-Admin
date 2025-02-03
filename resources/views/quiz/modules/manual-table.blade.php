<?php //dd($questions[0]->getSubject->id); ?>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            @php
                $qids = $selQuestions->pluck('question_id')->toArray();
            @endphp
            <label>Subject</label>
            <select class="select2 select2-hidden-accessible subject" multiple data-placeholder="Select Subject" style="width: 100%;" name="subject[]" id="subject" >
                <option value="">Select</option>
                @foreach ($subjects as $key => $subject)
                    @php
                        $subct = \App\Models\Quiz\Question::whereIn('id', $qids)->where('subject_id', $subject->id)->count();
                    @endphp
                    <option value="{{ $subject->id }}" id="subject-{{$subject->id}}" data-counter="{{ $subct }}" data-name="{{ $subject->name }}" @if(isset($sub_ids) && in_array($subject->id, $sub_ids)) selected="selected" @endif >{{ $subject->name }}({{ $subct }})</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Chapter</label>
            <select class="select2 select2-hidden-accessible chapter" multiple data-placeholder="Select Chapter" style="width: 100%;" name="chapter[]" id="chapter" >
                <option value="">Select</option>
                @foreach ($chapters as $key => $chapter)
                    @php
                        $chapct = \App\Models\Quiz\Question::whereIn('id', $qids)->where('chapter_id', $chapter->id)->count();
                    @endphp
                    <option value="{{ $chapter->id }}" id="chapter-{{$chapter->id}}" data-counter="{{ $chapct }}" data-name="{{ $chapter->name }}" @if(isset($chap_ids) && in_array($chapter->id, $chap_ids)) selected="selected" @endif>{{ $chapter->name }}({{ $chapct }})</option>
                @endforeach
            </select>
        </div>
    </div>
    <!-- <div class="col-md-4">
        <div class="form-group">
            <label>Concept</label>
            <select class="select2 select2-hidden-accessible concept" multiple data-placeholder="Select Concept" style="width: 100%;" name="concept[]" id="concept">
                <option value="">Select</option>
                @foreach ($concepts as $key => $concept)
                    @php
                        $conct = \App\Models\Quiz\Question::whereIn('id', $qids)->where('concept_id', $concept->id)->count();
                    @endphp
                    <option value="{{ $concept->id }}" id="concept-{{$concept->id}}" data-counter="{{ $conct }}" data-name="{{ $concept->name }}" @if(isset($con_ids) && in_array($concept->id, $con_ids)) selected="selected" @endif>{{ $concept->name }}({{ $conct }})</option>
                @endforeach
            </select>
        </div>
    </div> -->
</div>
<input type="hidden" id="selected-ques" value="{{ $module->no_of_ques }}">
<input type="hidden" id="selected-min" value="{{ floor($module->time / 60) }}">
<input type="hidden" id="selected-sec" value="{{ $module->time % 60 }}">
<div class="row">
    <div class="col-12">
        <a href="#" class="btn btn-success float-right" id="refresh" style="margin-right: 5px;">Refresh Questions</a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Select Questions</h3>
            </div>
            <div class="card-body">
                <table id="admin" class="table table-bordered table-hover" style="overflow-x: scroll;">
                    <thead>
                    <tr>
                        <th>Question</th>
                        <th>Question Type</th>
                        <th>Paragraph</th>
                        <th>Difficulty</th>
                        <th>Time</th>
                        <th>Subject</th>
                        <th>Chapter</th>
                        <!-- <th>Concept</th> -->
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($questions as $question)
                        <tr>
                            <td>{!! \Illuminate\Support\Str::limit($question->question, 150, $end='...') !!}</td>
                            <td>
                                {{ config('constants.qt.'.$question->question_type) }}
                            </td>
                            <td>
                                @if(!empty($question->paragraph_id))
                                {{ $question->getParagraph->name }}
                                @endif
                            </td>
                            <td>{{ $question->difficulty }}</td>
                            <td>{{ str_pad(floor($question->time/ 60), 2, 0, STR_PAD_LEFT).':'.str_pad($question->time %60, 2, 0)  }}</td>
                            <td>
                                @if(!empty($question->getSubject))
                                    {{ $question->getSubject->name }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($question->getChapter))
                                    {{ $question->getChapter->name }}
                                @endif
                            </td>
                            <!-- <td>
                                @if(!empty($question))
                                    {{ $question->name }}
                                @endif
                            </td> -->
                            <td>
                                {{-- <input type="checkbox" class="question_id" name="question_id[]" value="{{ $question->id }}" id="question_id_{{ $question->id }}" data-sub="{{ $question->getSubject->id }}" data-chap="{{ $question->getChapter->id }}" data-con="{{ $question->id }}" data-min="{{ str_pad(floor($question->time/ 60), 2, 0, STR_PAD_LEFT) }}" data-sec="{{ str_pad($question->time %60, 2, 0) }}"> --}}
                                <input type="checkbox" class="question_id" name="question_id[]" value="{{ $question->id }}" id="question_id_{{ $question->id }}" data-min="{{ str_pad(floor($question->time/ 60), 2, 0, STR_PAD_LEFT) }}" data-sec="{{ str_pad($question->time %60, 2, 0) }}">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Selected Questions</h3>
            </div>
            <div class="card-body">
                <table id="admin" class="table table-bordered table-hover" style="overflow-x: scroll;">
                    <thead>
                    <tr>
                        <th width="300px">Question</th>
                        <th>Question Type</th>
                        <th>Difficulty</th>
                        <th>Time</th>
                        <th>Subject</th>
                        <th>Chapter</th>
                        <!-- <th>Concept</th> -->
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($selQuestions as $selQuestion)
                        <tr>
                            <td>{!! $selQuestion->getQuestion->question !!}</td>
                            <td>
                                {{ config('constants.qt.'.$selQuestion->getQuestion->question_type) }}
                            </td>
                            <td>{{ $selQuestion->getQuestion->difficulty }}</td>
                            <td>{{ str_pad(floor($selQuestion->getQuestion->time/ 60), 2, 0, STR_PAD_LEFT).':'.str_pad($selQuestion->getQuestion->time %60, 2, 0)  }}</td>
                            <td>
                                @if(!empty($selQuestion->getQuestion->getSubject))
                                    {{ $selQuestion->getQuestion->getSubject->name }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($selQuestion->getQuestion->getChapter))
                                    {{ $selQuestion->getQuestion->getChapter->name }}
                                @endif
                            </td>
                            <!-- <td>
                                @if(!empty($selQuestion->getQuestion))
                                    {{ $selQuestion->getQuestion->name }}
                                @endif
                            </td> -->
                            <td>
                                {{-- <input type="checkbox" class="question_id selected" name="question_id[]" value="{{ $selQuestion->id }}" id="question_id_{{ $selQuestion->getQuestion->id }}" data-sub="{{ $selQuestion->getQuestion->getSubject->id }}" data-chap="{{ $selQuestion->getQuestion->getChapter->id }}" data-con="{{ $selQuestion->getQuestion->id }}" data-min="{{ str_pad(floor($selQuestion->getQuestion->time/ 60), 2, 0, STR_PAD_LEFT) }}" data-sec="{{ str_pad($selQuestion->getQuestion->time %60, 2, 0) }}" checked> --}}
                                <input type="checkbox" class="question_id selected" name="question_id[]" value="{{ $selQuestion->id }}" id="question_id_{{ $selQuestion->getQuestion->id }}" data-min="{{ str_pad(floor($selQuestion->getQuestion->time/ 60), 2, 0, STR_PAD_LEFT) }}" data-sec="{{ str_pad($selQuestion->getQuestion->time %60, 2, 0) }}" checked>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
