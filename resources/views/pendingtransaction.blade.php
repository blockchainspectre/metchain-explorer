@include('layout.header')

<div class="inner-body inner-page"> 
    <h3>Transaction Details</h3>
    <div class="full-box">
        <div class="tx-dropper">
            <div>Transaction Hash :</div>
            <div> {{$tx->txhash}}</div>
        </div>
        <div class="tx-dropper">
            <div>Status :</div>
            <div> <span class="red-box">Pending</span></div>
        </div>    
        <div class="tx-dropper">
            <div>Block : </div>
            <div>Pending</div>
        </div>    
        
        <div class="tx-dropper">
            <div>Timestamp :</div>
            <div><abbr class="timeago testLongTerm" title="{{date('Y-m-d\TH:i:s\Z',($tx->timestamp/1000000000))}}">{{date('Y-m-d TH:i:s',($tx->timestamp/1000000000))}}</abbr><span>{{date('Y-m-d T   H:i:s',($tx->timestamp/1000000000))}}</span></div>
        </div>    
        <div class="deck-split"></div>
        <div class="tx-dropper">
            <div>From :</div>
            <div>{{$tx->sender_blockchain_address}}</div>
        </div>    
        
        <div class="tx-dropper">
            <div>To :</div>
            <div>{{$tx->recipient_blockchain_address}}</div>
        </div>    
        
        <div class="tx-dropper">
            <div>Value :</div>
            <div>{{$tx->value}} MET</div>
        </div>    

        
    </div>
</div>

@include('layout.footer')