@extends('adminlte::page')

@section('title', 'Create Package')

@section('content_header')
    <h1 class="m-0 text-dark">Create Package</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form role="form" id="create" method="POST" action="{{ route('packages.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                    <label for="name">Package Name</label>
                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea  id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('name') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                    <label for="price">Price</label>

                                    <input id="price" name="price" type="text" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">

                                    @error('price')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('price') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="discounted_price">Discounted Price</label>

                                    <input id="discounted_price" name="discounted_price" type="text" class="form-control @error('discounted_price') is-invalid @enderror" value="{{ old('discounted_price') }}">

                                    @error('discounted_price')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('discounted_price') }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="discounted_price_expiry_at">Discounted Price Expiry At</label>

                                    <input id="discounted_price_expiry_at" name="discounted_price_expiry_at" type="date" class="form-control @error('discounted_price_expiry_at') is-invalid @enderror" value="{{ old('discounted_price_expiry_at') }}">

                                    @error('discounted_price_expiry_at')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('discounted_price_expiry_at') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="special_price">Special Price</label>

                                    <input id="special_price" name="special_price" type="text" class="form-control @error('special_price') is-invalid @enderror" value="{{ old('special_price') }}">

                                    @error('special_price')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price') }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="special_price_expiry_at">Special Price Expiry At</label>

                                    <input id="special_price_expiry_at" name="special_price_expiry_at" type="date" class="form-control @error('special_price_expiry_at') is-invalid @enderror" value="{{ old('special_price_expiry_at') }}">

                                    @error('special_price_expiry_at')
                                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('special_price_expiry_at') }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Exam</label>

                                    <x-inputs.course id="course_id" class="{{ $errors->has('course_id') ? ' is-invalid' : '' }}">
                                        @if(!empty(old('course_id')))
                                            <option value="{{ old('course_id') }}" selected>{{ old('course_id_text') }}</option>
                                        @endif
                                    </x-inputs.course>

                                    @if ($errors->has('course_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('course_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Level</label>
                                    <x-inputs.level id="level_id" related="#course_id">
                                        @if(!empty(old('level_id')))
                                            <option value="{{ old('level_id') }}" selected>{{ old('level_id_text') }}</option>
                                        @endif
                                    </x-inputs.level>

                                    @if ($errors->has('level_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('level_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr />

                        {{--<div class="row">
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Subject</label>
                                    <x-inputs.subject id="subject_id" related="#level_id">
                                        @if(!empty(old('subject_id')))
                                            <option value="{{ old('subject_id') }}" selected>{{ old('subject_id_text') }}</option>
                                        @endif
                                    </x-inputs.subject>

                                    @if ($errors->has('subject_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('subject_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="name">Chapter</label>
                                    <x-inputs.chapter id="chapter_id" related="#subject_id">
                                        @if(!empty(old('chapter_id')))
                                            <option value="{{ old('chapter_id') }}" selected>{{ old('chapter_id_text') }}</option>
                                        @endif
                                    </x-inputs.chapter>

                                    @if ($errors->has('chapter_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('chapter_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-5 col-md-3">
                                <div class="form-group">
                                    <label for="name">Professor</label>
                                    <x-inputs.professor id="professor_id">
                                        @if(!empty(old('professor_id')))
                                            <option value="{{ old('professor_id') }}" selected>{{ old('professor_id_text') }}</option>
                                        @endif
                                    </x-inputs.professor>

                                    @if ($errors->has('professor_id'))
                                        <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('professor_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-1 col-md-1">
                                <div class="form-group" style="margin-top: 32px;">
                                    <button type="button" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>--}}

                        <div class="table-responsive">
                            <table id="tbl-chapter-professors" class="table table-hover" data-chapter-professors='@json(old('chapters'))' >
                                <thead>
                                <tr>
                                    <th class="col-md-4" style="min-width: 200px;">Subject</th>
                                    <th class="col-md-4" style="min-width: 200px;">Chapter</th>
                                    <th class="col-md-3" style="min-width: 200px;">Professor</th>
                                    <th width="30"></th>
                                </tr>
                                </thead>
                                <thead>
                                <tr>
                                    <td>
                                        <x-inputs.subject id="subject_id" related="#level_id"></x-inputs.subject>
                                    </td>
                                    <td>
                                        <x-inputs.chapter id="chapter_id" related="#subject_id"></x-inputs.chapter>
                                    </td>
                                    <td>
                                        <x-inputs.professor id="professor_id"></x-inputs.professor>
                                    </td>
                                    <td width="30">
                                        <button type="button" class="btn btn-sm btn-success btn-add-chapter-professor"><i class="fa fa-plus"></i></button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            var increment = 0;
            var chapterProfessors = {};

            let addChapterRow = function (chapterProfessor) {
                chapterProfessor = $.extend({
                    subject_id: '',
                    subject_name: '',
                    chapter_id: '',
                    chapter_name: '',
                    professor_id: '',
                    professor_name: ''
                }, chapterProfessor);

                let index = increment;

                let template = `
                                <tr>
                                    <td class="col-subject">${chapterProfessor.subject_name}</td>
                                    <td class="col-chapter">${chapterProfessor.chapter_name}</td>
                                    <td class="col-professor">${chapterProfessor.professor_name}</td>
                                    <td class="py-2">
                                        <input type="hidden" name="chapters[${index}][subject_id]" value="${chapterProfessor.subject_id}">
                                        <input type="hidden" name="chapters[${index}][subject_name]" value="${chapterProfessor.subject_name}">
                                        <input type="hidden" name="chapters[${index}][chapter_id]" value="${chapterProfessor.chapter_id}">
                                        <input type="hidden" name="chapters[${index}][chapter_name]" value="${chapterProfessor.chapter_name}">
                                        <input type="hidden" name="chapters[${index}][professor_id]" value="${chapterProfessor.professor_id}">
                                        <input type="hidden" name="chapters[${index}][professor_name]" value="${chapterProfessor.professor_name}">
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-chapter-professor">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;

                console.log(template);

                var $template = $(template).data('chapter-professor', chapterProfessor);

                $('#tbl-chapter-professors > tbody').append($template);

                chapterProfessors[chapterProfessor.chapter_id+'_'+chapterProfessor.professor_id] = chapterProfessor;

                increment++;
            };

            let exist = function (chapterProfessor) {
                return chapterProfessors[chapterProfessor.chapter_id+'_'+chapterProfessor.professor_id]
            };

            let removeChapterProfessor = function (chapterProfessor) {
                delete chapterProfessors[chapterProfessor.chapter_id+'_'+chapterProfessor.professor_id];
            };

            $('#create').validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 255
                    }
                }
            });

            $('.btn-add-chapter-professor').click(function () {
                var chapterProfessor = {
                    subject_id: $('#subject_id').val(),
                    subject_name: $('#subject_id').find('option:selected').text(),
                    chapter_id: $('#chapter_id').val(),
                    chapter_name: $('#chapter_id').find('option:selected').text(),
                    professor_id: $('#professor_id').val(),
                    professor_name: $('#professor_id').find('option:selected').text()
                };

                if (exist(chapterProfessor)) {
                    alert("Already added");
                    return;
                }

                addChapterRow(chapterProfessor);
            });

            $('#tbl-chapter-professors').on('click', '.btn-delete-chapter-professor', function () {
                var $tr = $(this).closest('tr');
                var chapterProfessor = $tr.data('chapter-professor');
                removeChapterProfessor(chapterProfessor);
                $tr.remove();
            });

            var chapters =  $('#tbl-chapter-professors').data('chapter-professors');
            $.each(chapters, function (index, chapterProfessor) {
                addChapterRow(chapterProfessor);
            });
        });
    </script>
@stop
