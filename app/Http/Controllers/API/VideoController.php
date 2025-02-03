<?php

namespace App\Http\Controllers\API;


use App\Models\PackageVideo;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{

    public function files()
    {
        $path = request()->get('path');
        $videos = [];

        if ($path) {
            $videos =  Storage::disk('videos')->files($path);
        }

        return response()->json(['data' => $videos]);
    }

    public function folders()
    {
        $path = request()->get('path');

        $contents = [];

        $directories = Storage::disk('videos')->directories($path);

        foreach ($directories as $directory) {
            $contents[] = [
                'name' => basename($directory),
                'path' => $directory,
                'type' => 'folder',
            ];
        }

        $files = Storage::disk('videos')->files($path);

        foreach ($files as $file) {
            $contents[] = [
                'name' => basename($file),
                'path' => $file,
                'type' => 'file',
            ];
        }

        return response()->json(['data' => $contents]);
    }

    public function group()
    {
        $videoIDs = request('videos');
        $packageID = request('package_id');

        if (! $videoIDs) {
            return null;
        }

        $packageVideoIDs = PackageVideo::where('package_id', $packageID)->pluck('video_id')->toArray();
        $intersectedIDs = array_intersect($packageVideoIDs, $videoIDs);
        $mergedIDs = array_unique(array_merge($intersectedIDs, $videoIDs));
        $implodedIDs = implode(',', $mergedIDs);
        $selectedVideos = Video::whereIn('id', $mergedIDs)->with('module')->orderByRaw(DB::raw("FIELD(id, $implodedIDs)"))->get()->groupBy('module_id');

        $response = '';

        $moduleIndex = 1;

        foreach ($selectedVideos as $videos) {
            $response .=
                '<table class="table table-bordered table-module">
                    <thead>
                        <tr>
                            <th scope="col" colspan="3" class="text-center">' . $videos[0]->module->name . ' | ORDER: <span class="order">' . $moduleIndex . '</span></th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-left">VIDEO</th>
                            <th scope="col" class="text-right">ORDER</th>
                        </tr>
                    </thead>
                    <tbody class="sortable-tbody">';

            foreach ($videos as $videoIndex => $video) {
                $response .=
                        '<tr>
                            <td class="text-left">' . $video->title . '</td>
                            <td class="text-right">
                                <span class="order">' . ($videoIndex + 1) . '</span>
                                <input type="hidden" name="video_id[]" value="' . $video->id .'">
                                <input type="hidden" name="video_order[]" class="video-order" value="' . ($videoIndex + 1) .'">
                                <input type="hidden" name="module_id[]" value="' . $video->module_id .'">
                                <input type="hidden" name="module_order[]" class="module-order" value="' . $moduleIndex . '">
                            </td>
                        </tr>';
            }

            $response .=
                    '</tbody>
                </table>';

            $moduleIndex++;
        }

        return $response;
    }

}
