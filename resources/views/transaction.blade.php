@include('layout.header')

<div class="inner-body inner-page"> 
    <h3>Transaction Details</h3>
    <div class="full-box">
        <div class="tx-dropper">
            <div>Transaction Hash :</div>
            <div> {{$jsonTx->ScriptHash}}</div>
        </div>
        <div class="tx-dropper">
            <div>Status :</div>
            
            @if($jsonTx->Tstatus =="1" ||$jsonTx->Tstatus =="0" )
                <div> <span class="confirm-box">Confirmed</span></div>
            @else
                <div> <span class="confirm-box rejected" style="background:#f00; color:#fff; border:1px solid #f00">Rejected</span></div>
            @endif
        </div>    
        <div class="tx-dropper">
            <div>Block : </div>
            <div>{{$jsonTx->BlockHeight}} <span style="margin-left:50px;">Block Hash :<a href='{{route("block",$jsonBlock->header->currentHash)}}' class="box-link"> {{$jsonBlock->header->currentHash}}</a></span></div>
        </div>    
        
        <div class="tx-dropper">
            <?php if (strlen($jsonTx->Timestamp)==19){
                $timestamp = $jsonTx->Timestamp/1000000000;
            }else{
                $timestamp = $jsonTx->Timestamp/1000;
            }?>
            <div>Timestamp :</div>
            <div><abbr class="timeago testLongTerm" title="{{date('Y-m-d\TH:i:s\Z',($timestamp))}}">{{date('Y-m-d TH:i:s',$timestamp)}}</abbr><span>{{date('Y-m-d T   H:i:s',($timestamp))}}</span></div>
        </div>    
        <div class="deck-split"></div>
        <div class="tx-dropper">
            <div>From :</div>
            <div>{{$jsonTx->Inpoints->ScriptPublicKey}}</div>
        </div>    
        <?php $fee=0 ?>
        @foreach($jsonTx->Outpoints as $outpoint)
        <?php if($outpoint->Isfee){ 
            $fee = $outpoint->Value; 
        }; ?>
        <div class="tx-dropper">
            <div>To :</div>
            <div>{{$outpoint->ScriptPublicKey}}</div>
        </div>    
        
        <div class="tx-dropper">
            <div>Value :</div>
            <div>{{number_format($outpoint->Value,5)}} Met</div>
        </div>    
        @endforeach
        <div class="deck-split"></div>
        
        <div class="tx-dropper">
            <div>Transaction Fee :</div>
            <div>{{number_format($fee,5)}} Met</div>
        </div>    
        
        
    </div>
</div>

@include('layout.footer')