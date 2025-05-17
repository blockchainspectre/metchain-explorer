@include('layout.header')

<div class="inner-body inner-page"> 
    <h3>NFT Holders</h3>
    <div class="full-box nft-page">
        @foreach($nfts as $nft)
        <?php 
        
        $link = str_replace("metchain:","",$nft->Outpoints[0]->ScriptPublicKey);
        //dd($nft);
        ?>
            <div style="border-bottom:1px solid #fff;float:left; width:50%;min-width:350px; font-size:12px;line-height:24px; color:#fff;">
                <div class="f-w">
                    
                    <div class="nft-align">
                        <img src="https://metwallet.metchain.tech/vec/resources/images/nft/{{$nft->Inpoints->Value}}.png" />
                        <h4>MET NFT #{{$nft->Inpoints->Value}}</h4>
                    </div>
                    <div style="color:#fff;  padding:15px 20px;" class="list-control">
                        @if(!isset($nft->stake))
                            <div>Status : Unlocked</div>
                        @else
                        
                            <div>Status : Staked</div>
                            
                            <div><div class="receivedtx">Staked : <span>+</span>{{number_format($nft->stake->StakeAmount,5)}} MET</div> </div>
                            <div>Stake Time : {{date('Y-m-d',($nft->stake->LockTime/1000000000))}}</div>
                            <div>Stake Unlock : {{date('Y-m-d ',($nft->stake->UnlockTime/1000000000))}}</div>
                        @endif
                    
                        <div class="txidhash">ID : {{$nft->ScriptHash}} </div>
                    
                        <div class="txwallet">From : <a href="{{route('wallet',$nft->Inpoints->ScriptPublicKey)}}">{{$nft->Inpoints->ScriptPublicKey}}</a></div>
                        <div class="txwallet">To : <a href="{{route('wallet',$link)}}">{{$nft->Outpoints[0]->ScriptPublicKey}}</a></div>
                        
                    </div>
                </div>
               
               
                
                
            </div>
        @endforeach
        
    </div>
</div>

@include('layout.footer')