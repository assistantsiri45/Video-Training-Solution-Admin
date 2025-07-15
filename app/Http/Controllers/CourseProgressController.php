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
   // dd($request->all());
    $request->validate([
        'course_id' => '',
        'progress_percent' => '',
        'scroll_position' => 'nullable|integer'
    ]);
//dd($request->all());
    $userId = auth()->id();
//dd($userId);
    $progress = AppCourseProgress::updateOrCreate(
        ['user_id' => $userId, 'course_id' => $request->course_id],
        [
            'progress_percent' => $request->progress_percent,
            'scroll_position' => $request->scroll_position ?? 0
        ]
    );
dd($progress->all());
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
