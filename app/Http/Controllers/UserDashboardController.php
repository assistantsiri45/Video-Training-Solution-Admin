<?php

namespace App\Http\Controllers;

use App\CourseProgress;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;
class UserDashboardController extends Controller
{
public function index()
{
    $userId = auth()->id();

    $assignedCourses = \DB::table('user_courses')
        ->join('admin_courses', 'user_courses.course_id', '=', 'admin_courses.id')
        ->leftJoin('scorm_packages', 'admin_courses.scorm_package_id', '=', 'scorm_packages.id') // join SCORM table
        ->where('user_courses.user_id', $userId)
        ->select(
            'admin_courses.id as course_id',
            'admin_courses.title',
            'admin_courses.description',
            // 'admin_courses.access_password',
            'user_courses.expire_date',
            'scorm_packages.folder_name',
            'scorm_packages.launch_file'
        )
        ->get();

    // Create the full iframe URL
    foreach ($assignedCourses as $course) {
        if ($course->folder_name && $course->launch_file) {
            $course->training_link = asset('scorm_packages/' . $course->folder_name . '/' . $course->launch_file);
        } else {
            $course->training_link = null;
        }
    }

    return view('user.dashboard', compact('assignedCourses'));
}


public function resumeupdate(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:admin_courses,id',
        'progress_percent' => 'required|numeric|min:0|max:100',
    ]);

    $progress = CourseProgress::updateOrCreate(
        ['user_id' => auth()->id(), 'course_id' => $request->course_id],
        ['progress_percent' => $request->progress_percent, 'last_accessed_at' => now()]
    );

    return response()->json(['status' => 'success']);
}


}
