<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\UserWallet;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    function Wallet(Request $request){
        return ;
        $wallettx = DB::table('transactions')->where('to','=',$request->wallet)->orWhere('from','=',$request->wallet)->orderBy('block_height','ASC')->first();
        $balance =0;
        $b = DB::table("balances")->where("wallet","=",$request->wallet)->first();
        //dd($b);
        $balance = 0;
        if($b){
            $balance = $b->balnce;
        }
        if($request->page){
            $page = $request->page;
        }else{
            $page=0;
        }
        $paging = DB::table('transactions')->where('to','=',$request->wallet)->orWhere('from','=',$request->wallet)->orderBy("transactions.id","desc")->limit(20)->get();
        //dd($balance);
        $data['txs'] = $paging;
        $data['balance'] = round($balance,9);
        $data['wallettx'] = $wallettx;
        $json = json_encode($data);
        return $json;
        //dd($block);
        //return view('wallet',['txs'=>$wallettxs, "balance"=> $balance, "mined"=>$mined,"wallettx"=>$wallettx ]);
    }
    function Pendingtx(Request $request){
        $pendingtx = file_get_contents("http://127.0.0.1:5000/pendingtx");
        return $pendingtx;
    }
}