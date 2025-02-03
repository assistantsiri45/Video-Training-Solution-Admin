@extends('adminlte::page')

@section('title', 'Create Notification')

@section('content_header')
    <h1 class="m-0 text-dark">Create Custom Notification</h1>
@stop
<style>
  .select2-container {
   
    width: 100% !important;
}
    </style>
@section('content')
    <div class="row">

        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('custom-notifications.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <input class="custom-checkbox checkbox" id="all_student" name="all_student" type="checkbox" checked/>
                                <label for="all_student">  All students</label>
                            </div>
                            <div class="col-md-2">
                            <input class="custom-checkbox checkbox" id="is_course" name="is_course" type="checkbox"/>
                                <label for="is_course">Course</label>
                            </div>
                            <div class="col-md-2">
                                <input class="custom-checkbox checkbox" id="is_package" name="is_package" type="checkbox"/>
                                <label for="is_package">Package</label>
                            </div>
                            <div class="col-md-2">
                                <input class="custom-checkbox checkbox" id="is_level" name="is_level" type="checkbox"/>
                                <label for="is_level">Level</label>
                            </div>
                            <div class="col-md-2">
                                <input class="custom-checkbox checkbox" id="is_student" name="is_student" type="checkbox"/>
                                <label for="is_student">Student</label>
                            </div>
                        </div>
                        <div class="row mt-3 d-none" id="package-row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                <select multiple class="form-control  @error('package') is-invalid @enderror" style="width: 350px;" name="package[]" id="package" >
                                        
                                    @foreach ($packages as $package)
                                            <option value="{{ $package->id }}">{{ $package->name }}</option>


                                            @endforeach
                                   
                                        </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 d-none" id="course-row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                <select multiple class="form-control @error('course') is-invalid @enderror select2" style="width: 350px;" name="course[]" id="course" multiple required>
                                        @foreach ($course as $course)
                                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 d-none" id="level-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select multiple class="form-control @error('level') is-invalid @enderror" style="width: 350px;" name="level[]" id="level" required>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 d-none" id="student-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">student Name</label>
                                    <select disabled multiple class="form-control @error('email') is-invalid @enderror select2" style="width: 350px;" name="email[]" id="email" required>
                                        @foreach ($student as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{$student->email}})</option>
                                        @endforeach
                                    </select>


                                    <!-- <input disabled class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="off"> -->
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title" value="{{ old('title') }}" autocomplete="off">
                                    @error('title')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('title') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="body">Body</label>
                                    <textarea class="form-control @error('notification_description') is-invalid @enderror"
                                              id="notification_description" rows="6" name="notification_description"
                                              placeholder="Body" autocomplete="off">{{ old('notification_description') }}</textarea>
                                    @error('notification_description')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('notification_description') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                      
                            <div class="col-md-2">
                                <input class="custom-checkbox" id="email_notification" name="email_notification" type="checkbox" />
                                <label for="email_notification"> Email</label>
                            </div>
                            <!-- <div class="col-md-2">
                                <input class="custom-checkbox" id="whatsapp_notification" name="whatsapp_notification" type="checkbox"/>
                                <label for="email_notification">Whatsapp</label>
                            </div> -->
                           <!-- <div class="col-md-2">
                                <input class="custom-checkbox" id="sms_notification" name="sms_notification" type="checkbox"/>
                                <label for="sms_notification"> SMS</label>
                            </div> -->
                        </div>
                        <div class="row d-none" id="sms_body">
                        <div class="col-md-2">
                        <select class="form-control " name="template_id" id="template_id"  style="width: 100% !important;">
                                                    <option value=""></option>
                                                    @foreach ($sms as $sms)
                                                        <option value="{{ $sms->template_id }}">{{ $sms->title }}</option>
                                                    @endforeach
                                                </select>
                                <label for="sms_notification"> Template</label>
                            </div>

                            <div class="col-md-6">
                        
                                 
                                    <textarea class="form-control" id="smsbody" name="bodyc" readonly  rows="6" value="" placeholder="Sms Body"></textarea>
   <label>Body</label>

                                </div>


</div>
</div>





                        <div class="row mt-3 d-none" id="email_body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="body">Email Body</label>
                                    <div class="border w-100" id="editorjs"></div>
                                </div>
                            </div>
                        </div>
                        <input id="body" name="body" type="hidden">
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        !function(t,e){"object"==typeof exports&&"undefined"!=typeof module?module.exports=e():"function"==typeof define&&define.amd?define(e):(t=t||self).edjsHTML=e()}(this,(function(){"use strict";var t={delimiter:function(){return"<br/>"},header:function(t){var e=t.data;return"<h"+e.level+"> "+e.text+" </h"+e.level+">"},paragraph:function(t){return"<p> "+t.data.text+" </p>"},list:function(t){var e=t.data,n="unordered"===e.style?"ul":"ol",r="";return e.items&&(r=e.items.map((function(t){return"<li> "+t+" </li>"})).reduce((function(t,e){return t+e}),"")),"<"+n+"> "+r+" </"+n+">"},image:function(t){var e=t.data,n=e.caption?e.caption:"Image";return'<img src="'+(e.file?e.file.url:"")+'" alt="'+n+'" />'},quote:function(t){var e=t.data;return"<blockquote> "+e.text+" </blockquote> - "+e.caption}};function e(t){return new Error('[31m The Parser function of type "'+t+'" is not defined. \n\n  Define your custom parser functions as: [34mhttps://github.com/pavittarx/editorjs-html#extend-for-custom-blocks [0m')}return function(n){return void 0===n&&(n={}),Object.assign(t,n),{parse:function(n){return n.blocks.map((function(n){return t[n.type]?t[n.type](n):e(n.type)}))},parseBlock:function(n){return t[n.type]?t[n.type](n):e(n.type)}}}}));
    </script>
    <script>

        $(function () {
			  $('#email').select2({
                placeholder: 'Student Name'
            });
            $('#course').select2({
                placeholder: 'Course'
            });
            const editor = new EditorJS({
                placeholder: 'Body',
                tools: {
                    header: {
                        class: Header,
                        placeholder: 'Header'
                    },
                    {{--image: {--}}
                    {{--    class: ImageTool,--}}
                    {{--    config: {--}}
                    {{--        endpoints: {--}}
                    {{--            byFile: '{{ route('blogs.images.store') }}'--}}
                    {{--        }--}}
                    {{--    }--}}
                    {{--}--}}
                }
            });

            $('#create').validate({
                ignore: '#editorjs *',
                rules: {
                    title: {
                        required:function(){
                            if ($("#email_notification").prop('checked')) {
                       return true;
                            }else{
                               
                                if(!$("#notification_description").val()){
                            return true;
                        }else{
                            return false;
                        }
                            }
                  }                   
                 },
                 "package[]": {
                        required:  function(){
                    if ($("#is_package").prop('checked')) {
                       return true;
                      
                    }else{
                       return false;
                      
                    }
                }
                    },
                    "level[]": {
                        required: function(){
                    if ($("#is_level").prop('checked')) {
                       return true;
                      
                    }else{
                       return false;
                      
                    }
                }
                    },
                    "course[]": {
                        required: function(){
                    if ($("#is_course").prop('checked')) {
                       return true;
                      
                    }else{
                       return false;
                      
                    }
                }
                    },
                    "email[]": {
                        required: function(){
                    if ($("#email").prop('checked')) {
                       return true;
                      
                    }else{
                       return false;
                      
                    }
                }

},
                    notification_description:  {
                  required: function(){
                    if ($("#email_notification").prop('checked')) {
                       return true;
                    }else{
                        if(!$("#title").val()){
                            return true;
                        }else{
                            return false;
                        }
                        
                    }
                  }
                },
                
                }
            });

            $("#package").select2({
                placeholder: 'Please choose a package'
            });

            $("#level").select2({
                placeholder: 'Please choose a level'
            });

            $(".checkbox").change(function() {
                $(".checkbox").prop('checked', false);
                $(this).prop('checked', true);
            });

            $("#sms_notification").change(function() {
                if ($(this).prop('checked')) {
                    $("#sms_body").removeClass('d-none');
                }
                else {
                    $("#sms_body").addClass('d-none');
                }
            });

            jQuery('#all_student').change(function() {
                if ($(this).prop('checked')) {
                    $("#email").attr('disabled');
                    $("#package-row").addClass('d-none');
                    $("#level-row").addClass('d-none');
                    $("#course-row").addClass('d-none');
                    $("#student-row").addClass('d-none');
                    $("#is_level").prop('checked', false);
                    $("#is_package").prop('checked', false);
                    $("#is_student").prop('checked', false);
                }
            });

            jQuery('#is_package').change(function() {
                if ($(this).prop('checked')) {
                    $("#email").attr('disabled');
                    $("#package-row").removeClass('d-none');
                    $("#level-row").addClass('d-none');
                    $("#course-row").addClass('d-none');
                    $("#student-row").addClass('d-none');
                    $("#all_student").prop('checked', false);
                    $("#is_level").prop('checked', false);
                    $("#is_student").prop('checked', false);
                }
            });
            jQuery('#is_course').change(function() {
                if ($(this).prop('checked')) {
                    $("#email").attr('disabled');
                    $("#course-row").removeClass('d-none');
                    $("#level-row").addClass('d-none');
                    $("#package-row").addClass('d-none');
                    $("#student-row").addClass('d-none');
                    $("#all_student").prop('checked', false);
                    $("#is_level").prop('checked', false);
                    $("#is_student").prop('checked', false);
                }
            });

            jQuery('#is_level').change(function() {
                if ($(this).prop('checked')) {
                    $("#email").attr('disabled');
                    $("#package-row").addClass('d-none');
                    $("#course-row").addClass('d-none');
                    $("#level-row").removeClass('d-none');
                    $("#student-row").addClass('d-none');
                    $("#all_student").prop('checked', false);
                    $("#is_package").prop('checked', false);
                    $("#is_student").prop('checked', false);
                }
            });

            jQuery('#is_student').change(function() {
                if ($(this).prop('checked')) {
                    $("#email").removeAttr('disabled');
                    $("#package-row").addClass('d-none');
                    $("#course-row").addClass('d-none');
                    $("#level-row").addClass('d-none');
                    $("#student-row").removeClass('d-none');
                    $("#all_student").prop('checked', false);
                    $("#is_package").prop('checked', false);
                }
            });

            $('#create').on('submit', function (e) {
                e.preventDefault();

                if ($('#create').valid()) {
                    editor.save().then((data) => {
                        $('#body').val(JSON.stringify(data));
                        $('#create')[0].submit();
                    }).catch((error) => {
                        console.log(error);
                    });
                }
            });
            $("#template_id").change(function() {
               var template_id=$("#template_id").val();
               $.ajax({
                            url: '{{ url('custom-notifications/templatebody') }}' + '/' + template_id,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                              $("#smsbody").val(data.body);
                            }
                        });
            });
            
            $("#title").change(function(){
                if ($("#email_notification").prop('checked')==false) {
if($("#title").val()){
    $("#notification_description-error").hide();
    $('#notification_description').removeClass('is-invalid');
}
                }
            });

            $("#notification_description").change(function(){
                if ($("#email_notification").prop('checked')==false) {
if($("#notification_description").val()){
    $("#title-error").hide();
    $('#title').removeClass('is-invalid');
}
                }
            });

        });
    </script>
@stop

@push('css')
    <style>
        .ce-block__content,
        .ce-toolbar__content {
            max-width: unset;
            padding-left: 12px;
            padding-right: 12px;
        }
    </style>
@endpush
