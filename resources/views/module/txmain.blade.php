<script type="text/javascript">
$(function(){
    $(".switch-tx-page").click(function(){
        fromid = $(this).attr("data-ajax")
        cpage = $(this).attr("data-id")
        ajaxUrlPage ="{{route('GetWalletTransaction')}}?wallet={{$addr}}&txtype=0&from="+fromid+"&limit=20&page="+cpage;
        $.get(ajaxUrlPage,function(data){
            $("#maintx").html(data)
            jQuery(".timeago").timeago();
        })
        
    })
})
</script>


            
<?php 

$pages = intval($totaltx/20);
if ($pages < ($totaltx/20)){
    $pages= $pages+1;
    $selectedpage++;
}
if ($totaltx>20){


$lastpage = $pages;
if ($lastpage>5){
    echo "<div class='switch-tx-page' data-ajax='".($lastpage*20)."' data-id='".$lastpage."'>Last </div>";
}

if ($selectedpage <=4){
    $currentpage =4;
    
}else if ($selectedpage >=$pages-2){
    $currentpage = $selectedpage-1;
}else if ($selectedpage >=5){
    $currentpage = $selectedpage+2;
}

if (isset($currentpage)&& $currentpage > $lastpage){
    $currentpage=$lastpage;
} 
$pagecount =0;
while($pages>=0){
    
    $pagecount++;
    if (!isset($currentpage) ||$currentpage<$pages){
        
    
    echo "<div class='switch-tx-page' data-ajax='".($currentpage*20)."' data-id='".($currentpage+1)."'> ".($currentpage+1)."</div>";
}
    $currentpage= $currentpage-1;
    
    
    if ($pagecount == 5 || $currentpage<0 ){
        break;
    }
}

if ($lastpage>5){
    echo "<div class='switch-tx-page' data-ajax='0' data-id='1'>First </div>";
    
}
}
?>
            
            @foreach($txs as $tx)
            <?php 
            if($tx->ScriptHash == "fa95c19583f92a6873a5367fe3d6c50384bff17f8298aca20dc59b3485ffb439"){
                continue;
            }
            ?>
            <div class="transaction-list-design">
            
            
                <div class="f-w">
                    
                    @if($addr == $tx->Inpoints->ScriptPublicKey)
                        <div class="senttx"> <span>-</span> Sent</div> 
                    @else
                        <div class="receivedtx"><span>+</span>Received</div> 
                    @endif
                    @if($tx->Tstatus==9)
                        <div class="rejecttx">Not Accepted</div> 
                    @elseif($tx->Tstatus==1|| $tx->Tstatus==0)
                        <div class="confirmedtx">Confirmed</div>
                        <div class="confirmedtx" style="font-size:10px; background:#ccc; color:#333; margin-right:20px;">{{$tx->Confirmations}} Confirmations</div> 
                    @endif
                </div>
                <div class="f-w">
                    <?php 
                    
                    if (strlen($tx->Timestamp) ==  19){
                        $timestamp = $tx->Timestamp/1000000000;
                    }else {
                        $timestamp = $tx->Timestamp/1000;
                    }
                    ?>

                    
                    <div class="timealign"><abbr class="timeago testLongTerm" title="{{date('Y-m-d\TH:i:s\Z',($timestamp))}}">{{date('Y-m-d TH:i:s',($timestamp))}}</abbr></div>

                    <?php
                    if($addr == $tx->Inpoints->ScriptPublicKey){
                        $isSender = 0;
                        $value = $tx->Inpoints->Value;
                    }else{
                       
                        foreach($tx->Outpoints as $key=> $outpoints ){
                            if ($addr == $outpoints->ScriptPublicKey){
                                $value = $outpoints->Value;
                                $isSender = $key;
                            }
                        }
                    }
                    ?>
                    <div class="met-amount">{{number_format($value,5)}} MET</div>
                </div>
                <div class="f-w">
                    <div class="txidhash">ID: <a href="{{route('tx',$tx->ScriptHash)}}">{{$tx->ScriptHash}}</a></div>
                   
                    @if($addr !=($tx->Inpoints->ScriptPublicKey))
                        @if(str_contains($tx->Inpoints->ScriptPublicKey,"METCHAIN_Blockchain"))
                            <div class="txwallet">From : {{$tx->Inpoints->ScriptPublicKey}}</div>
                        @else
                            <div class="txwallet">From : <a href="{{route('wallet',($tx->Inpoints->ScriptPublicKey))}}">{{$tx->Inpoints->ScriptPublicKey}}</a></div>
                        @endif
                        
                    @else
                        <div class="txwallet">To : <a href="{{route('wallet',($tx->Outpoints[0]->ScriptPublicKey))}}">{{$tx->Outpoints[$isSender]->ScriptPublicKey}}</a></div>
                    @endif
                    
                </div>
            </div>
            @endforeach
            
        