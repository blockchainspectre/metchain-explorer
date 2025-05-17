@include('layout.header')

<div class="inner-body inner-page"> 
    <h3>Wallet Details</h3>
    <div class="full-box">
        <div class="part-2">
			<div class="b-r-3">
				<div class="wallet-page">
					<div class="met-icon"></div>
					<div class="wallet-info">
						<h4>MET BALANCE</h4>
                        @if(isset($balance))

						<h5 style="font-size:26px; margin-top:8px;">{{number_format($balance->amount,5)}} MET</h5>
                        @endif
					</div>
				</div>
				<div class="nft-area-page wallet-page wallet-info">
						<h4>MET NFTs</h4>
                        @if(isset($nftbal))
                            @foreach($nftbal as $nft)
                                <div class="nft-align">
                                    <img src="https://metwallet.metchain.tech/vec/resources/images/nft/{{$nft}}.png" />
                                    <h4>#{{$nft}}</h4>
                                </div>
                            @endforeach
                        @endif
					</div>
			</div>
			
		</div>
	
		<?php 
        /*<div class="part-2">
			<div class="b-r-3">
				<div class="wallet-page">
					<div>
                        <div class="wallet-head-info">More Info</div>
						<div class="wallet-subhead">LAST TRANSACTION</div>
						<div><a href="{{route('tx',$txs[0]->txHash)}}">{{$txs[0]->txHash}}</a> <abbr class="timeago testLongTerm" title="{{date('Y-m-d\TH:i:s\Z',($txs[0]->timestamp))}}">{{date('Y-m-d TH:i:s',($txs[0]->timestamp))}}</abbr></div>
                        
                    </div>
				</div>
					
			</div>
			
		</div>*/
        ?>
    </div>
    <div class="special-nav-wallet full-box">
        <ul>
            <li data-id="tx-tab">Transactions</li>
            <li data-id="internal-tab">Internal Transactions</li>
            <li data-id="staked-tab">Staked NFT's</li>
        </ul>
    </div>
    <script type="text/javascript">
        $(function(){
            $(".special-nav-wallet ul li").click(function(){
                var tab = $(this).attr('data-id')
                $(".switching-tab").hide();
                $("#"+tab).show();
            })
        })
    </script>
    <div class="full-box w-s-250" style="background:rgb(8 21 34 / 64%)" >
        
        <div class="transaction-list-table switching-tab" id="staked-tab">
            <h3>Staked NFT's</h3>
            @if(count($staked)<=0)
                <div class="f-w">
                    <h3>No NFT is staked under this wallet</h3>
                </div>
            @endif
            
            @foreach($staked as $nft)
                
            <div>
            
            
                <div class="f-w">
                    
                    <div class="receivedtx">Staked : <span>+</span>{{number_format($nft->StakeAmount,5)}} MET</div> 
                    
                    
                     <?php 
                    
                    if (strlen($nft->LockTime) ==  19){
                        $timesplit = 1000000000;
                    }else {
                        $timesplit = 1000;
                    }
                    ?>
                </div>
                <div class="f-w">
                    <div class="timealign">Lock Time: <abbr class="timeago testLongTerm" title="{{date('Y-m-d\TH:i:s\Z',($nft->LockTime/$timesplit))}}">{{date('Y-m-d TH:i:s',($nft->LockTime/$timesplit))}}</abbr></div>
                    
                    <div class="nft-align">
                        <img src="https://metwallet.metchain.tech/vec/resources/images/nft/{{$nft->NFTid}}.png" />
                        <h4>MET NFT #{{$nft->NFTid}}</h4>
                    </div>
                </div>
                <div class="f-w">
                    <div class="timealign">Unlock Time: {{date('Y-m-d TH:i:s',($nft->UnlockTime/$timesplit))}}</div>
                </div>
                <div class="f-w">
                    <div class="txidhash">ID: <a href="{{route('tx',$nft->Txhash)}}">{{$nft->Txhash}}</a></div>
                
                    <div class="txwallet">From : <a href="{{route('wallet',rawurlencode($nft->NFTSender))}}">{{$nft->NFTSender}}</a></div>
                </div>
            </div>
            @endforeach
        </div>
        <script type="text/javascript">
            $(function(){
                ajaxUrl ="{{route('GetWalletTransaction')}}?wallet={{$walletAddr}}&txtype=0&from=0&page=1";
                    $.get(ajaxUrl,function(data){
                        $("#maintx").html(data)
                        jQuery(".timeago").timeago();
                    })
               /*setInterval(() => {
                    ajaxUrl ="{{route('GetWalletTransaction')}}?wallet={{$walletAddr}}&txtype=1&from=0&page=1";
                    $.get(ajaxUrl,function(data){
                        $("#maintx").html(data)
                        jQuery(".timeago").timeago();
                    })
               }, 2000);*/
                ajaxUrl ="{{route('GetWalletTransaction')}}?wallet={{$walletAddr}}&txtype=3&from=0&limit=20";
                
                $.get(ajaxUrl,function(data){
                    $("#internalTx").html(data)
                    
                    jQuery(".timeago").timeago();
                })
                
            })
        </script>
        <div class="transaction-list-table switching-tab" id="tx-tab">
            <h3>Wallet Transactions</h3>
            <div style="color:#fff;text-align:right;padding-right:20px; font-size:12px; padding-bottom:8px;">Total Transactions {{$totaltx-1}}</div>
            <div class="ajaxLoader" id="maintx"></div>
        </div>
        <div class="transaction-list-table switching-tab" id="internal-tab">
            <h3>Wallet Internal Transactions</h3> 
            <div class="ajaxLoader" id="internalTx"></div>
        </div>
        <?php /*<div class="transaction-list-table switching-tab" id="internal-tab">
            <h3>Wallet Internal Transactions</h3>
           
            @if(count($walletnftstx)<=0)
                <div class="f-w">
                    <h3>No internal transaction detected.</h3>
                </div>
            @endif
            @foreach($walletnftstx as $tx)
             
            <div>
            
            
                <div class="f-w">
                    @if(str_contains(url()->current(),($tx->Inpoints->ScriptPublicKey)))
                        <div class="senttx"> <span>-</span> Sent</div> 
                    @else
                        <div class="receivedtx"><span>+</span>Received</div> 
                    @endif
                   
                    @if($tx->Tstatus==9)
                        <div class="rejecttx">Not Accepted</div> 
                    @elseif($tx->Tstatus==1 || $tx->Tstatus==0)
                        <div class="confirmedtx">Confirmed</div> 
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
                    <div class="nft-align">
                        <img src="https://metwallet.metchain.tech/vec/resources/images/nft/{{$tx->Inpoints->Value}}.png" />
                        <h4>MET NFT #{{$tx->Inpoints->Value}}</h4>
                    </div>
                </div>
                <div class="f-w">
                    <div class="txidhash">ID: <a href="{{route('tx',$tx->ScriptHash)}}">{{$tx->ScriptHash}}</a></div>
                    
                    @if(!str_contains(url()->current(),($tx->Inpoints->ScriptPublicKey)))
                        @if(str_contains($tx->Inpoints->ScriptPublicKey,"METCHAIN_Blockchain"))
                            <div class="txwallet">From : {{$tx->Inpoints->ScriptPublicKey}}</div>
                        @else
                            <div class="txwallet">From : <a href="{{route('wallet',($tx->Inpoints->ScriptPublicKey))}}">{{$tx->Inpoints->ScriptPublicKey}}</a></div>
                        @endif

                    @elseif(!str_contains(url()->current(),($tx->Outpoints[0]->ScriptPublicKey)))
                        <div class="txwallet">To : <a href="{{route('wallet',($tx->Outpoints[0]->ScriptPublicKey))}}">{{$tx->Outpoints[0]->ScriptPublicKey}}</a></div>
                    @endif
                </div>
            </div>
            @endforeach
            
        </div>
    
        
        <div class="transaction-list-table switching-tab" id="tx-tab">
            <h3>Wallet Transactions</h3>
            @foreach($txs as $tx)
            
            <div>
            
            
                <div class="f-w">
                    @if(str_contains(url()->current(),($tx->Inpoints->ScriptPublicKey)))
                        <div class="senttx"> <span>-</span> Sent</div> 
                    @else
                        <div class="receivedtx"><span>+</span>Received</div> 
                    @endif
                    @if($tx->Tstatus==9)
                        <div class="rejecttx">Not Accepted</div> 
                    @elseif($tx->Tstatus==1|| $tx->Tstatus==0)
                        <div class="confirmedtx">Confirmed</div> 
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
                    if(str_contains(url()->current(),str_replace("metchain:","",($tx->Inpoints->ScriptPublicKey)))){
                        $value = $tx->Inpoints->Value;
                    }else{
                       
                        foreach($tx->Outpoints as $key=> $outpoints ){
                            if (str_contains(url()->current(),str_replace("metchain:","",($outpoints->ScriptPublicKey)))){
                                $value = $outpoints->Value;
                            }
                        }
                    }
                    ?>
                    <div class="met-amount">{{number_format($value,5)}} MET</div>
                </div>
                <div class="f-w">
                    <div class="txidhash">ID: <a href="{{route('tx',$tx->ScriptHash)}}">{{$tx->ScriptHash}}</a></div>
                   
                    @if(!str_contains(url()->current(),($tx->Inpoints->ScriptPublicKey)))
                        @if(str_contains($tx->Inpoints->ScriptPublicKey,"METCHAIN_Blockchain"))
                            <div class="txwallet">From : {{$tx->Inpoints->ScriptPublicKey}}</div>
                        @else
                            <div class="txwallet">From : <a href="{{route('wallet',($tx->Inpoints->ScriptPublicKey))}}">{{$tx->Inpoints->ScriptPublicKey}}</a></div>
                        @endif
                        
                    @elseif(!str_contains(url()->current(),($tx->Outpoints[0]->ScriptPublicKey)))
                        <div class="txwallet">To : <a href="{{route('wallet',($tx->Outpoints[0]->ScriptPublicKey))}}">{{$tx->Outpoints[0]->ScriptPublicKey}}</a></div>
                    @endif
                    
                </div>
            </div>
            @endforeach
            
        </div>
        */?>
    </div>
        
    </div>
</div>

@include('layout.footer')