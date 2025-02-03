<?php

namespace App\Http\Controllers;

use App\Models\CampaignRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class CampaignRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = CampaignRegistration::query()->with('campaign','temp_campaign_point');
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
                ->editColumn('campaign_id',function($query)
                {
                    if($query->campaign)
                    {
                        return $query->campaign->title;
                    }
                })
                ->editColumn('phone',function($query)
                {
                    if($query->phone)
                    {
                        return $query->country_code.' '.$query->phone;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'campaign_id', 'name' => 'campaign_id', 'title' => 'Campaign'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
        ])->orderBy(0, 'desc');

        return view('pages.campaigns.campaign-registrations.index',compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
