@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📦 Upload SCORM Course</h4>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('scorm.upload') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Course Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter course title" required>
                        </div>

                        <div class="mb-3">
                            <label for="zip_file" class="form-label">SCORM Zip File</label>
                            <input type="file" name="zip_file" id="zip_file" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">⬆️ Upload Course</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
