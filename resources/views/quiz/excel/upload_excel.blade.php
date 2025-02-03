@extends('adminlte::page')
@include('quiz.master-layouts.quizcss')
<style type="text/css">
    .form-check-label {

        margin: 0 20px 0 20px;

    }
</style>
@section('title', 'Upload Zip File')

@section('content_header')
    <div class="row">             
        <div class="col-sm-6">
        <h1 class="m-0 text-dark">Upload Zip File</h1>
        </div>
        <div class="col-sm-6">
        </div> 
    </div>
@endsection

@section('content')
@include('quiz.error')
    <section class="content">
        <div class="container-fluid">
            <!-- SELECT2 EXAMPLE -->
            <div class="card card-default">
                <!-- <div class="card-header">
                    <h3 class="card-title">Create Question</h3>
                </div> -->
                <!-- /.card-header -->
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('quiz.uploadQuestionExcel') }}" method="post" enctype="multipart/form-data">
                        @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" class="form-control" name="q_attachment" id="q_attachment" required>
                            </div>
                            <div class="err" style="color: red"></div>
                        </div>
                        <div class="col-6">
                            <input type="submit" value="Upload" class="btn btn-success float-right" onclick="checkfile()">
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
        function checkfile() { 
            var file =  $('#q_attachment').val();
            // alert(file);
            if (file == '') {
                $('.err').html('This field is required');
            }
         }
       
    </script>
@endsection
