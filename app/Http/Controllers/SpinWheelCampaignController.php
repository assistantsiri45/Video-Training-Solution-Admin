<?php

namespace App\Http\Controllers;

use App\Models\CampaignRegistration;
use App\Models\SpinWheelCampaign;
use App\Models\SpinWheelSegment;
use App\Models\TempCampaignPoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Psy\Util\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class SpinWheelCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {

        if (request()->ajax()) {
            $query = SpinWheelCampaign::query();

            return DataTables::of($query)
                ->filter(function($query) {
                    if (!empty(request('filter.search')))
                    {
                        $query->where('title','LIKE', '%'.request('filter.search').'%');
                    }
                })
                ->editColumn('start_date',function($query)
                {
                    $start_date=Carbon::parse($query->start_date)->format('d-m-yy');
                    return $start_date;
                })
                ->editColumn('end_date',function($query)
                {
                    $end_date=Carbon::parse($query->end_date)->format('d-m-yy');
                    return $end_date;
                })
                ->editColumn('point_validity',function($query)
                {
                    $point_validity=Carbon::parse($query->point_validity)->format('d-m-yy');
                    return $point_validity;
                })
                ->editColumn('is_published',function($query)
                {
                    if($query->is_published=='1')
                    {
                        return '✔';
//                      return  '<a class="no-response" data-id=' . $query->id . '><i class="fa fa-check"></i></a>';
                    }
                })
                ->addColumn('action', 'pages.campaigns.spin-wheel-campaigns.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'start_date', 'name' => 'start_date', 'title' => 'Start Date'],
            ['data' => 'end_date', 'name' => 'end_date', 'title' => 'End date'],
            ['data' => 'no_of_chances', 'name' => 'no_of_chances', 'title' => 'No.of Chances'],
            ['data' => 'max_budget', 'name' => 'max_budget', 'title' => 'Budget'],
            ['data' => 'is_published', 'name' => 'is_published', 'title' => 'Published'],
            ['data' => 'point_validity', 'name' => 'point_validity', 'title' => 'Point Validity'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ])->orderBy(0, 'desc');

        return view('pages.campaigns.spin-wheel-campaigns.index',compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.campaigns.spin-wheel-campaigns.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
          //  'no_of_chances' => 'required',
            'max_budget' => 'required',
            'point_validity' => 'required'
        ]);

        $spinWheelCampaign = new SpinWheelCampaign();
        $spinWheelCampaign->title = $request->input('title');
        $spinWheelCampaign->start_date = Carbon::parse($request->input('start_date'));
        $spinWheelCampaign->end_date = Carbon::parse($request->input('end_date'));
//        $spinWheelCampaign->no_of_chances = $request->input('no_of_chances');
        $spinWheelCampaign->max_budget = $request->input('max_budget');
        $spinWheelCampaign->point_validity = Carbon::parse($request->input('point_validity'));
        $spinWheelCampaign->is_published = $request->input('is_published') == 'on';
        $spinWheelCampaign->slug = \Illuminate\Support\Str::slug($request->input('title'));
        $spinWheelCampaign->save();

        $spinWheelSegmentTitles = $request->input('segment_title');
        $spinWheelSegmentPoints = $request->input('segment_point');
        $spinWheelSegmentValue = $request->input('segment_value');
        $spinWheelSegmentValueType = $request->input('segment_value_type');
        $spinWheelSegmentSuccessPercentage = $request->input('success_percentage');
        $spinWheelSegmentColorCodes = ['0047AB', '395EC7', '5A77E3', '7990FF', '97AAFF', 'B4C6FF', '0047AB', '395EC7', '5A77E3', '7990FF', '97AAFF', 'B4C6FF', '0047AB', '395EC7', '5A77E3', '7990FF', '97AAFF', 'B4C6FF', '0047AB', '395EC7', '5A77E3', '7990FF', '97AAFF', 'B4C6FF'];

        foreach ($spinWheelSegmentTitles as $i => $spinWheelSegmentTitle) {
            $spinWheelSegment = new SpinWheelSegment();
            $spinWheelSegment->spin_wheel_campaign_id = $spinWheelCampaign->id;
            $spinWheelSegment->title = $spinWheelSegmentTitle;
            $spinWheelSegment->value = $spinWheelSegmentValue[$i];
            $spinWheelSegment->color_code = $spinWheelSegmentColorCodes[$i];
            $spinWheelSegment->value_type = $spinWheelSegmentValueType[$i];
            $spinWheelSegment->success_percentage = $spinWheelSegmentSuccessPercentage[$i];
            $spinWheelSegment->hits_in_hundred = $spinWheelSegmentSuccessPercentage[$i];
            $spinWheelSegment->save();
        }

        if($request->input('buy_one_get_one') == 'on'){

            $spinWheelSegment = new SpinWheelSegment();
            $spinWheelSegment->spin_wheel_campaign_id = $spinWheelCampaign->id;
            $spinWheelSegment->title = $request->input('buy_one_get_one_title');
            $spinWheelSegment->color_code = '395EC7';
            $spinWheelSegment->value_type = 3;
            $spinWheelSegment->success_percentage = $request->input('buy_one_get_success_percentage');
            $spinWheelSegment->hits_in_hundred = $request->input('buy_one_get_success_percentage');
            $spinWheelSegment->save();

        }

        if($request->input('better_luck_next_time') == 'on'){

            $spinWheelSegment = new SpinWheelSegment();
            $spinWheelSegment->spin_wheel_campaign_id = $spinWheelCampaign->id;
            $spinWheelSegment->title = $request->input('better_luck_next_time_title');
            $spinWheelSegment->color_code = '5A77E3';
            $spinWheelSegment->value_type = 4;
            $spinWheelSegment->success_percentage = $request->input('better_luck_next_time_success_percentage');
            $spinWheelSegment->hits_in_hundred = $request->input('better_luck_next_time_success_percentage');
            $spinWheelSegment->save();

        }

        if($request->input('one_chapter_free') == 'on'){

            $spinWheelSegment = new SpinWheelSegment();
            $spinWheelSegment->spin_wheel_campaign_id = $spinWheelCampaign->id;
            $spinWheelSegment->title = $request->input('one_chapter_free_title');
            $spinWheelSegment->color_code = '5A77E3';
            $spinWheelSegment->value_type = 5;
            $spinWheelSegment->success_percentage = $request->input('one_chapter_free_success_percentage');
            info($spinWheelSegment->success_percentage);
            $spinWheelSegment->hits_in_hundred = $request->input('one_chapter_free_success_percentage');
            $spinWheelSegment->save();

        }
        if($request->input('three_chapter_free') == 'on'){

            $spinWheelSegment = new SpinWheelSegment();
            $spinWheelSegment->spin_wheel_campaign_id = $spinWheelCampaign->id;
            $spinWheelSegment->title = $request->input('three_chapter_free_title');
            $spinWheelSegment->color_code = '5A77E3';
            $spinWheelSegment->value_type = 6;
            $spinWheelSegment->success_percentage = $request->input('three_chapter_free_success_percentage');
            info($spinWheelSegment->success_percentage);
            $spinWheelSegment->hits_in_hundred = $request->input('three_chapter_free_success_percentage');
            $spinWheelSegment->save();

        }

        return redirect(route('spin-wheel-campaigns.index'))->with('success', 'Spin Wheel Campaign created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Builder $builder,$id)
    {
        $query = CampaignRegistration::query()->with('campaign','temp_campaign_point')->where('campaign_id','=',$id);
        $totalCount=CampaignRegistration::query()->with('campaign','temp_campaign_point')->where('campaign_id','=',$id)->count();
        $totalSpin = TempCampaignPoint::where('campaign_id',$id )->count();
        info($totalSpin);
        $totalRedeem=CampaignRegistration::query()->with('campaign','temp_campaign_point')
            ->whereHas('temp_campaign_point',function ($q)
                                        {
                                            $q->where('is_used','=',1);
                                        })
                                        ->where('campaign_id','=',$id)
                                        ->count();
        if (request()->ajax()) {

            return DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            $query
                                ->where('name','like', "%" . request('filter.search') . "%")
                                ->orwhere('phone','like', "%" . request('filter.search') . "%")
                                ->orWhereHas('campaign',function ($query){
                                    $query->where('title','like', "%" . request('filter.search') . "%");
                                });
                        });
                    }
                })
                ->editColumn('created_at',function($query)
                {
                    if($query->created_at)
                    {
                        $created_at=Carbon::parse($query->created_at)->format('d-m-yy');
                        return $created_at;
                    }
                })
                ->editColumn('phone',function($query)
                {
                    if($query->phone)
                    {
                        return $query->country_code.' '.$query->phone;
                    }
                })
                ->addColumn('rewards',function ($query)
                {
                   if($query->temp_campaign_point)
                   {
                       if($query->temp_campaign_point->value_type==1)
                       {
                           return 'Rs. '.$query->temp_campaign_point->value;
                       }
                       if($query->temp_campaign_point->value_type==2)
                       {
                           return $query->temp_campaign_point->value.'%';
                       }
                   }

                })
                ->addColumn('used',function ($query)
                {
                    if($query->temp_campaign_point)
                    {
                        if($query->temp_campaign_point->is_used==1)
                        {
                            return '✔';
                        }
                    }

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'rewards', 'name' => 'rewards', 'title' => 'Rewards'],
            ['data' => 'used', 'name' => 'used', 'title' => 'Used'],

        ])->orderBy(0, 'desc');

        $data = CampaignRegistration::query()->with('campaign','temp_campaign_point')->where('campaign_id','=',$id)->first();
        $campaign=SpinWheelCampaign::query()->where('id',$id)->first();
        return view('pages.campaigns.spin-wheel-campaigns.show',compact('html'),['data' => $data,'totalCount' => $totalCount,'totalRedeem' => $totalRedeem,'totalSpin' => $totalSpin,'campaign' => $campaign]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $spinWheelCampaign = SpinWheelCampaign::query()->with('spinWheelSegment')->find($id);
        $campaignSegments = SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
            ->whereNotIn('value_type', [3,4,5,6])->get();
        $buyoneGetone = SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
            ->where('value_type', 3)->first();
        $blnt = SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
            ->where('value_type', 4)->first();
        $one_chapter = SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
            ->where('value_type', 5)->first();
        $three_chapter = SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
            ->where('value_type', 6)->first();
        return view('pages.campaigns.spin-wheel-campaigns.edit', compact('spinWheelCampaign','campaignSegments',
            'buyoneGetone', 'blnt','one_chapter','three_chapter' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'max_budget' => 'required',
            'point_validity' => 'required'
        ]);

        $colorCodes = ['0047AB', '395EC7', '5A77E3', '7990FF', '97AAFF', 'B4C6FF', '0047AB', '395EC7', '5A77E3', '7990FF', '97AAFF', 'B4C6FF', '0047AB', '395EC7', '5A77E3', '7990FF', '97AAFF', 'B4C6FF', '0047AB', '395EC7', '5A77E3', '7990FF', '97AAFF', 'B4C6FF'];

        $spinWheelCampaign = SpinWheelCampaign::query()->with('spinWheelSegment')->find($id);



        $spinWheelCampaign->title = $request->input('title');
        $spinWheelCampaign->start_date = Carbon::parse($request->input('start_date'));
        $spinWheelCampaign->end_date = Carbon::parse($request->input('end_date'));
//        $spinWheelCampaign->no_of_chances = $request->input('no_of_chances');
        $spinWheelCampaign->max_budget = $request->input('max_budget');
        $spinWheelCampaign->point_validity = Carbon::parse($request->input('point_validity'));
        $spinWheelCampaign->is_published = $request->input('is_published') == 'on';
        $spinWheelCampaign->save();





        if($request->input('buy_one_get_one') == 'on'){
            SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
                ->where('value_type', 3)->delete();
            $buyoneGetone = new SpinWheelSegment();
            $buyoneGetone->title = $request->input('buy_one_get_one_title');
            $buyoneGetone->success_percentage = $request->input('buy_one_get_success_percentage');
            $buyoneGetone->hits_in_hundred = $request->input('buy_one_get_success_percentage');
            $buyoneGetone->color_code =$colorCodes[array_rand($colorCodes)];
            $buyoneGetone->spin_wheel_campaign_id = $id;
            $buyoneGetone->value_type = 3;
            $buyoneGetone->save();
        }else{

            $buyoneGetone = SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
                ->where('value_type', 3)->delete();
        }

        if($request->input('better_luck_next_time') == 'on'){
            SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
                ->where('value_type', 4)->delete();
            $blnt = new SpinWheelSegment();
            $blnt->title = $request->input('better_luck_next_time_title');
            $blnt->success_percentage = $request->input('better_luck_next_time_success_percentage');
            $blnt->hits_in_hundred = $request->input('better_luck_next_time_success_percentage');
            $blnt->color_code = $colorCodes[array_rand($colorCodes)];
            $blnt->spin_wheel_campaign_id = $id;
            $blnt->value_type = 4;
            $blnt->save();
        }else{

            $blnt = SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
                ->where('value_type', 4)->delete();
        }
        if($request->input('one_chapter_free') == 'on'){
            SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
                ->where('value_type', 5)->delete();
            $one_chapter = new SpinWheelSegment();
            $one_chapter->title = $request->input('one_chapter_free_title');
            $one_chapter->success_percentage = $request->input('one_chapter_free_success_percentage');
            $one_chapter->hits_in_hundred = $request->input('one_chapter_free_success_percentage');
            $one_chapter->color_code = $colorCodes[array_rand($colorCodes)];
            $one_chapter->spin_wheel_campaign_id = $id;
            $one_chapter->value_type = 5;
            $one_chapter->save();
        }
        else
        {
            $one_chapter = SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
                ->where('value_type', 5)->delete();
        }
        if($request->input('three_chapter_free') == 'on'){
            SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
                ->where('value_type', 6)->delete();
            $three_chapter = new SpinWheelSegment();
            $three_chapter->title = $request->input('three_chapter_free_title');
            $three_chapter->success_percentage = $request->input('three_chapter_free_success_percentage');
            $three_chapter->hits_in_hundred = $request->input('three_chapter_free_success_percentage');
            $three_chapter->color_code = $colorCodes[array_rand($colorCodes)];
            $three_chapter->spin_wheel_campaign_id = $id;
            $three_chapter->value_type = 6;
            $three_chapter->save();
        }
        else
        {
            $three_chapter = SpinWheelSegment::where('spin_wheel_campaign_id' , $id)
                ->where('value_type', 6)->delete();
        }


        if (count($spinWheelCampaign->spinWheelSegment) > 0) {
            foreach ($spinWheelCampaign->spinWheelSegment as $spinWheelSegment) {
                $spinWheelSegment->delete();
            }
        }

        $spinWheelSegmentTitles = $request->input('segment_title');
        $spinWheelSegmentValue = $request->input('segment_value');
        $spinWheelSegmentValueType = $request->input('segment_value_type');
        $spinWheelSegmentSuccessPercentage = $request->input('success_percentage');
        $spinWheelSegmentColorCodes = $colorCodes;

        foreach ($spinWheelSegmentTitles as $i => $spinWheelSegmentTitle) {
            $spinWheelSegment = new SpinWheelSegment();
            $spinWheelSegment->spin_wheel_campaign_id = $spinWheelCampaign->id;
            $spinWheelSegment->title = $spinWheelSegmentTitle;
            $spinWheelSegment->value = $spinWheelSegmentValue[$i];
            $spinWheelSegment->color_code = $spinWheelSegmentColorCodes[$i];
            $spinWheelSegment->value_type = $spinWheelSegmentValueType[$i];
            $spinWheelSegment->success_percentage = $spinWheelSegmentSuccessPercentage[$i];
            $spinWheelSegment->hits_in_hundred = $spinWheelSegmentSuccessPercentage[$i];
            $spinWheelSegment->save();
        }

        return redirect(route('spin-wheel-campaigns.index'))->with('success', 'Spin Wheel Campaign updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campaign = SpinWheelCampaign::findOrFail($id);

        $campaign->delete();

        return response()->json(true, 200);
    }
}
