<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\UserWallet;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;
//include schema to app to controller


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ExplorerController extends Controller
{
    //
    private $rpc = "http://127.0.0.1:5000";

    function Index(){
        //$msg = $this->GetTransactions();
        DB::connection()->enableQueryLog();
        $transactions = $this->GetLatestTransactions();
        $blocks = $this->GetLatestBlocks();
        $pendingtx = @$this->get_contents("http://127.0.0.1:5000/pendingtx");
        $pendingdecode = json_decode($pendingtx);
        $infobc = $this->GetMinebaleAndCirculatingSupply();
        $queries = DB::getQueryLog();
        //dd($queries);
        return view("home",['transactions'=>$transactions,'blocks'=>$blocks, 'pendingtx'=>$pendingtx,'infobc'=>$infobc]);
    }

    function GetAPITotalSupply(){
        $info['result']= 1000000000-$this->CheckBalance("METCHAIN_Blockchain")-$this->CheckBalance("metchain:000000000000000000000000000000000000000000000000000000000000DEAD");
        return json_encode($info['result']);
        //$info['remainingsupply'] = 1000000000 - $this->CheckBalance("metchain:000000000000000000000000000000000000000000000000000000000000DEAD");
    }

    function GetAPICirculatingSupply(){
        $info['result']=1000000000 -$this->CheckBalance("METCHAIN_Blockchain")-$this->CheckBalance("metchain:000000000000000000000000000000000000000000000000000000000000DEAD")-$this->NFTStakeTotal();
        return json_encode($info['result']);
        
    }

    function TPS(){
        $blocks = DB::table('blocks')->latest('id')->first();
        $block_id = $blocks->block_height;
        $url = $this->rpc."/block/from-to?from=".($block_id-199)."&limit=199";
        
        $info = file_get_contents($url);
        $jsonBlock = json_decode($info);
        $counter = 0;
        foreach($jsonBlock->Blocks as $block){
            
            $counter = count($block->Txs)+$counter;
        }
        
       
    
        
        return $counter;
        
    }
    function PriceCheck(){
        $xeggexapi = file_get_contents("https://api.xeggex.com/api/v2/asset/getbyid/65dea6efb9f343ee794a9d68");
        $jsonInfo = json_decode($xeggexapi);
        return $jsonInfo->usdValue;
       
        
    }
    function GetMinebaleAndCirculatingSupply(){
        $info = [];

        $info['balance'] = $this->CheckBalance("METCHAIN_Blockchain");
        $info['burnt'] = $this->CheckBalance("metchain:000000000000000000000000000000000000000000000000000000000000DEAD");
        $info['remainingsupply'] = 1000000000 - $info['balance'];
        $info['balanceburntSupply'] = 1000000000 -$info['balance']-$info['burnt'];
        
        $info['price']=$this->PriceCheck();
        $info['tps'] = $this->TPS();
        $info['totalStaked'] = $this->NFTStakeTotal();
        $info['balanceburnt'] = 1000000000 -$info['balance']-$info['burnt']-$this->NFTStakeTotal();
        return $info;
    }

    function GetMinebaleAndCirculatingSupplyInfo(){
        return json_encode($this->GetMinebaleAndCirculatingSupply());
    }

    function NFTStakeTotal(){
        $i=1;
        $total = 0;
        while($i<=500){
            $stake = file_get_contents($this->rpc."/nftstake?nft=".$i);
            $stakeinfo = json_decode($stake);
            if(isset($stakeinfo->NFTid)){
                
                $total= $total+$stakeinfo->StakeAmount;
            }
            $i++;
        }   
            
        return $total;

    }

    function get_contents($url) {
        $headers = get_headers($url);
        $status = substr($headers[0], 9, 3);
        if ($status == '200') {
            return file_get_contents($url);
        }
        return false;
    }
    function VerifiedWallets(){
        $wallets = DB::table('verifiedwallets')->latest('id')->limit(250)->get();
        //dd($wallets);
        return view("vwallets",["wallets"=>$wallets]);
    }
    
    function GetNFTS(){
        $nfts = file_get_contents($this->rpc."/nft");
        $jsonInfo = json_decode($nfts);
        foreach ($jsonInfo as $key=>$nft){
            
            $stake = file_get_contents($this->rpc."/nftstake?nft=".$nft->Inpoints->Value);
            $stakeinfo = json_decode($stake);
            if(isset($stakeinfo->NFTid)){
                $jsonInfo[$key]->stake=$stakeinfo;
            }
            
        }
    
        return view("nfts",['nfts'=>$jsonInfo]);
        
    }

    function Transactions(Request $request){
        
        
        $url= $this->rpc."/transaction?hash=".$request->tx;
        $info = file_get_contents($url);
        
        $jsonTx = json_decode($info);
        
        
        
        
        

        $url= $this->rpc."/block?height=".$jsonTx->BlockHeight;
        $info = file_get_contents($url);
        $jsonBlock = json_decode($info);
        if (!isset($jsonTx->Inpoints)){
            foreach ($jsonBlock->transaction as $jsonTx) {
                if ($jsonTx->ScriptHash == $jsonTx->ScriptHash){
                    $jsonTx = $jsonTx;
                }
            }
        }
        
        
        
        return view('transaction',['jsonTx'=>$jsonTx,'jsonBlock'=>$jsonBlock]);
    }

    function Blocks(Request $request){
        
        $getblockbyHash = $this->rpc."/blockByHash?hash=".$request->hash;
        $info = file_get_contents($getblockbyHash);
        $json = json_decode($info);
        $block = DB::table('blocks')->where('block_hash','=',$request->hash)->first();
         
        foreach($json->transaction as $key => $transaction){
            
            
            $url= $this->rpc."/transaction?hash=".$transaction->ScriptHash;
            $info = file_get_contents($url);
            $newJsonTx = json_decode($info);
    
            if (isset($newJsonTx->Inpoints)){
                $json->transaction[$key]= $newJsonTx;               
            }


        }
        return view('block',['block'=>$block,'json'=>$json]);
    }

    function Search(Request $request){
        $addr = rawurldecode($request->search);
        if (!str_contains($request->search,"metchain:")){
            $addr = "metchain:".$addr;
        }
        $url = $this->rpc."/wallet/transactions?addr=".$addr."&from=0&limit=1";
        $info = file_get_contents($url);
        $json = json_decode($info);
        
        if (isset($json->Totaltx)&&$json->Totaltx>=1){
            
            return redirect()->route('wallet',$request->search);
        }
        
        $wallettxs = DB::table('transactions')->where('txhash','=',$request->search)->first();
        
        if ($wallettxs){
            return redirect()->route('tx',$request->search);
        }
        
        $wallettxs = DB::table('blocks')->where('block_hash','=',$request->search)->first();
        
        if ($wallettxs){
            return redirect()->route('block',$request->search);
        }

        
        //dd($request);
        return redirect()->route('home');
        
    }
    function CheckBalance($wallet){
        if(str_contains($wallet," ")){
            $wallet = urlencode($wallet);
        }
        if(!str_contains($wallet,"metchain:")&& $wallet!="METCHAIN_Blockchain"){
            $wallet = "metchain:".$wallet;
        }
        $url = "http://127.0.0.1:8081/wallet/balance?wl=".$wallet;
        $info = @file_get_contents($url);
        $json = json_decode($info);
        if(isset($json->amount)){
            return $json->amount;
        }else{
            return 0;
        }
        
        
    }

    function CheckBalanceWithNFT($wallet){
        if(str_contains($wallet," ")){
            $wallet = urlencode($wallet);
        }
        if(!str_contains($wallet,"metchain:")){
            $wallet = "metchain:".$wallet;
        }
        $url = "http://127.0.0.1:5000/wallet/balance?wl=".$wallet;
        $info = file_get_contents($url);
        $json = json_decode($info);
        return $json;
        
        
    }

   

    function GetWalletTransaction(Request $request){
        $addr = $request->wallet;
        $type = $request->txtype;
        $from = $request->from;
        $page = $request->page;
        $url = $this->rpc."/wallet/transactions?addr=".$addr."&from=".$from."&limit=20&txtype=".$type;
        
        $info = file_get_contents($url);
        $wallettxs = json_decode($info);
        //dd($url);
        if ($type==3){
            
            return view('module.txinternal',["txs"=>$wallettxs->Txs,"addr"=>$addr]);
        }
        return view('module.txmain',["txs"=>$wallettxs->Txs,"addr"=>$addr,'totaltx'=>$wallettxs->Totaltx,'selectedpage'=>$page]);
    }
    
    function Wallet(Request $request){
        
        if (!str_contains($request->wallet,"metchain:")){
            $addr = "metchain:".$request->wallet;
            return redirect()->route('wallet',$addr);
        }else {
            $addr = $request->wallet;
        }
        
        
        if(str_contains($addr,"Coinbase_stake_met_nft_")){
            return $this->StakeWallet($addr);
        }
        
        $url = $this->rpc."/wallet/transactions?addr=".$addr."&from=0&limit=1&txtype=0";
        $info = file_get_contents($url);
        $wallettxs = json_decode($info);
        
        
        
       
         //dd($walletnftstx);
        if (count($wallettxs->Txs)<=0){
            return redirect()->route('home');
        }

        $url ="http://127.0.0.1:8081/wallet/balance?wl=".$addr;
        $info = file_get_contents($url);
        $balance = json_decode($info);
        
        
        //die();
        $url ="http://127.0.0.1:8081/wallet/nft?wl=".$addr;
        $info = file_get_contents($url);
        $nftbal = json_decode($info);
        
        $staked =[];
        $i=0;
        foreach ($nftbal as $nft){
            
            $stake = file_get_contents($this->rpc."/nftstake?nft=".$nft);
            $stakeinfo = json_decode($stake);
           
            if (isset($stakeinfo->NFTid)){
                $staked[$i]=$stakeinfo;
                
                $i++;
            }
            
        }
        
      
        //dd($queries);
        return view('wallet',['totaltx'=>$wallettxs->Totaltx, "balance"=> $balance,'nftbal'=>$nftbal,"staked"=>$staked,"walletAddr"=>$addr]);
    }
    function GetStakedInfo($nfts){
        $nf = [];
        foreach($nfts as $nft){
            $stake = file_get_contents("http://127.0.0.1:5000/stakenftcheck?nft=".$nft);
            
            $stake = json_decode($stake);
            if(isset($stake->StakeAmount)){
                $nf[$nft]= $stake;
            }
        }
        return $nf;
       
    }
    function GetLatestTransactions(){
        $txs = DB::table('transactions')->latest('id')->limit(10)->get();
        foreach ($txs as $key=> $tx){
            $url= $this->rpc."/block?height=".$tx->block_height;
            $info = file_get_contents($url);
            $jsonBlock = json_decode($info);
            
            foreach ($jsonBlock->transaction as $jsonTx) {
                if ($tx->txhash == $jsonTx->ScriptHash){
                    if (strlen($jsonTx->Timestamp)==13){
                        $jsonTx->Timestamp = $jsonTx->Timestamp."000000";
                    }
                    $tx->jsonTx = $jsonTx;
                    
                }
            }
        }

        return $txs;
    }
    function GetLatestBlocks(){
        return DB::table('blocks')->select('block_hash',"block_height")->latest('id')->limit(10)->get();
    }

   function syncs(Request $request){
    //session(['key' => 'value']);
    //$request->session()->keep('isSync', "True");
    
        
        //$blocks = file_get_contents("https://127.0.0.1:5000/block");
        //$request->session()->forget('isSync');
        //$request->session()->put('isSync', "True");
        $sync = $request->session()->get('isSync');
        
        /*if ($sync == "True"){
            return "Syncing";
        }*/
        $request->session()->put('isSync', "True");
        $blocks = DB::table('blocks')->latest('id')->first();
        if(!$blocks){
            $block_id = 1;
        }else{
            $block_id = $blocks->block_height+1;
        }
       
        $starttime = microtime(true);

       
        
        $query = "";
        $insanequery= array();
        $url = $this->rpc."/block/from-to?from=".$block_id."&limit=10";
        
        $groupblocks = file_get_contents($this->rpc."/block/from-to?from=".$block_id."&limit=100");
        $blocks = json_decode($groupblocks);
        
        foreach($blocks->Blocks as $block){
            
            $blockQuery = "(".$block->Blockheight.",'".$block->Blockhash."')";
            
            if (!isset($insanequery['blocks'])){
                $insanequery['blocks']= "INSERT INTO blocks (`block_height`,`block_hash`) Value ";
            }else{
                $insanequery['blocks'] = $insanequery['blocks'].',';
            }
            $insanequery['blocks'] = $insanequery['blocks']. $blockQuery;
            foreach ($block->Txs as $tx){
                $current =  "(".$block->Blockheight.",'".$tx->Txhash."')";
                if (!isset($insanequery['tx'])){
                    $insanequery['tx']= "INSERT INTO `transactions` ( `block_height`,`txhash` ) VALUES";
                } else{
                    $insanequery['tx'] = $insanequery['tx'].',';
                }
                $insanequery['tx']= $insanequery['tx'].$current;
                
            }
           
            
        }
        $data = DB::statement($insanequery['blocks']);
        $data = DB::statement($insanequery['tx']);
        
        
       
        
         $endtime = microtime(true);
         $duration = $endtime - $starttime;
        echo $duration; 
        $request->session()->forget('isSync');
        
        return "Added";
        
   }

  

    
    function syncWallets(){
        $wallets = DB::table('verifiedwallets')->latest('id')->first();
        
        if(!$wallets){
            $wallet_id = 1;
        }else{
            $wallet_id = $wallets->walletId+1;
        }
        $wallets = file_get_contents("http://127.0.0.1:5000/validwallets?wx=".$wallet_id);
        
        $wallet = json_decode($wallets);
        
        if(isset($wallet->walletAddress)){
            $data = DB::table('verifiedwallets')->insert([
                'walletAddress' => $wallet->walletAddress,
                'blockHash' => $wallet->blockhash,
                'lockhash' => $wallet->lockhash,
                'walletId' => $wallet->walletid,
            ]);
        }
        
    }

    function UpdateRichlist(){
        
        $url =$this->rpc."/Allwallets";
        $json = file_get_contents($url);
        Storage::disk('public')->put('richlist.json', $json);
        
        return "Updated richlist";
        
    }


    function getNetworkInfo(){
        $data = array("method"=>"getNetworkInfo");
         
        $json = json_encode($data);
        $url = 'http://127.0.0.1:5000/jsonRpc/';
        $ch = curl_init($url);
         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ));
         
        $response = curl_exec($ch);
        if(curl_errno($ch)) {
            return 'Error: ';
        } 
        curl_close($ch);
        return json_decode($response);
    }
    

    function StakeWallet($addr){
        $nftid = explode("Coinbase_stake_met_nft_",$addr);
        
        
        if($nftid[1]>=1&&$nftid[1]<=500){
            $i = $nftid[1];
        } else{
            return;
        }
       
        $stake = file_get_contents($this->rpc."/nftstake?nft=".$i);
        $stakeinfo = json_decode($stake);
        
       
        
        return view('stakingwallet',["staked"=>$stakeinfo,"walletAddr"=>$addr]);
    }
}
