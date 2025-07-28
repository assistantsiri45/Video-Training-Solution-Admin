<?php

namespace App\Http\Controllers;

use App\CourseProgress as AppCourseProgress;
use Illuminate\Http\Request;
use App\Models\CourseProgress;
class CourseProgressController extends Controller
{

public function save(Request $request)
{
    $request->validate([
        'course_id' => '',
        'progress_percent' => '',
        'scroll_position' => 'nullable|integer'
    ]);
//dd($request->all());
    $userId = auth()->id();

    // Get old progress record
    $progress = AppCourseProgress::firstOrNew([
        'user_id' => $userId,
        'course_id' => $request->course_id,
    ]);
    // Add session time to existing one (if any)
    $oldTime = $progress->session_time ?? 0;
    $newTime = $request->session_time ?? 0;
    $totalSessionTime = $oldTime + $newTime;

    // Auto-mark lesson_status as completed if time >= 600 seconds
    $lessonStatus = $request->cmi_core_lesson_status ?? $progress->cmi_core_lesson_status;
    if ($totalSessionTime >= 600) {
        $lessonStatus = 'completed';
    }
    // Update or insert progress
    $progress->session_time = $totalSessionTime;
    $progress->cmi_core_lesson_location = $request->cmi_core_lesson_location ?? $progress->cmi_core_lesson_location;
    $progress->cmi_core_lesson_status = $lessonStatus;
    $progress->save();

    return response()->json(['status' => 'success']);
}

public function get($id)
{
    $userId = auth()->id();

    $progress = AppCourseProgress::where('user_id', $userId)
        ->where('course_id', $id)
        ->first();

    return response()->json([
        'scroll_position' => $progress->scroll_position ?? 0
    ]);
}

}
