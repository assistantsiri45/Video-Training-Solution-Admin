<?php

namespace App\Http\Controllers;

use App\Mail\AgentRegisterMail;
use App\Mail\ThirdPartyAgentRegisterMail;
use App\Models\Associate;
use App\Models\User;
use App\Models\ThirdPartyAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class ThirdPartyAgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = ThirdPartyAgent::with('user')->get();

            return DataTables::of($query)
                ->addColumn('action', 'pages.third_party.action')
                ->editColumn('phone', function($query) {
                    if($query->phone){
                        return $query->country_code.' '.$query->phone;
                    }

                })
                ->editColumn('name', function($query) {
                    if($query->user){
                        return optional($query->user)->name;
                    }
                    return '-';

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name', 'defaultContent' => '-'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email', 'defaultContent' => '-'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Mobile','width' => '10%', 'defaultContent' => '-'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false]

        ])->orderBy(0, 'desc');

        return view('pages.third_party.index', compact('html'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.third_party.create');
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
            'name' => 'required|alpha_spaces',
            'email' => 'required|email:rfc,dns|unique:users',
            'mobile_code' => 'required',
            'mobile' => 'required|unique:users,phone'
        ]);

        DB::beginTransaction();
        $user = new User();
        $user->name = $request->name;
        $user->country_code = $request->mobile_code;
        $user->phone = $request->mobile;
        $user->email = $request->email;
        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->role = User::ROLE_THIRD_PARTY_AGENT;
        $user->save();

        $third_party = new ThirdPartyAgent();
        $third_party->user_id = $user->id;
        $third_party->email = $request->email;
        $third_party->phone = $request->mobile;
        $third_party->country_code = $request->mobile_code;
        $third_party->save();

        $user_details['name'] = $user->name;
        $user_details['email'] = $user->email;
        $user_details['phone'] = $user->phone;
        $user_details['password'] = $password;

        try{
            Mail::send(new ThirdPartyAgentRegisterMail($user_details));
        }
        catch (\Exception $exception) {
            info($exception->getMessage(), ['exception' => $exception]);
        }

        DB::commit();

        return redirect(route('third-party-agents.index'))->with('success', 'Successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ThirdPartyAgent  $thirdPartyAgent
     * @return \Illuminate\Http\Response
     */
    public function show(ThirdPartyAgent $thirdPartyAgent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ThirdPartyAgent  $thirdPartyAgent
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        /** @var ThirdPartyAgent $associate */
        $thirdparty = ThirdPartyAgent::findOrFail($id);

        return view('pages.third_party.edit', compact('thirdparty'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ThirdPartyAgent  $thirdPartyAgent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $thirdparty = ThirdPartyAgent::findOrFail($id);

        $request->validate([
            'name' => 'required|alpha_spaces',
            'email' => 'required|unique:users,email,' . $thirdparty->user_id,
            'mobile_code' => 'required',
            'mobile' => 'required|unique:users,phone,' . $thirdparty->user_id,


        ]);

        $thirdparty = ThirdPartyAgent::findOrFail($id);
        /** @var ThirdPartyAgent $associate */
        if($thirdparty->country_code != null){
            $thirdparty->phone = $request->mobile;
        }
        $thirdparty->country_code = $request->mobile_code;
        $thirdparty->save();

        /** @var User $user */
        $user = User::findOrFail($thirdparty->user_id);
        $user->name = $request->name;
        $user->country_code = $request->mobile_code;
        if($user->country_code != null) {
            $user->phone = $request->mobile;
        }
        $user->save();

        return redirect(route('third-party-agents.index'))->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ThirdPartyAgent  $thirdPartyAgent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $thirdparty = ThirdPartyAgent::findOrFail($id);
        $thirdparty->delete();

        $user = User::findOrFail($thirdparty->user_id);
        $user->delete();

        return response()->json(true, 200);
    }
}
