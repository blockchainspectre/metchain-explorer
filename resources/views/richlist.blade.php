@include('layout.header')

<div class="inner-body inner-page"> 
    <h3>Rich List</h3>
    <div class="full-box listing-page">
        
        <div style="border-bottom:1px solid #333;float:left; width:100%; font-size:12px;line-height:24px; color:#fff; font-weight:bold;" class="heading-bg">
                <div style="color:#fff; padding:8px 20px;float:left; width:50px;">#</div>
                <div style="width:500px; padding:8px  20px;float:left;" class="list-controlR">Address</div>
                <div style="color:#fff;  padding:8px 20px;float:right;" class="list-controlR">Amount</div>
        </div>
        <div class="inner-scroll">
            <?php $i = 1; ?>    
            
            @foreach($wallets as $wallet)
                <?php if($i==1000){
                    break;
                }?>
                <div style="border-bottom:1px solid #333;float:left; width:100%; font-size:12px;line-height:24px; color:#fff;">
                    <div style="color:#fff; padding:8px 20px;float:left; width:50px;">{{$i}}</div>
                    <div style="width:20%; padding:8px  20px;float:left; font-weight:bold;" class="list-controlR"><a href="{{route('wallet',$wallet->Addr)}}">{{substr($wallet->Addr,0,11).'...'.substr($wallet->Addr,(strlen($wallet->Addr)-12))}} </a></div>
                    <div style="color:#fff;  padding:8px 20px;float:right;" class="list-controlR"> {{number_format($wallet->Balance,5)}} MET</div>
                </div>
                <?php $i++;?>
            @endforeach
        </div>
    </div>
</div>

@include('layout.footer')