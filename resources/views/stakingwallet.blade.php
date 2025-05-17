@include('layout.header')

<div class="inner-body inner-page"> 
    <h3>Staking Wallet Details</h3>
    
    <div class="full-box">
        <div class="part-2">
			<div class="b-r-3">
				<div class="wallet-page">
                    <div class="met-wallet-addr">
                        <span>Wallet Address : </span>  <b>metchain:Coinbase_stake_met_nft_{{$staked->NFTid}}</b> 
                        
                    </div>
					<div class="met-icon"></div>
					<div class="wallet-info">
                    
						<h4>MET BALANCE</h4>
                        @if(isset($staked))

						<h5 style="font-size:26px; margin-top:8px;">{{number_format($staked->StakeAmount,5)}} MET</h5>
                        @endif
					</div>
				</div>
				
			</div>
			
		</div>
	
		
    </div>
    <div class="special-nav-wallet full-box disabled-box">
        <ul>
            <li data-id="tx-tab">Transactions</li>
            <li data-id="internal-tab">Internal Transactions</li>
            <li data-id="staked-tab">Staked NFT's</li>
        </ul>
    </div>
    
    <div class="full-box w-s-250" style="background:rgb(8 21 34 / 64%)" >
        
        <div class="transaction-list-table" id="staked-tab">
            <h3>Staked NFT's</h3>

            <div>
                <div class="f-w">   
                    <div class="receivedtx">Staked : <span>+</span>{{number_format($staked->StakeAmount,5)}} MET</div> 
                     <?php 
                    
                    if (strlen($staked->LockTime) ==  19){
                        $timesplit = 1000000000;
                    }else {
                        $timesplit = 1000;
                    }
                    ?>
                </div>
                <div class="f-w">
                    <div class="timealign">Lock Time: <abbr class="timeago testLongTerm" title="{{date('Y-m-d\TH:i:s\Z',($staked->LockTime/$timesplit))}}">{{date('Y-m-d TH:i:s',($staked->LockTime/$timesplit))}}</abbr></div>
                    
                    <div class="nft-align">
                        <img src="https://metwallet.metchain.tech/vec/resources/images/nft/{{$staked->NFTid}}.png" />
                        <h4>MET NFT #{{$staked->NFTid}}</h4>
                    </div>
                </div>
                <div class="f-w">
                    <div class="timealign">Unlock Time: {{date('Y-m-d TH:i:s',($staked->UnlockTime/$timesplit))}}</div>
                </div>
                <div class="f-w">
                    <div class="txidhash">ID: <a href="{{route('tx',$staked->Txhash)}}">{{$staked->Txhash}}</a></div>
                
                    <div class="txwallet">From : <a href="{{route('wallet',rawurlencode($staked->NFTSender))}}">{{$staked->NFTSender}}</a></div>
                </div>
            </div>
            
        </div>
        
        
       
    </div>
        
    </div>
</div>

@include('layout.footer')