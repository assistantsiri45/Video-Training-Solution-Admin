@extends('adminlte::page')

@section('title', 'Edit Type')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Type</h1>
@stop

@section('css')
    <style>
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, .sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #007bff !important;
            color: #fff !important;
        }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
                <form role="form" id="edit" method="POST" action="{{ route('type.update', $type->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                    <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Course</label>
                                    <select class="form-control " name="course_id[]" id="course_id"  style="width: 100% !important;" multiple>
                                                    <option value=""></option>
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}" @if(in_array($course->id, $selected_course))selected="selected"@endif >{{ $course->name }}</option>
                                                    @endforeach
                                                </select>


                                    @if ($errors->has('course_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Level</label>
                                    <select name="level_id[]" id="level"  class="form-control select-level" style="width: 100% !important;" multiple>
                                    
                                                </select>

                                    @if ($errors->has('level_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Type Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Type Name"
                                        @error('name') is-invalid @enderror" value="{{ old('name', $type->name) }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
         var courseIds;
            courseIds =  @json($selected_course) ?? [];
            var levelIds = @json($selected_levels) ?? [];
            var level_Ids = [];
        getLevels(courseIds);
        if(levelIds.length>0){
               
           
               $.each(levelIds, function( index, element ){
                   var item = parseInt(element);
                   level_Ids.push(item)
               });
              
           }
        function getLevels(courseIds){
            let url = '{{ url('get-levels-by-course') }}';

                   $.ajax({
                       url: url,
                       type: "GET",
                       dataType: 'json',
                       data: {
                           "_token": "{{ csrf_token() }}",
                           "course_ids" : courseIds ,
                       }
                   }).done(function (response) {
                       if(response.length>0){
                         
                           $.each(response, function( index, value ) {
                               
                               var item = value.id
                               let exist = level_Ids.includes(item);
                               
                               $('#level').append('<option  value="' + value.id + '|' + value.course_id + '" ' + ( exist ? 'selected':'') + '>' + value.name + '</option>');
                             
                               

                             

                           });
                          
                       }
                       else{
                        
                       }

                   });
        }
        $(document).ready(function () {
            $('#edit').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255,
                        //lettersandspace: true
                    },
                    "course_id[]": {
                        required: true
                    },
                    "level_id[]": {
                            required: true
                    }
                    
                }
            });
        });
        $('#course_id').select2({
                placeholder: 'Level'
            });
        $('#level').select2({
                placeholder: 'Level'
            });
        $(function () {

// Course wise Levels

$('#course_id').on('change', function () {
   
    
    var CourseID = $(this).val();
   

    if (CourseID.length > 0) {
        $.ajax({
            url: '{{ url('/getlevels/ajax') }}' + '/' + CourseID,
            type: "GET",
            dataType: "json",
            success: function (data) {
                
            $('#level').empty();
                
                $.each(data, function (key, value) {
                    var item = value.id
                               let exist = level_Ids.includes(item);
                    $('#level').append('<option value="' + value.id + '|' + value.course_id + '" ' + ( exist ? 'selected':'') + '>' + value.name + '</option>');
                });

            }
        });
    } else {
       
        $('#level').empty();
    }
});
        });
    </script>
@stop
