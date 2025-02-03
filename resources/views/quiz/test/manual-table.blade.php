<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Subject</label>
            <select class="select2 select2-hidden-accessible subject" multiple data-placeholder="Select Subject" style="width: 100%;" name="subject[]" id="subject" >
                <option value="">Select</option>
                @foreach ($subjects as $key => $subject)
                    <option value="{{ $subject->id }}" id="subject-{{$subject->id}}" data-counter="0" data-name="{{ $subject->name }}" @if(isset($sub_ids) && in_array($subject->id, $sub_ids)) selected="selected" @endif >{{ $subject->name }}(0)</option>
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
                    <option value="{{ $chapter->id }}" id="chapter-{{$chapter->id}}" data-counter="0" data-name="{{ $chapter->name }}" @if(isset($chap_ids) && in_array($chapter->id, $chap_ids)) selected="selected" @endif>{{ $chapter->name }}(0)</option>
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
                    <option value="{{ $concept->id }}" id="concept-{{$concept->id}}" data-counter="0" data-name="{{ $concept->name }}" @if(isset($con_ids) && in_array($concept->id, $con_ids)) selected="selected" @endif>{{ $concept->name }}(0)</option>
                @endforeach
            </select>
        </div>
    </div> -->
</div>
<div class="row">
    <div class="col-12">
        <a href="#" class="btn btn-success float-right" id="refresh" style="margin-right: 5px;">Refresh Questions</a>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="admin" class="table table-bordered table-hover" style="overflow-x: scroll;">
                    <thead>
                    <tr>
                        <th width="300px">Question</th>
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
{{--                            <td>{!! \Illuminate\Support\Str::limit($question->question, 150, $end='...') !!}</td>--}}
                            <td>{{ $question->question }}</td>
                            <td>
                                {{ config('constants.qt.'.$question->question_type) }}
                            </td>
                            <td>
                                @if($question->getParagraph != null)

                            {{ $question->getParagraph->name}}
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
                           
                            <td>
                                
                                 <input type="checkbox" class="question_id" name="question_id[]" @if(isset($test_questions) && in_array($question->id,$test_questions)) checked @endif value="{{ $question->id }}" id="question_id_{{ $question->id }}" data-min="{{ str_pad(floor($question->time/ 60), 2, 0, STR_PAD_LEFT) }}" data-sec="{{ str_pad($question->time %60, 2, 0) }}">

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
