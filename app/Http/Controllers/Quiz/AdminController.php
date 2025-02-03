<?php

namespace App\Http\Controllers\Quiz;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Exceptions\Handler;
use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Alert;
use Validator;
use File;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        if(count(Role::where(['name' =>'admin'])->get()) == 0)
        {
            Role::create(['name'=>'admin']);
        }
        $datas = Admin::where('id','!=',1)->get();

        return view('backend.admin.index')->with(['datas' => $datas]);
    }

    public function create() {
        return view('backend.admin.create');
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255', 'unique:admins'],
                'password' => ['required', 'string', 'min:8'],
                'status'   => 'required',
                ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $password = Hash::make($request->password);

            $data = new Admin();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = $password;
            $data->status = $request->status;
            $data->save();
            $data->assignRole('admin');
            return $this->redirectToIndex('admin', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function edit($id) {
        $data = Admin::find($id);

        return view('backend.admin.edit')->with(['data' => $data]);
    }

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $password = base64_encode($request->password);

            $data = Admin::find($id);
            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = $password;
            $data->status = $request->status;;
            $data->save();

            return $this->redirectToIndex('admin', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Admin::destroy($id);
        return $this->redirectToIndex('admin', config('constants.message.delete'));
    }

    public function getPassword(){
        $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

}
