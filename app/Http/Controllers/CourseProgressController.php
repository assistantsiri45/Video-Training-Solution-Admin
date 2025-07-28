<?php

namespace App\Http\Controllers;

use App\CourseProgress as AppCourseProgress;
use Illuminate\Http\Request;
use App\Models\CourseProgress;
class CourseProgressController extends Controller
{
// public function save(Request $request)
//     {
//      //  dd("hello");
//         $request->validate([
//             'course_id' => 'nullable',
//             'progress_percent' => 'required|integer|min:0|max:100',
//     'cmi_core_lesson_location' => 'nullable|string',
//     'cmi_core_lesson_status' => 'nullable|string',
//         ]);
//         $userId = auth()->id();
//  // dd($request->all());
//         $progress = AppCourseProgress::updateOrCreate(
//             [
//                 'user_id' => $userId,
//                  'course_id' => $request->course_id
//             ],
//             [
//                 'progress_percent' => $request->progress_percent,
//         'cmi_core_lesson_location' => $request->cmi_core_lesson_location,
//         'cmi_core_lesson_status' => $request->cmi_core_lesson_status,
//             ]
//         );
//         // dd("heloo45");
//     //   dd($progress->all());

//         return response()->json(['status' => 'success', 'message' => 'Progress saved']);
//     }

public function save(Request $request)
{
    $request->validate([
        'course_id' => 'required|integer',
        'session_time' => 'nullable|integer',
        'cmi_core_lesson_location' => 'nullable|string',
        'cmi_core_lesson_status' => 'nullable|string'
    ]);

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
