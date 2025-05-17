
           
            @if(count($txs)<=0)
                <div class="f-w">
                    <h3>No internal transaction detected.</h3>
                </div>
            @endif
            @foreach($txs as $tx)
             
            <div class="transaction-list-design">
            
            
                <div class="f-w">
                    @if($addr ==($tx->Inpoints->ScriptPublicKey))
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
                    
                    @if($addr !=($tx->Inpoints->ScriptPublicKey))
                        @if(str_contains($tx->Inpoints->ScriptPublicKey,"METCHAIN_Blockchain"))
                            <div class="txwallet">From : {{$tx->Inpoints->ScriptPublicKey}}</div>
                        @else
                            <div class="txwallet">From : <a href="{{route('wallet',($tx->Inpoints->ScriptPublicKey))}}">{{$tx->Inpoints->ScriptPublicKey}}</a></div>
                        @endif

                    @else
                        <div class="txwallet">To : <a href="{{route('wallet',($tx->Outpoints[0]->ScriptPublicKey))}}">{{$tx->Outpoints[0]->ScriptPublicKey}}</a></div>
                    @endif
                </div>
            </div>
            @endforeach
            
       