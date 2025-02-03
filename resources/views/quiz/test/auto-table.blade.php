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
    @if($data->auto_selection_type != 'subject')
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
    @endif
    @if($data->auto_selection_type != 'subject' && $data->auto_selection_type != 'chapter')
    <div class="col-md-4">
        <div class="form-group">
            <label>Concept</label>
            <select class="select2 select2-hidden-accessible concept" multiple data-placeholder="Select Concept" style="width: 100%;" name="concept[]" id="concept">
                <option value="">Select</option>
                @foreach ($concepts as $key => $concept)
                    <option value="{{ $concept->id }}" id="concept-{{$concept->id}}" data-counter="0" data-name="{{ $concept->name }}" @if(isset($con_ids) && in_array($concept->id, $con_ids)) selected="selected" @endif>{{ $concept->name }}(0)</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
    <input type="hidden" value="{{ $data->auto_selection_type }}" name="auto_selection_type" id="auto_selection_type">
    <input type="hidden" value="{{ $data->is_difficulty }}" name="is_difficulty" id="is_difficulty">
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
                        @if($data->auto_selection_type == 'subject')
                        <th>Subjects</th>
                        @endif
                        @if($data->auto_selection_type == 'chapter')
                        <th>Subjects</th>
                        <th>Chapters</th>
                        @endif
                        @if($data->auto_selection_type == 'concept')
                        <th>Subjects</th>
                        <th>Chapters</th>
                        <th>Concepts</th>
                        @endif
                        @if($data->is_difficulty == 1)
                        <th>Easy</th>
                        <th>Medium</th>
                        <th>Hard</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subjects1 as $subject)
                    @if($data->auto_selection_type == 'subject')
                    <tr>
                    <td class="td-subject">{{ $subject->name }} @if($data->auto_selection_type == 'subject') [<input type="number" class="selection selection-subjects" name="selection[{{ $subject->id }}]" id="subject-{{ $subject->id }}" data-sub="{{ $subject->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('subject_id', $subject->id)->count() }}" onkeydown="return false">] @endif</td>
                    @if($data->is_difficulty == 1 && $data->auto_selection_type == 'subject')
                    <td><input type="number" class="diff-selection selection-easy" name="easy_{{ $subject->id }}" id="easy-{{ $subject->id }}" data-type="easy" data-id="{{ $subject->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('subject_id', $subject->id)->where('difficulty', 'Easy')->count() }}" onkeydown="return false"></td>
                    <td><input type="number" class="diff-selection selection-medium" name="medium_{{ $subject->id }}" id="medium-{{ $subject->id }}" data-type="medium" data-id="{{ $subject->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('subject_id', $subject->id)->where('difficulty', 'Medium')->count() }}" onkeydown="return false"></td>
                    <td><input type="number" class="diff-selection selection-hard" name="hard_{{ $subject->id }}" id="hard-{{ $subject->id }}" data-type="hard" data-id="{{ $subject->id }}" data-old="0" min="0" value="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('subject_id', $subject->id)->where('difficulty', 'Hard')->count() }}" onkeydown="return false"></td>
                    @endif
                    </tr>
                    @endif
                    @if(isset($chapters1))
                    @foreach($chapters1 as $chapter)
                    @if($chapter->subject_id == $subject->id)
                    @if($data->auto_selection_type == 'chapter')
                    <tr>
                    <td class="td-subject">{{ $subject->name }} @if($data->auto_selection_type == 'subject') [<input type="number" class="selection selection-subjects" name="selection[{{ $subject->id }}]" id="subject-{{ $subject->id }}" data-sub="{{ $subject->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('subject_id', $subject->id)->count() }}" onkeydown="return false">] @endif</td>
                    <td class="td-chapter">{{ $chapter->name }} @if($data->auto_selection_type == 'chapter') [<input type="number" class="selection selection-chapters" name="selection[{{ $chapter->id }}]" id="chapter-{{ $chapter->id }}" data-sub="{{ $subject->id }}" data-chap="{{ $chapter->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('chapter_id', $chapter->id)->count() }}" onkeydown="return false">] @endif</td>
                    @if($data->is_difficulty == 1 && $data->auto_selection_type == 'chapter')
                    <td><input type="number" class="diff-selection selection-easy" name="easy_{{ $chapter->id }}" id="easy-{{ $chapter->id }}" data-type="easy" data-id="{{ $chapter->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('chapter_id', $chapter->id)->where('difficulty', 'Easy')->count() }}" onkeydown="return false"></td>
                    <td><input type="number" class="diff-selection selection-medium" name="medium_{{ $chapter->id }}" id="medium-{{ $chapter->id }}" data-type="medium" data-id="{{ $chapter->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('chapter_id', $chapter->id)->where('difficulty', 'Medium')->count() }}" onkeydown="return false"></td>
                    <td><input type="number" class="diff-selection selection-hard" name="hard_{{ $chapter->id }}" id="hard-{{ $chapter->id }}" data-type="hard" data-id="{{ $chapter->id }}" data-old="0" min="0" value="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('chapter_id', $chapter->id)->where('difficulty', 'Hard')->count() }}" onkeydown="return false"></td>
                    @endif
                    </tr>
                    @endif
                    @if(isset($concepts1))
                    @foreach($concepts1 as $concept)
                    @if($concept->chapter_id == $chapter->id)
                    @if($data->auto_selection_type == 'concept')
                    <tr>
                    <td class="td-subject">{{ $subject->name }} @if($data->auto_selection_type == 'subject') [<input type="number" class="selection selection-subjects" name="selection[{{ $subject->id }}]" id="subject-{{ $subject->id }}" data-sub="{{ $subject->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('subject_id', $subject->id)->count() }}" onkeydown="return false">] @endif</td>
                    <td class="td-chapter">{{ $chapter->name }} @if($data->auto_selection_type == 'chapter') [<input type="number" class="selection selection-chapters" name="selection[{{ $chapter->id }}]" id="chapter-{{ $chapter->id }}" data-sub="{{ $subject->id }}" data-chap="{{ $chapter->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('chapter_id', $chapter->id)->count() }}" onkeydown="return false">] @endif</td>
                    <td class="td-concept">{{ $concept->name }} @if($data->auto_selection_type == 'concept') [<input type="number" class="selection selection-concepts" name="selection[{{ $concept->id }}]" id="concept-{{ $concept->id }}" data-sub="{{ $subject->id }}" data-chap="{{ $chapter->id }}" data-con="{{ $concept->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('concept_id', $concept->id)->count() }}" onkeydown="return false">] @endif</td>
                    @if($data->is_difficulty == 1 && $data->auto_selection_type == 'concept')
                    <td><input type="number" class="diff-selection selection-easy" name="easy_{{ $concept->id }}" id="easy-{{ $concept->id }}" data-type="easy" data-id="{{ $concept->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('concept_id', $concept->id)->where('difficulty', 'Easy')->count() }}" onkeydown="return false"></td>
                    <td><input type="number" class="diff-selection selection-medium" name="medium_{{ $concept->id }}" id="medium-{{ $concept->id }}" data-type="medium" data-id="{{ $concept->id }}" data-old="0" value="0" min="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('concept_id', $concept->id)->where('difficulty', 'Medium')->count() }}" onkeydown="return false"></td>
                    <td><input type="number" class="diff-selection selection-hard" name="hard_{{ $concept->id }}" id="hard-{{ $concept->id }}" data-type="hard" data-old="0" data-id="{{ $concept->id }}" min="0" value="0" max="{{ \App\Models\Quiz\Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->where('concept_id', $concept->id)->where('difficulty', 'Hard')->count() }}" onkeydown="return false"></td>
                    @endif
                    </tr>
                    @endif
                    @endif
                    @endforeach
                    @endif
                    @endif
                    @endforeach
                    @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
