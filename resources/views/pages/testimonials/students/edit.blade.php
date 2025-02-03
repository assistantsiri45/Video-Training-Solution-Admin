@extends('adminlte::page')

@section('title', 'Create Subject')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Testimonial</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary">
            <form role="form" id="edit" method="POST" action="{{ route('student-testimonials.update', $testimonial->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div id="hidden-inputs-container">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="name">First Name</label>
                                            <input type="text" name="fname" class="form-control" id="name" placeholder="Name"
                                                   @error('fname') is-invalid @enderror value="{{ old('fname', $testimonial->first_name) }}" readonly>
                                            @error('fname')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('fname') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="title">Last Name</label>
                                            <input type="text" name="lname" class="form-control" id="lname" placeholder="Last Name"
                                                   @error('title') is-invalid @enderror value="{{ old('lname', $testimonial->last_name) }}" readonly>
                                            @error('title')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('lname') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" id="email" name="email" class="form-control" placeholder="Email" @error('email') is-invalid @enderror value="{{ old('email', $testimonial->email) }}" readonly>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('email') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile</label>
                                            <div class="input-group">
                                                
                                                <input type="text" id="mobile" name="phone" class="form-control" placeholder="Mobile" @error('phone') is-invalid @enderror value="{{ old('mobile', $testimonial->phone) }}" readonly>
                                                @error('phone')
                                                <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('phone') }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="introduction">Testimonial</label>
                                            <textarea class="form-control" id="testimonial" name="testimonial" required rows="5" placeholder="Testimonial" @error('introduction') is-invalid @enderror>{{ old('testimonial', $testimonial->testimonial) }}</textarea>

                                            @error('testimonial')
                                            <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('testimonial') }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                             
                                <div class="row">
                                    <div class="col-md-12">
                                        <input class="custom-checkbox" id="is-published" name="is_published"
                                               type="checkbox" @if ($testimonial->publish ==2 ) checked @endif />
                                        <label for="is-published">Is Published</label>
                                    </div>
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
        $(document).ready(function () {
            $('#create').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255,
                        lettersandspace: true
                    },
                    course_id: {
                        required: true
                    },
                    level_id: {
                        required: true
                    }
                }
            });
        });
    </script>
@stop
