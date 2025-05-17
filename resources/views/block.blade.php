@include('layout.header')

<div class="inner-body inner-page"> 
    <h3>Block Details</h3>

    <div class="full-box block-left-area">
        <div class="tx-dropper">
            <div>Block Height :</div>
            <div> {{$block->block_height}}</div>
        </div>
        <div class="tx-dropper">
            <div>Status :</div>
            <div> <span class="confirm-box">Confirmed</span></div>
        </div>    
        <div class="tx-dropper">
            <div>Timestamp : </div>
           
            <div><abbr class="timeago testLongTerm" title="{{date('Y-m-d\TH:i:s\Z',($json->header->timestamp/1000))}}">{{date('Y-m-d TH:i:s',($json->header->timestamp/1000))}}</abbr><span>{{date('Y-m-d T   H:i:s',($json->header->timestamp/1000))}}</span></div>
        </div>
        <div class="tx-dropper">
            <div>Block Hash: </div>
            <div> {{$block->block_hash}}</div>
        </div>
        @if($block->block_height >= 2)    
            <div class="tx-dropper">
                <div>Previous Block Hash: </div>
                
                <div> <a href="{{route('block',$json->header->previousHash)}}"> {{$json->header->previousHash}}</a></div>
            </div>
        @endif
        <div class="tx-dropper">
            <div>Mega Block Hash: </div>
            <div> {{$json->header->megablock}}</div>
        </div> 
        <div class="tx-dropper">
            <div>Met Block Hash: </div>
            <div> {{$json->header->metblock}}</div>
        </div> 
        <div class="tx-dropper">
            <div>Nonce: </div>
            <div> {{number_format($json->header->nonce)}}</div>
        </div>
        <div class="tx-dropper">
            <div>Bits :</div>
            <div>{{$json->header->bits}} (Network Difficulty)</div>
        </div> 
    </div>
    <div class="full-box block-right-area">
        <h2>Block Transactions :</h2>
        
            
        <div>
            <h3>Total {{count($json->transaction)}} transactions in the block.</h3>
            
            <?php $i=0 ?>
                @foreach($json->transaction as $key => $tx)
                <?php $i =count($tx->Outpoints)+$i ?>
                <div class="new-block-split" style="float:left; width:100%; border-bottom:1px solid #ccc;padding-bottom:20px">
                <div class="new-block-sender">
                    <h4>Senders ({{count($json->transaction)}})</h4>
                    
                        @if($tx->Inpoints->ScriptPublicKey != 'METCHAIN_Blockchain')
                            <div class="new-sender-info">
                                <div class="senttx" bis_skin_checked="1"><span>-</span>Sent</div>
                                @if($tx->Tstatus==9)
                                    <div class="rejecttx">Not Accepted</div> 
                                @elseif($tx->Tstatus==1)
                                    <div class="confirmedtx">Confirmed</div> 
                                @endif
                                <a href="{{route('wallet',($tx->Inpoints->ScriptPublicKey))}}">{{$tx->Inpoints->ScriptPublicKey}}</a> 
                                <span class="tx-amount senttx-bg">- {{number_format($tx->Inpoints->Value,5)}} MET</span>
                            </div>
                            
                        @else
                            <div class="new-sender-info">Coinbase (New Coins)</div>
                                
                        @endif
                        <div class="txidhash"><a href="{{route('tx',$tx->ScriptHash)}}">ID:{{$tx->Inpoints->Txhash}}</a></div>
                </div>    
                <div class="new-block-reciever">
                    <h4>Receiver ({{$i}})</h4>
                        @foreach($tx->Outpoints as $outpoint)
                        <div class="new-reciver-info">
                            <div class="receivedtx" bis_skin_checked="1"><span>+</span>Received</div>
                            
                            @if($tx->Tstatus==9 )
                                <div class="rejecttx">Not Accepted</div> 
                            @elseif($tx->Tstatus==1 || $tx->Tstatus==0)
                                <div class="confirmedtx">Confirmed</div> 
                            @endif
                            <a href="{{route('wallet',($outpoint->ScriptPublicKey))}}">{{$outpoint->ScriptPublicKey}}</a> 
                            <span class="tx-amount">{{number_format($outpoint->Value,5)}} MET</span>
                        </div>
                        <div class="txidhash"><a href="{{route('tx',$tx->ScriptHash)}}">ID:{{$outpoint->Txhash}}</a></div>
                        @endforeach
                    
                </div>
                </div>
                @endforeach
            </div>
           
                
            </div>
        </div>
           
        
    </div>
</div>

@include('layout.footer')