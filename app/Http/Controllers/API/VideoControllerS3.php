<?php

namespace App\Http\Controllers\API;


use App\Models\PackageVideo;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class VideoControllerS3 extends Controller
{

    public function files()
    {
        $path = request()->get('path');
        $videos = [];

        if ($path) {
            $videos =  Storage::disk('s3')->files($path);
        }

        return response()->json(['data' => $videos]);
    }

    public function folders()
    {
        $path = request()->get('path');
        $contents = [];
        $s3_base_path = env('AWS_S3_BASE_FOLDER', '');
        $path = !empty($path) ? $path : $s3_base_path;
        $directories = Storage::disk('s3')->directories($path);
        
        if($path == ""){
            $contents[] = [
                'name' => "/",
                'path' => "/",
                'type' => 'folder',
            ];
        }

        foreach ($directories as $directory) {
            $contents[] = [
                'name' => basename($directory),
                'path' => $directory,
                'type' => 'folder',
            ];
        }
        $files = Storage::disk('s3')->files($path);
        foreach ($files as $file) {
            $contents[] = [
                'name' => basename($file),
                'path' => $path.$file,
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

    public function uploadS3(Request $request){
        
        if ($request->hasFile('file')) {
            
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $filesize = $file->getSize();
            $extension = $file->getClientOriginalExtension();
            $extension = strtolower($extension);
            
            //clearning filename
            $filename = pathinfo($filename, PATHINFO_FILENAME);
            $filename = preg_replace('/\s+/', ' ', $filename); // convert extra spaces to single space
            $filename = str_replace(' ', '_', trim($filename)); // converting spaces to underscore
            $filename = $filename . '.' . $extension;   // final filename with extension lowercase

            $uploadPath = $request->input('path') ? $request->input('path') : '' ;
            
            $this->validate($request, [
                'file' => 'required|mimes:mp4', // maximum file size of 10MB
            ]);
                
            // Upload the file to S3 without setting an ACL
            $path = Storage::disk('s3')->putFileAs($uploadPath, $file, $filename);

            // Generate the public URL of the uploaded file
            // $url = Storage::disk('s3')->url($path);
            $url =  env('AWS_CDN') . $path;

            // Return the public URL to the uploaded file
            return response()->json(['success' => 'File uploaded successfully.', 'url' => $url]);
        } else {
            return response()->json(['error' => 'No file uploaded.']);
        }
    
    }

    public function updateDuration(Request $request,$id){

        $this->validate($request,[
            'duration' => 'required'
        ]);

        $data = [
            'duration' => $request->input('duration'),
        ];
        
        Video::where('id', $id)->update($data);

        return response()->json(['success' => 'Video Duration Updated','duration' => $data['duration'], 'videoId' => $id]);
    }

}