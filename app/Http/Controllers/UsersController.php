<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
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
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show(Users $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function edit(Users $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Users $users)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy(Users $users)
    {
        //
    }
    function array_push_assoc($array, $key, $value){
        $array[$key] = $value;
        return $array;
     }
    public function login(Request $request){
        $status="failed";
        $message="Email atau password salah.";
        $users=Users::where('email','=',$request->email)->where('password','=',$request->password)->where('status','!=','nonaktif')->first();
        $kosong=array(
            'id' => 1,
            'name' => '',
            'nik' => 43,
            'tel' => 257,
            'card' => 5602242014592959,
            'email' => '',
            'username' => '',
            'status' => '',
            'created_at' => '-000001-11-30T00:00:00.000000Z',
            'updated_at' => '-000001-11-30T00:00:00.000000Z',
            'saldo' => 0,
        );
        if($users){
            $trans=Transactions::where('users_id','=',$users->id)->orderBy('id','desc')->first();
            if($trans){
                $saldo=$trans->saldo;
            }else{
                $saldo=0;
            }
            self::array_push_assoc($users,'saldo',$saldo);
            $status='success';
            $message='Berhasil login';
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $users]);
        }else{
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'nik' => 'required',
            'tel' => 'required',
            'card' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
            'status' => 'required',
        ]);
        $kosong=array(
            'id' => 1,
            'name' => '',
            'nik' => 43,
            'tel' => 257,
            'card' => 5602242014592959,
            'email' => '',
            'username' => '',
            'status' => '',
            'created_at' => '-000001-11-30T00:00:00.000000Z',
            'updated_at' => '-000001-11-30T00:00:00.000000Z',
            'saldo' => 0,
        );
        $status="failed";
        $message="Tidak dapat memvalidasi data.";
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
       
        $users=Users::updateOrCreate([
            'id' => $request->id
           ],[
            'name' => $request->name,
            'nik' => $request->nik,
            'tel' => $request->tel,
            'card' => $request->card,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
            'status' => $request->status,
        ]);
        if($users){
            $verifications=Transactions::updateOrCreate([
                'id' => $request->id
               ],[
                'users_id' => $users->id,
                    'debit' => 0,
                    'credit' => 0,
                    'saldo' => 0,
                    'from' => $users->card,
                    'dest' => $users->card,
                    'desc' => "Pembuatan kartu",
            ]);
            $status='success';
            $message='Data berhasil disimpan';
        }else{
            $status='failed';
            $message='Data tidak berhasil disimpan';
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=>$kosong]);
        }
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $users]);
    }
    public function check_card(Request $request){
        $status="failed";
        $message="Data tidak ditemukan.";
        $users=Users::where('card','=',$request->card)->first();
        $kosong=array(
            'id' => 1,
            'name' => '',
            'nik' => 43,
            'tel' => 257,
            'card' => 5602242014592959,
            'email' => '',
            'username' => '',
            'status' => '',
            'created_at' => '-000001-11-30T00:00:00.000000Z',
            'updated_at' => '-000001-11-30T00:00:00.000000Z',
        );
        if($users){
            $status='success';
            $message='Berhasil mendapatkan data';
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $users]);
        }else{
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
    }
    public function users(Request $request){
        $status="failed";
        $message="Data tidak ditemukan.";
        $users=Users::where('id','=',$request->users_id)->first();
        $kosong=array(
            'id' => 1,
            'name' => '',
            'nik' => 43,
            'tel' => 257,
            'card' => 5602242014592959,
            'email' => '',
            'username' => '',
            'status' => '',
            'created_at' => '-000001-11-30T00:00:00.000000Z',
            'updated_at' => '-000001-11-30T00:00:00.000000Z',
        );
        if($users){
            $status='success';
            $message='Berhasil mendapatkan data';
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $users]);
        }else{
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
    }
    public function edit_users(Request $request){
        $validator = Validator::make($request->all(), [
            'users_id' => 'required',
            'name' => 'required',
            'nik' => 'required',
            'tel' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
        $kosong=array(
            'id' => 1,
            'name' => '',
            'nik' => 43,
            'tel' => 257,
            'card' => 5602242014592959,
            'email' => '',
            'username' => '',
            'status' => '',
            'created_at' => '-000001-11-30T00:00:00.000000Z',
            'updated_at' => '-000001-11-30T00:00:00.000000Z',
            'saldo' => 0,
        );
        $status="failed";
        $message="Tidak dapat memvalidasi data.";
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
       
        $users=Users::updateOrCreate([
            'id' => $request->users_id
           ],[
            'name' => $request->name,
            'nik' => $request->nik,
            'tel' => $request->tel,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
        ]);
        if($users){
            $status='success';
            $message='Data berhasil disimpan';
        }else{
            $status='failed';
            $message='Data tidak berhasil disimpan';
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=>$kosong]);
        }
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $users]);
    }
    public function delete_users(Request $request){
        $validator = Validator::make($request->all(), [
            'users_id' => 'required'
        ]);
        $kosong=array(
            'id' => 1,
            'name' => '',
            'nik' => 43,
            'tel' => 257,
            'card' => 5602242014592959,
            'email' => '',
            'username' => '',
            'status' => '',
            'created_at' => '-000001-11-30T00:00:00.000000Z',
            'updated_at' => '-000001-11-30T00:00:00.000000Z',
        );
        $status="failed";
        $message="Tidak dapat memvalidasi data.";
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
       
        $users=Users::updateOrCreate([
            'id' => $request->users_id
           ],[
            'status' => 'nonaktif',
        ]);
        if($users){
            $status='success';
            $message='Data berhasil disimpan';
        }else{
            $status='failed';
            $message='Data tidak berhasil disimpan';
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=>$kosong]);
        }
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $users]);
    }
}
