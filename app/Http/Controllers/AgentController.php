<?php

namespace App\Http\Controllers;

use App\Mail\AgentRegisterMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\Associate;
use App\Models\User;


class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Associate::with('user')->get();

            return DataTables::of($query)
                ->addColumn('action', 'pages.agents.action')
                ->editColumn('phone', function($query) {
                    if($query->phone){
                        return $query->country_code.' '.$query->phone;
                    }

                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'user.name', 'user.name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Mobile','width' => '10%'],
            ['data' => 'commission', 'name' => 'commission', 'title' => 'Commission (%)'],
//            ['data' => 'commission_repeat_purchase_by_agent', 'name' => 'commission_repeat_purchase_by_agent', 'title' => 'Repeat purchase by agent commission (%)'],
//            ['data' => 'commission_repeat_purchase_by_student', 'name' => 'commission_repeat_purchase_by_student', 'title' => 'Repeat purchase by student commission (%)'],
//            ['data' => 'commission_repeat_purchase_by_other_agent', 'name' => 'commission_repeat_purchase_by_other_agent', 'title' => 'Repeat purchase by other agent commission (%)'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false]

        ])->orderBy(0, 'desc');

        return view('pages.agents.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.agents.create');
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
            'mobile' => 'required|unique:users,phone',
            'commission' => 'required|numeric'
//            'repeat_purchase_by_agent_commission' => 'required|numeric',
//            'repeat_purchase_by_student_commission' => 'required|numeric',
//            'repeat_purchase_by_other_agent_commission' => 'required|numeric',
        ]);

        DB::beginTransaction();
        $user = new User();
        $user->name = $request->name;
        $user->country_code = $request->mobile_code;
        $user->phone = $request->mobile;
        $user->email = $request->email;
        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->role = User::ROLE_AGENT;
        $user->save();

        $associate = new Associate();
        $associate->user_id = $user->id;
        $associate->email = $request->email;
        $associate->phone = $request->mobile;
        $associate->country_code = $request->mobile_code;
        $associate->commission = $request->commission ?? null;
        $associate->save();
//        $associate->commission_repeat_purchase_by_agent = $request->repeat_purchase_by_agent_commission ?? null;
//        $associate->commission_repeat_purchase_by_student = $request->repeat_purchase_by_student_commission ?? null;
//        $associate->commission_repeat_purchase_by_other_agent = $request->repeat_purchase_by_other_agent_commission ?? null;


        $user_details['name'] = $user->name;
        $user_details['email'] = $user->email;
        $user_details['phone'] = $user->phone;
        $user_details['password'] = $password;
        try{
            Mail::send(new AgentRegisterMail($user_details));
        }
        catch (\Exception $exception) {
            info($exception->getMessage(), ['exception' => $exception]);
        }


        DB::commit();

        return redirect(route('agents.index'))->with('success', 'Agent successfully created');
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
        /** @var Associate $associate */
        $associate = Associate::findOrFail($id);

        return view('pages.agents.edit', compact('associate'));
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
        $associate = Associate::findOrFail($id);

        $request->validate([
            'name' => 'required|alpha_spaces',
            'email' => 'required|unique:associates,email,' . $id,
            'mobile_code' => 'required',
            'mobile' => 'required|required|unique:associates,phone,' . $id,
            'commission' => 'required|numeric',
//            'repeat_purchase_by_agent_commission' => 'required|numeric',
//            'repeat_purchase_by_student_commission' => 'required|numeric',
//            'repeat_purchase_by_other_agent_commission' => 'required|numeric',
        ]);


        /** @var Associate $associate */
        if($associate->country_code != null){
            $associate->phone = $request->mobile;
        }
        $associate->country_code = $request->mobile_code;
        $associate->commission = $request->commission ?? null;
//        $associate->commission_repeat_purchase_by_agent = $request->repeat_purchase_by_agent_commission ?? null;
//        $associate->commission_repeat_purchase_by_student = $request->repeat_purchase_by_student_commission ?? null;
//        $associate->commission_repeat_purchase_by_other_agent = $request->repeat_purchase_by_other_agent_commission ?? null;
        $associate->save();

        /** @var User $user */
        $user = User::findOrFail($associate->user_id);
        $user->name = $request->name;
        $user->country_code = $request->mobile_code;
        if($user->country_code != null) {
            $user->phone = $request->mobile;
        }
        $user->save();

        return redirect(route('agents.index'))->with('success', 'Agent successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $associate = Associate::findOrFail($id);
        $associate->delete();

        $user = User::findOrFail($associate->user_id);
        $user->delete();

        return response()->json(true, 200);
    }
}
