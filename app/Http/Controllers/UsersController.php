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
        $message="Username atau password salah.";
        $users=Users::where('username','=',$request->username)->where('password','=',$request->password)->first();
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
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> '']);
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
        $status="failed";
        $message="Tidak dapat memvalidasi data.";
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> '']);
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
                    'debit' => $request->debit,
                    'credit' => $request->credit,
                    'saldo' => $saldo,
                    'from' => $request->from,
                    'dest' => $request->dest,
                    'desc' => $request->desc,
            ]);
            $status='success';
            $message='Data berhasil disimpan';
        }else{
            $status='failed';
            $message='Data tidak berhasil disimpan';
        }
        //return view('layouts.employees.index',['success' => 'Post created successfully.']);
        return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $users]);
    }
    public function users(){
        $users = Users::select('card','name')->get();
        $status='success';
        $message='Data berhasil dimuat';
        return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $users]);
    }
}
