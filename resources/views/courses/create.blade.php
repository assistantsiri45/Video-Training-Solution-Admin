@extends('adminlte::page')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📘 Create New Course</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Course Title</label>
                    <input type="text" name="title" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Training Link (URL)</label>
                    <input type="url" name="training_link" class="form-control" placeholder="https://example.com" />
                </div>

                <div class="mb-3">
                    <label class="form-label">Access Password</label>
                    <input type="text" name="access_password" class="form-control" placeholder="Enter course password" />
                </div>

                <button class="btn btn-success w-100">Create Course</button>
            </form>
        </div>
    </div>
</div>
@endsection
