<?php

namespace App\Http\Controllers;

use App\Models\Verifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Users;

class VerificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\Verifications  $verifications
     * @return \Illuminate\Http\Response
     */
    public function show(Verifications $verifications)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Verifications  $verifications
     * @return \Illuminate\Http\Response
     */
    public function edit(Verifications $verifications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Verifications  $verifications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Verifications $verifications)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Verifications  $verifications
     * @return \Illuminate\Http\Response
     */
    public function destroy(Verifications $verifications)
    {
        //
    }
    public function start_verif(Request $request){
        $validator = Validator::make($request->all(), [
            'users_id' => 'required',
        ]);
        $status="failed";
        $message="Tidak dapat memvalidasi data.";
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> '']);
        }
        $verifications=Users::where('id', $request->users_id)
        ->update([
            'status' => "verif"
         ]);
        if($verifications){
            $status="success";
            $message="Berhasil memvalidasi data.";
        }
        return response()->json(['status'=> $status, 'messages'=>$message, 'data'=>$verifications]);
    }
    public function check_verif(Request $request){
        $validator = Validator::make($request->all(), [
            'users_id' => 'required',
        ]);
        $status="failed";
        $message="Tidak dapat memvalidasi data.";
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message]);
        }
        $hasupload = Verifications::find($request->users_id)->count();
        if($hasupload>0){
            $status="success";
            $message="Sudah pengajuan";
            return response()->json(['status'=> $status, 'messages'=>$message]);
        }
    }
    public function verif(Request $request){
        $validator = Validator::make($request->all(), [
            'users_id' => 'required',
            'desc' => 'required',
            'value' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $kosong=array(
            'users_id' => 1,
            'desc' => "kosong",
            'value' => "kosong",
            'created_at' => '-000001-11-30T00:00:00.000000Z',
            'updated_at' => '-000001-11-30T00:00:00.000000Z', 
        );
        $status="failed";
        $message="Tidak dapat memvalidasi data.";
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
        $path_logo = time().'.logo.'.$request->value->extension();
        // Public Folder
        $request->value->storeAs('images', $path_logo);
        $verifications=Verifications::updateOrCreate([
            'id' => $request->id
           ],[
            'users_id' => $request->users_id,
            'desc' => $request->desc,
            'value' => $path_logo,
        ]);
        if($verifications){
            $status='success';
            $message='Data berhasil disimpan';
            $users=ModelsUsers::find($request->users_id);
            $users->status="check";
            $users->save();
        }else{
            $status='failed';
            $message='Data tidak berhasil disimpan';
            $verifications=array(
                'users_id' => 1,
                'desc' => "kosong",
                'value' => "kosong",
                'created_at' => '-000001-11-30T00:00:00.000000Z',
                'updated_at' => '-000001-11-30T00:00:00.000000Z', 
            );
        }
        return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $verifications]);
    }
}
