<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
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
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function show(Transactions $transactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function edit(Transactions $transactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transactions $transactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transactions $transactions)
    {
        //
    }
    public function transaction(Request $request){
        $validator = Validator::make($request->all(), [
            'users_id' => 'required',
            'debit' => 'required',
            'credit' => 'required',
            'saldo' => 'required',
            'from' => 'required',
            'dest' => 'required',
            'desc' => 'required',
        ]);
        $status="failed";
        $message="Tidak dapat memvalidasi data (Validasi).";
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> '']);
        }
        $last=Transactions::where('users_id','=',$request->users_id)->orderBy('id','desc')->first();
        if($last){
            $sal=$last->saldo;
        }else{
            $sal=0;
        }
        $saldo = $sal+$request->debit-$request->credit;
        $verifications=Transactions::updateOrCreate([
            'id' => $request->id
           ],[
            'users_id' => $request->users_id,
                'debit' => $request->debit,
                'credit' => $request->credit,
                'saldo' => $saldo,
                'from' => $request->from,
                'dest' => $request->dest,
                'desc' => $request->desc,
        ]);
        if($request->debit<1){
            if($verifications){
                $status='success';
                $message='Data berhasil disimpan';
                $users=Users::where('card','=',$request->dest)->first();
                $last2=Transactions::where('users_id','=',$users->id)->orderBy('id','desc')->first();
                if($last){
                    $sal2=$last2->saldo;
                }else{
                    $sal2=0;
                }
                $saldo2 = $sal2+$request->credit;
                $verifications=Transactions::create([
                    'users_id' => $users->id,
                    'debit' => $request->credit,
                    'credit' => $request->debit,
                    'saldo' => $saldo2,
                    'from' => $request->from,
                    'dest' => $request->dest,
                    'desc' => $request->desc,
                ]);
                return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $verifications]);
            }else{
                $status='failed';
                $message='Data tidak berhasil disimpan (Input)';
            }
        }
        return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $verifications]);
    }
}
