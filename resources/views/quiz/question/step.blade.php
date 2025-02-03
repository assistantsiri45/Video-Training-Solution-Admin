@extends('adminlte::page')
@section('title', 'Question Bank')
@section('content_header')
<style type="text/css">
   .form-check-label {
   margin: 0 20px 0 20px;
   }

   input.custom-control-label1 {
       width : 20px;
       height : 20px;
   }
</style>
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Question Bank</h1>
        </div>
    </div>
@endsection

@section('content')
@include('quiz.error')

    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Create Question</h3>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.question.step-submit', ['QID' => $ques_details->id]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Question</label>
                                    <textarea class="tinymce-editor1 " name="question">{!! old('question') !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="quest_desc">
                                <div class="form-group">
                                    <label>Question Description</label>
                                    <textarea class="tinymce-editor1 " name="question_description">{!! old('question_description') !!}</textarea>
                                </div>
                            </div>
{{--                                <div class="col-md-3" id="quest_file">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>File</label>--}}
{{--                                        <input type="file" class="form-control" name="q_attachment" id="q_attachment">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                        </div>
                        <div class="row">
                          <div class="col-md-3">
                             <div class="form-group">
                                <label>Instruction</label>
                                <select class="select2 select2-hidden-accessible instruction" data-placeholder="Select Instruction" style="width: 100%;" name="instruction" id="instruction">
                                   <option value="">Select</option>
                                   @foreach ($instructions as $key => $instruction)
                                   <option value="{{ $instruction->id }}" {{ old('instruction') == $instruction->id ? "selected" : "" }}>{{ $instruction->name }}</option>
                                   @endforeach
                                </select>
                             </div>
                          </div>
                            @if($ques_details->is_paragraph == 1)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Paragraph</label>
                                    <select class="select2 select2-hidden-accessible instruction" data-placeholder="Select Paragraph" style="width: 100%;" name="paragraph" id="paragraph">
                                        <option value="">Select</option>
                                        @foreach ($paragraphs as $key => $paragraph)
                                            <option value="{{ $paragraph->id }}" @if($ques_details->paragraph_id == $paragraph->id) selected="selected" @endif>{{ $paragraph->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Difficulty</label>
                                    <select class="select2 select2-hidden-accessible difficulty" data-placeholder="Select" style="width: 100%;" name="difficulty" id="difficulty">
                                        <option value="">Select</option>
                                        @foreach(config('constants.difficult') as $key => $difficult)
                                            <option value="{{ $key }}" {{ old('difficulty') == $key ? "selected" : "" }}>{{ $difficult }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Score</label>
                                    <input type="number"class="form-control" min="0" name="score" id="score" value="{{ old('score') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Minutes</label>
                                    <input type="number" min="0" class="form-control" name="minutes" id="minutes" value="{{ old('minutes') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Seconds</label>
                                    <input type="number" min="0" max="59" class="form-control" name="seconds" id="seconds" value="{{ old('seconds') }}">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label>Order By</label>
                                    <input type="number" min="1" class="form-control" name="order_by" id="order_by" value="{{ old('order_by') }}">
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="select2 select2-hidden-accessible" data-placeholder="Select Status" style="width: 100%;" name="status">
                                        <option value="1" selected="">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>QuestionCorrectFeedback</label>
                                    <textarea class="form-control" name="correct_feedback">{!! old('correct_feedback') !!}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>QuestionInCorrectFeedback</label>
                                    <textarea class="form-control" name="incorrect_feedback">{!! old('incorrect_feedback') !!}</textarea>
                                </div>
                            </div>
                            @if($ques_details->question_type == 'MCQ')
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>QuestionPartiallyFeedback</label>
                                    <textarea class="form-control" name="partially_feedback">{!! old('partially_feedback') !!}</textarea>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="card-header">
                            <h3 class="card-title">Create Options</h3>
                        </div>
                        @for($i=0; $i<$ques_details->no_of_options; $i++)
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Content type</label>
                                        <select class="select2 select2-hidden-accessible ans_content_type" data-placeholder="Select Content type" style="width: 100%;" name="ans_content_type[]">
                                            <option value="">Select</option>
                                            @foreach(config('constants.ct') as $key => $c_type)
                                                <option value="{{ $key }}" {{ old('ans_content_type.'.$i) == $key ? "selected" : "" }}>{{ $c_type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input @if($ques_details->question_type == 'SCQ') type="radio" @elseif($ques_details->question_type == 'MCQ') type="checkbox" @endif class="form-check-input is_correct" name="is_correct[]"  value="{{$i}}">Is Correct
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Answer</label>
                                        <textarea  class="tinymce-editor1 " name="answer[]">{!! old('answer.'.$i) !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Feedback</label>
                                        <textarea class="form-control" name="feedback[]">{!! old('feedback.'.$i) !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endfor
                        <div class="append"></div>
                        <div class="row">
                            <div class="col-12">
                                <input type="submit" value="Save" name="save" class="btn btn-success float-right">
                                {{-- @if($ques_details->is_paragraph == 1)
                                <input type="submit" value="Next" name="next"  class="btn btn-success float-right" style="margin-right: 5px;">
                                @else --}}
                                <a href="{{ url('quiz/question/create').'?id='.$ques_details->id }}" class="btn btn-secondary float-right" style="margin-right: 5px;">Back</a>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
@include('quiz.master-layouts.quizjs')
    <script>

        $(function () {
            var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var ImageUrl = "{{ route('quiz.image-upload') }}";

            tinymce.init({
                selector: 'textarea.tinymce-editor1',
                height: 200,
                plugins: 'code print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
                images_upload_url: ImageUrl,
                mobile: {
                    plugins: 'code print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap quickbars emoticons'
                },
                menu: {
                },

                menubar: 'file edit view insert format tools table tc help',
                toolbar: 'code | undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange  formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
                autosave_ask_before_unload: true,
                autosave_interval: '30s',
                autosave_prefix: '{path}{query}-{id}-',
                autosave_restore_when_empty: false,
                autosave_retention: '2m',
                image_advtab: true,
                convert_urls: false,
                link_list: [
                    { title: 'My page 1', value: 'https://www.tiny.cloud' },
                    { title: 'My page 2', value: 'http://www.moxiecode.com' }
                ],
                image_list: [
                    { title: 'My page 1', value: 'https://www.tiny.cloud' },
                    { title: 'My page 2', value: 'http://www.moxiecode.com' }
                ],
                image_class_list: [
                    { title: 'None', value: '' },
                    { title: 'Some class', value: 'class-name' }
                ],
                importcss_append: true,
                templates: [
                    { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
                    { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
                    { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
                ],
                template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
                template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
                image_caption: true,
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                noneditable_noneditable_class: 'mceNonEditable',
                toolbar_mode: 'sliding',
                spellchecker_whitelist: ['Ephox', 'Moxiecode'],
                content_style: '.mymention{ color: gray; }',
                contextmenu: 'link image imagetools table',
                a11y_advanced_options: true,
                skin: useDarkMode ? 'oxide-dark' : 'oxide',
                content_css: useDarkMode ? 'dark' : 'default',
                /* we override default upload handler to simulate successful upload*/
                images_upload_handler: function (blobInfo, success, failure) {
                    var xhr, formData;
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', ImageUrl);
                    var token = '{{ csrf_token() }}';
                    xhr.setRequestHeader("X-CSRF-Token", token);
                    xhr.onload = function() {
                        var json;
                        if (xhr.status !== 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        success(json.location);
                    };
                    formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                },
                // images_upload_handler: function (blobInfo, success, failure) {
                //     setTimeout(function () {
                //         /* no matter what you upload, we will turn it into TinyMCE logo :)*/
                //         success('http://moxiecode.cachefly.net/tinymce/v9/images/logo.png');
                //     }, 2000);
                // },
            });
        });

        var n = 1;
        // function getContentType(){
        //     var content_type = $('.content_type option:selected').val();
        //     if(content_type=='Text'){
        //         $('#quest_desc').css('display','block');
        //         $('#quest_file').css('display','none');
        //     }else{
        //         $('#quest_desc').css('display','none');
        //         $('#quest_file').css('display','block');
        //     }
        //
        // }
        function getQuestionType(){
            var question_type = $('.question_type option:selected').val();
            if(question_type=='SCQ'){
                $('.is_correct').attr('type','radio');

            }else{
                $('.is_correct').attr('type','checkbox');
            }

        }

        function appendAnswerRow(){
            var content_type = <?php echo json_encode(config('constants.ct')) ?>;
            var html = '<div class="row"><div class="col-md-3"><div class="form-group"><label>Content type</label><select class="select2 select2-hidden-accessible ans_content_type" id="sel_'+n+'" data-placeholder="Select Content type" style="width: 100%;" name="ans_content_type[]" onchange="getContentType()"><option value="">Select</option>';
            $.each(content_type,function( key, value ) {
                html += '<option value="'+key+'">'+value+'</option>';
            });
            html += '</select></div></div><div class="col-md-3"><div class="form-group"><label>Answer</label><textarea class="tinymce-editor1 desc_'+n+'" name="answer[]"></textarea></div></div><div class="col-md-3"><div class="form-group"><label>File</label><input type="file" class="form-control" name="a_attachment[]"></div></div><div class="col-md-2" id="is_correct"><div class="form-check"><label class="form-check-label"><input type="radio" class="form-check-input is_correct" name="is_correct[]" value="1">Is Correct </label></div></div><div class="col-md-1"><input type="button" value="Add" class="btn btn-success float-right"></div></div>'

            $('.append').html(html);

            $('#sel_'+n).select2();
            var nme = 'textarea.desc_'+n+'';
            $(function () {
                tinymce.init({
                    selector: nme,
                    height: 200,
                });
            });
            n++;
        }
    </script>
@endsection
