<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use App\Mail\ThirdPartyAgentRegisterMail;
use App\Mail\ThirdPartyStudentMail;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use App\Models\Student;
use App\Models\User;
use App\Notifications\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $request->validate([
            'name' => 'required|alpha_spaces',
            'email' => 'required|email:rfc,dns|unique:users',
            'country_code' => 'required',
            'phone' => 'required|unique:users,phone'
        ]);

        DB::beginTransaction();
        $user = new User();
        $user->name = $request->name;
        $user->country_code = $request->country_code;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->role = User::ROLE_STUDENT;
        $user->save();

        $student = new Student();
        $student->user_id = $user->id;
        $student->name = $request->name;
        $student->country_code = $request->country_code;
        $student->phone = $request->phone;
        $student->address = $request->address;
        $student->email = $request->email;
        $student->country_id = $request->country_id;
        $student->state_id = $request->state_id;
        $student->city = $request->city;
        $student->pin = $request->pin;
        $student->course_id = $request->course_id;
        $student->level_id = $request->level_id;
        $student->save();


        $countryname = Country::CountryName($request->country_id)->first();
        $statename=State::StateName($request->state_id)->first();
        $address = new Address();

        $address->user_id = $user->id;
        $address->name = $request->name;
        $address->country_code = $request->country_code;
        $address->phone = $request->phone;
        $address->city = $request->city;
      //  $address->state = $request->state;
        $address->state = $statename->name;
     //   $address->country = $request->country;
        $address->country = $countryname->name;
        $address->pin = $request->pin;
        $address->area = $request->area;
        $address->landmark = $request->landmark;
        $address->address = $request->address;
        $address->address_type = $request->address_type;

        $address->save();

        $user_details['name'] = $user->name;
        $user_details['email'] = $user->email;
        $user_details['phone'] = $user->phone;
        $user_details['password'] = $password;

        try{
            Mail::send(new ThirdPartyStudentMail($user_details));
        }
        catch (\Exception $exception) {
            info($exception->getMessage());
        }

        try {
            $attributes['name'] = $user->name;
            $attributes['email'] = $user->email;
            $attributes['password'] = $password;

            $notification = new UserCreated($attributes);
            Notification::route('sms', $user->phone)->notify($notification);
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }

        DB::commit();


        $path = 'third-party-orders/'.$student->id;
        return redirect(url($path))->with('success', 'Successfully created');
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
        //
    }
}
