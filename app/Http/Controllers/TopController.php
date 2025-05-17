<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class TopController extends Controller
{
    function Index(){
        $data = Storage::disk('public')->get('richlist.json');
        $input = json_decode($data);
        $keys = array_column($input, 'Balance');
        $new = array_multisort($keys, SORT_DESC, $input);

        
        
        
        
        return view('richlist',['wallets'=>$input]);
    }
    


}
