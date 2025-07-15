@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📚 Your Assigned Courses</h5>
            <i class="fas fa-book-reader fs-5"></i>
        </div>

        <div class="card-body">
            @if($assignedCourses->count())
                <div class="row">
                    @foreach($assignedCourses as $course)
                        <div class="col-md-12 mb-4">
                            <div class="card border border-1">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">
                                        {{ $course->title }}
                                    </h5>

                                    <p class="card-text">{{ $course->description }}</p>

                                    @if($course->access_password)
                                        <p>🔒 <strong>Password:</strong> {{ $course->access_password }}</p>
                                    @endif

                                    <span class="badge bg-success">Expire on: {{ \Carbon\Carbon::parse($course->expire_date)->format('d M Y') }}</span>

                                    @if($course->training_link)
                                        <div class="mt-3">
                                            <iframe src="{{ $course->training_link }}" width="100%" height="500px" frameborder="0"></iframe>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-1"></i> No courses assigned yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
