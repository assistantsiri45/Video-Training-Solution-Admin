<?php

namespace App\Http\Controllers;

use App\Mail\ProfessorRegisterMail;
use App\Notifications\JobAppliedNotification;
use App\Services\BotrAPI;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Str;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return Response
     * @throws Exception
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Professor::query();

            return DataTables::of($query)
                ->addColumn('action', 'pages.professors.action')
                ->editColumn('mobile', function($query) {
                    if($query->mobile){
                        return $query->country_code.' '.$query->mobile;
                    }

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name', 'width' => '30%'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email', 'width' => '30%'],
            ['data' => 'mobile', 'name' => 'mobile', 'title' => 'Mobile', 'width' => '30%'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ]);

        return view('pages.professors.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('pages.professors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|alpha_spaces',
            'email' => 'required|email:rfc,dns|unique:users',
            'password_option' => 'required',
            'mobile' => 'required|numeric',
            'image' => 'required',
            'file' => 'mimes:jpeg,jpg,png,gif|max:10000',
//            'experience' => 'required|regex:/^([^<>]*)$/',
            'introduction' => 'required|regex:/^([^<>]*)$/',
            'revenue' => 'numeric|nullable',
            'description' => 'required',
            'career_start_at' => 'required'
        ])->validate();


        $jwplatform = new BotrAPI('7kHOkkQa', 'McDMAuOcJtkr7k6U172rSnjI');

        try {
        DB::beginTransaction();

            if($request->input('video_type') == Professor::MANUAL_UPLOAD) {

                $video_file = $request->video_file;

                $params = [
                    'title' => $request->input('name'),
                    'description' => $request->input('introduction'),
                    'tags' => $request->input('name'),
                ];
                $params = collect($params);
                $params = $params->map(function ($item) {
                    return html_entity_decode($item);
                });
                $params = $params->toArray();

                if ($request->video_file) {
                    $params['download_url'] = config('filesystems.disks.videos.url') . $request->video_file;
                    $params['download_url'] = str_replace(' ', '%20', $params['download_url']);
                }

                $response = $jwplatform->call("/videos/create", $params);
                if ($response['status'] != 'ok') {
                    return redirect()->back()->withError("Video upload error");
                }
            }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if($request->input('password_option')==Professor::MANUAL){
            $password = $request->password;
        }
        else{
            $password = Str::random(8);
        }
        $user->password = Hash::make($password);
        $user->phone = $request->input('mobile');
        $user->role = User::ROLE_PROFESSOR;

        $user->save();

        $professor = new Professor();

        $professor->user_id = $user->id;
        $professor->name = $request->input('name');
        $professor->title = $request->input('title');
        $professor->email = $request->input('email');
        $professor->country_code = $request->input('mobile_code');
        $professor->mobile = $request->input('mobile');
        $professor->description = $request->input('description');
        $professor->password_option = $request->input('password_option');
        $professor->video_type = $request->input('video_type');
        if($request->input('video_type')==Professor::MANUAL_UPLOAD){
            $professor->media_id = $response['video']['key'];
            $professor->video = $params['download_url'];
        }
        else{
            $professor->video = $request->input('video_url');
        }
//        $professor->experience = $request->input('experience');
        $professor->career_start_at = $request->input('career_start_at');
        $professor->professor_revenue = $request->input('revenue');
        $professor->introduction = $request->input('introduction');
        $professor->is_published = $request->input('is_published') == 'on';
        if($request->image){
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.png';
            Storage::disk('public')->put("professors/images/$image_name", $data);
            $professor->image = $image_name;
        }

        $professor->alt = $request->input('alt');
        $professor->save();
         $user_details['name'] = $user->name;
         $user_details['email'] = $user->email;
         $user_details['password'] = $password;
         $user_details['phone'] = $user->phone;
         Mail::send(new ProfessorRegisterMail($user_details));

        DB::commit();
        } catch (\Exception $e) {
            info($e->getMessage());
            return redirect()->back()->withInput()->withError($e->getMessage());
        }
        return redirect(route('professors.index'))->with('success', 'Professor successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        $professor = Professor::findOrFail($id);

        return view('pages.professors.edit', compact('professor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|alpha_spaces',
            'email' =>  'required|email|unique:users,email,'.Professor::find($id)->user_id,
            'mobile' => 'required',
            'file' => 'mimes:jpeg,png,jpg,gif|max:10000',
//            'experience' => 'required|regex:/^([^<>]*)$/',
            'introduction' => 'required|regex:/^([^<>]*)$/',
            'revenue' => 'numeric|nullable',
            'description' => 'required',
            'title' => 'required',
            'career_start_at' => 'required',
        ])->validate();

        if($request->input('video_type')==Professor::MANUAL_UPLOAD) {
            $jwplatform = new BotrAPI('7kHOkkQa', 'McDMAuOcJtkr7k6U172rSnjI');
            $video_file = $request->video_file;

            $params = [
                'title' => $request->input('name'),
                'description' => $request->input('introduction'),
                'tags' => $request->input('name'),
            ];
            $params = collect($params);
            $params = $params->map(function ($item) {
                return html_entity_decode($item);
            });
            $params = $params->toArray();

            if ($request->video_file) {
                $params['download_url'] = config('filesystems.disks.videos.url') . $request->video_file;
                $params['download_url'] = str_replace(' ', '%20', $params['download_url']);
            }
            $response = $jwplatform->call("/videos/create", $params);
            if ($response['status'] != 'ok') {
                return redirect()->back()->withError("Video upload error");
            }


            try {
                DB::beginTransaction();
                $professor = Professor::findOrFail($id);
                $professor->name = $request->input('name');
                $professor->title = $request->input('title');
                $professor->email = $request->input('email');
                $professor->country_code = $request->input('mobile_code');
                $professor->mobile = $request->input('mobile');
                $professor->description = $request->input('description');
                $professor->password_option = $request->input('password_option');
                $professor->video_type = $request->input('video_type');
                if(array_key_exists('video', $response)){
                    $professor->media_id = $response['video']['key'];
                    $professor->video = $params['download_url'];
                }
//                $professor->experience = $request->input('experience');
                $professor->career_start_at = $request->input('career_start_at');
                $professor->professor_revenue = $request->input('revenue');
                $professor->introduction = $request->input('introduction');
                $professor->is_published = $request->input('is_published') == 'on';
                if ($request->image) {
                    $data = $request->image;
                    list($type, $data) = explode(';', $data);
                    list(, $data) = explode(',', $data);
                    $data = base64_decode($data);
                    $image_name = time() . '.png';
                    Storage::disk('public')->put("professors/images/$image_name", $data);
                    $professor->image = $image_name;
                }

                $professor->alt = $request->input('alt');
                $professor->save();


                $update_user = User::find($professor->user_id);
                if ($request->input('password_option') == Professor::MANUAL) {
                    $update_user->password = Hash::make($request->password);
                } else {
                    $update_user->password = Hash::make(Str::random(8));
                }
                $update_user->name = $request->input('name');
                $update_user->email = $request->input('email');
                $update_user->phone = $request->input('mobile');
                $update_user->save();


                DB::commit();
            } catch (\Exception $e) {
                info($e->getMessage());
                return redirect()->back()->withInput()->withError($e->getMessage());
            }
        }
        else{
            $professor = Professor::findOrFail($id);
            $professor->name = $request->input('name');
            $professor->title = $request->input('title');
            $professor->email = $request->input('email');
            $professor->country_code = $request->input('mobile_code');
            $professor->mobile = $request->input('mobile');
            $professor->password_option = $request->input('password_option');
            $professor->video_type = $request->input('video_type');
            $professor->description = $request->input('description');
            $professor->media_id = null;
            $professor->video = $request->input('video_url');
//            $professor->experience = $request->input('experience');
            $professor->professor_revenue = $request->input('revenue');
            $professor->introduction = $request->input('introduction');
            $professor->is_published = $request->input('is_published') == 'on';
            if ($request->image) {
                $data = $request->image;
                list($type, $data) = explode(';', $data);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);
                $image_name = time() . '.png';
                Storage::disk('public')->put("professors/images/$image_name", $data);
                $professor->image = $image_name;
            }
            $professor->alt = $request->input('alt');
            $professor->save();
            
            $update_user = User::find($professor->user_id);
                if ($request->input('password_option') == Professor::MANUAL) {
                    $update_user->password = Hash::make($request->password);
                } else {
                    $update_user->password = Hash::make(Str::random(8));
                }
                $update_user->name = $request->input('name');
                $update_user->email = $request->input('email');
                $update_user->phone = $request->input('mobile');
                $update_user->save();

        }

        return redirect()->back()->with('success', 'Professor  details successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */

    public function publishProfessorVideo(Request  $request){

        $professor = Professor::findOrFail($request->id);

        $jwPlatform = new BotrAPI('7kHOkkQa', 'McDMAuOcJtkr7k6U172rSnjI');

        $fullVideo = null;
        $demoVideo = null;

        if ($professor->media_id) {
            $fullVideo = $jwPlatform->call('/videos/show', ['video_key' => $professor->media_id]);
        }

        if ($fullVideo['video']['status'] == 'failed') {
            return response()->json(['message' => 'An error occurred while uploading.Please upload the video again.', 'status' => 503], 200);

        }
        elseif ($fullVideo['video']['status'] == 'processing') {
            return response()->json(['message' => 'Video is not published by JWPlayer. Please try again later.', 'status' => 503], 200);

        }else if($fullVideo['video']['status'] == 'ready') {
            $professor->publish_status = 1;
            $professor->update();
            return response()->json(['message' => 'Professor video published successfully.', 'status' => 200], 200);

        }
    }

    public function destroy($id)
    {
        $professor = Professor::findOrFail($id);

        $professor->delete();

        $user = User::findOrFail($professor->user_id);

        $user->delete();

        return response()->json(true, 200);
    }

    public function updateCareer()
    {
        $professors = Professor::get();

        foreach ($professors as $professor) {
            try {
                $careerStartAt = Carbon::parse($professor->created_at)->subYear($professor->experience);
            } catch (\Exception $e) {
                logger()->error($e->getMessage(), ['exception' => $e]);
            }
            if (!$professor->experience) {
                $careerStartAt = $professor->created_at;
            }
            $professor->career_start_at = $careerStartAt;
            $professor->save();
        }
        return redirect()->route('home');
    }
}
