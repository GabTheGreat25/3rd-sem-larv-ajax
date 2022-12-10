<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\admin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = admin::join('users','admin.user_id','users.id')->select('admin.*','users.email')->orderBy('admin.admin_id','DESC')->get();
        return response()->json($admin);
    }
    
    public function getAdminAll()
    {
        return view('admin.index');
    }

    public function getRegisterAdmin(){
        return view('admin.register');
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
        $user = new User();
        $user->name = $request->full_name;
        $user->email = $request->email;
        $user->password = Hash::make($request['password']);
        $user->role = 'admin';
        $user->save();
        $lastInsertId = DB::getPdo()->lastInsertId();

        $admin = new admin();
        $admin->users()->associate($lastInsertId);
        $admin->full_name = $request->full_name;
        $admin->age = $request->age;
       
        $files = $request->file('uploads');
        $admin->image_path = 'images/'.$files->getClientOriginalName();
        $admin->save();
        Storage::put('/public/images/'.$files->getClientOriginalName(),file_get_contents($files));

       return response()->json(["success" => "Admin Created Successfully.", "admin" => $admin, "status" => 200]);
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
        $admin = admin::find($id);
        return response()->json($admin);
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
        $admin = admin::find($id);
        $admin->full_name = $request->full_name;
        $admin->age = $request->age;
        $admin->price = $request->price;
        // $admin->user_id = $request->user_id;

        $files = $request->file('uploads');
        $admin->image_path = 'images/'.$files->getClientOriginalName();
        $admin->save();
        Storage::put('/public/images/'.$files->getClientOriginalName(),file_get_contents($files));
        return response()->json(["success" => "Admin Updated Successfully.", "admin" => $admin, "status" => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = admin::findOrFail($id);

        if (File::exists("storage/" . $admin->image_path)) {
            File::delete("storage/" . $admin->image_path);
        }

        $admin->delete();

        $data = array('success' => 'deleted', 'code' => '200');
        return response()->json($data);
    }
}