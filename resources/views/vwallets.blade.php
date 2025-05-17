@include('layout.header')

<div class="inner-body inner-page"> 
    <h3>Verfied Wallets List (Only latest 250 wallets)</h3>
    <div class="full-box listing-page">
        <div style="border-bottom:1px solid #333;float:left; width:100%; font-size:12px;line-height:24px; color:#fff; font-weight:bold;" class="heading-bg">
            <div style="color:#fff; padding:8px 20px;float:left; width:20px;">#</div>
            <div style="padding:8px 20px;" class="list-control">Wallet Address : </div>
            <div style="width:28%; padding:8px 20px;float:left;" class="list-control">Verification Block</div>
            <div style="width:28%; padding:8px 20px;float:left;" class="list-control">Wallet Lockhash</div>
            
        </div>
        <div class="inner-scroll">
            @foreach($wallets as $wallet)
                
                <div style="border-bottom:1px solid #333;float:left; width:100%; font-size:12px;line-height:24px; color:#fff;">
                    <div style="color:#fff; padding:8px 20px;float:left; width:20px;">{{$wallet->walletId}}</div>
                    <div style="padding:8px 20px;" class="list-control"><a href="{{route('wallet',str_replace('metchain:','',$wallet->walletAddress))}}">{{substr($wallet->walletAddress,0,11).'...'.substr($wallet->walletAddress,(strlen($wallet->walletAddress)-12))}}</a></div>
                    <div style="width:28%; padding:8px 20px;float:left;" class="list-control"><a href="{{route('block',$wallet->blockHash)}}">{{substr($wallet->blockHash,0,11)."...".substr($wallet->blockHash,(strlen($wallet->blockHash)-12))}} </a></div>
                    <div style="width:28%; padding:8px  20px;float:left;" class="list-control">{{substr($wallet->lockhash,0,11)."...".substr($wallet->lockhash,(strlen($wallet->lockhash)-12))}}</div>
                    
                </div>
                
            @endforeach
        </div>
    </div>
</div>

@include('layout.footer')