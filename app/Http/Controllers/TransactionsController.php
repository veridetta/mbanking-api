<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Client\Request as ClientRequest;
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
        $kosong=array('users_id' => 0,
                'debit' => 0,
                'credit' => 0,
                'saldo' => 0,
                'from' => 0,
                'dest' => 0,
                'desc' => 0,
                'created_at' => '-000001-11-30T00:00:00.000000Z',
                'updated_at' => '-000001-11-30T00:00:00.000000Z', );
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
        $last=Transactions::where('users_id','=',$request->users_id)->orderBy('id','desc')->first();
        if($last){
            $sal=$last->saldo;
        }else{
            $sal=0;
        }
        $saldo = $sal+$request->debit-$request->credit;
        if($request->debit<1){
                $users=Users::where('card','=',$request->from)->first();
                $last2=Transactions::where('users_id','=',$users->id)->orderBy('id','desc')->first();
                if($last){
                    $sal2=$last2->saldo;
                }else{
                    $sal2=0;
                }
                if($request->credit < $sal2){
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
                    $status='success';
                     $message='Data berhasil disimpan';
                    $saldo2 = $sal2-$request->credit;
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
                    $message='Saldo tidak mencukupi';
                    return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
                }
        }else{
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
            $status='success';
                     $message='Data berhasil disimpan';
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $verifications]);
        }
        $status='failed';
                     $message='Data tidak berhasil disimpan';
        return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
    }
    function check_saldo(Request $request){
        $validator = Validator::make($request->all(), [
            'users_id' => 'required',
        ]);
        $status="failed";
        $message="Tidak dapat memvalidasi data (Validasi).";
        $kosong=array('users_id' => 0,
                'debit' => 0,
                'credit' => 0,
                'saldo' => 0,
                'from' => 0,
                'dest' => 0,
                'desc' => 0,
                'created_at' => '-000001-11-30T00:00:00.000000Z',
                'updated_at' => '-000001-11-30T00:00:00.000000Z', );
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
        $last=Transactions::where('users_id','=',$request->users_id)->orderBy('id','desc')->first();
            $status="success";
            $message="Berhasil memuat saldo.";
        if($last){
            $sal=$last->saldo;
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $last]);
        }else{
            $sal=0;
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
    }
    function mutasi(Request $request){
        $validator = Validator::make($request->all(), [
            'users_id' => 'required',
            'type' => 'required',
        ]);
        $status="failed";
        $message="Tidak dapat memvalidasi data (Validasi).";
        $kosong=array(
                'users_id' => 0,
                'debit' => 0,
                'credit' => 0,
                'saldo' => 0,
                'from' => 0,
                'dest' => 0,
                'desc' => "Tidak ada data",
                'created_at' => '-000001-11-30T00:00:00.000000Z',
                'updated_at' => '-000001-11-30T00:00:00.000000Z', );
        if ($validator->fails()) {
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
        $now = Carbon::now()->format("m");
        if($request->type=='default'){
            $last=Transactions::where('users_id','=',$request->users_id)->whereMonth('created_at',$now)->get();
        }else{
            $from = Carbon::parse($request->from)->format("m");
            $to = Carbon::parse($request->to)->format("m");
            $last=Transactions::where('users_id','=',$request->users_id)->whereBetween('created_at',$from,$to)->get();
        }
            $status="success";
            $message="Berhasil memuat mutasi.";
        if($last){
            foreach($last as &$lasts){
                $tanggal = Carbon::parse($lasts->created_at)->format("d/m");
                $lasts->saldo = $tanggal;
                
            }
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $last]);
        }else{
            $status="failed";
            $message="Belum ada data";
            return response()->json(['status'=> $status, 'messages'=>$message, 'data'=> $kosong]);
        }
    }
}
