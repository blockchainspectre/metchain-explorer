@include('layout.header')
<div class="inner-body">
	<div class="inner-body-bg">
	</div>
	@include('module.search')

	<div class="full-box">
		<div class="part-3">
			<div class="b-r-3">
				<div class="home-headers">
					<div class="met-icon"></div>
					<div>
						<h4>Met Price <span>+ 0.00 %</span></h4>
						<h5>$ {{ number_format($infobc['price'],4)}} <span>0.000000000 BTC<span></h5>
					</div>
				</div>
				<div class="home-headers">
					<div class="met-icon mm-icon"></div>
					<div>
						<h4>MARKET CAP</h4>
						<h5>${{number_format($infobc['balanceburnt']*$infobc['price'],2)}}</h5>
					</div>
				</div>		
			</div>
			
		</div>
	
		<div class="part-3">
			<div class="b-r-3">
				<div class="home-headers">
					<div class="met-icon tx-icon"></div>
					<div>
						<h4>TRANSACTIONS</h4>
						<h5>{{round($infobc['tps'],2)}}<span> &nbsp;TPS<span></h5>
					</div>
				</div>
				<div class="home-headers">
					<div class="met-icon met-icon"></div>
					<div>
						<h4>TOTAL STAKED</h4>
						<h5>{{number_format($infobc['totalStaked'],2)}} MET<span>(${{number_format($infobc['totalStaked']*$infobc['price'],2)}})<span></h5>
					</div>
				</div>		
			</div>
			
		</div>
		<div class="part-3">
			<div class="b-r-3">
				<div class="home-headers">
					<div class="met-icon"></div>
					<div>
						<h4>POS - POW MINABLE</h4>
						<h5 ><span class="animate-balance">{{(float)$infobc['balance']}}</span> MET</h5>
					</div>
				</div>
				<div class="home-headers">
					<div class="met-icon mm-icon"></div>
					<div>
						<h4>CIRCULATING SUPPLY</h4>
						<h5><span class="animate-supply">{{$infobc['balanceburnt']}}</span> MET <span class="burnt-supply"><img class="burning" src="{{URL::asset('resources/images/burning.png')}}" /> Burned  <b>{{number_format($infobc['burnt'],2)}}</b> MET <span></h5>
					</div>
				</div>		
			</div>
			
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			jQuery(".timeago").timeago();
			$('.animate-circle').animate("rotate:360",1000)
			
			//setTimeout
			//setInterval
			var oldval = $('.animate-balance').text()
			var oldsup = $('.animate-supply').text()
			setInterval(() => {
				$.get("{{route('home')}}",function(e){
					
					tx = $(e).find('.latest-tx').html()
					bal = $(e).find('.animate-balance').text()
					balsupply = $(e).find('.animate-supply').text()
					
					$('.latest-tx').html(tx)
					bl = $(e).find('.latest-bl').html()
					$('.latest-bl').html(bl)
					if(oldval != bal){
						
						$(".animate-balance") .prop('number', oldval).animateNumber(
															{
															number: bal,
															numberStep: function(now, tween) {
																// see http://stackoverflow.com/a/14428340
																var formatted = now.toFixed(6).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
																$(tween.elem).text(formatted);
															}
															},
															2000
														);
														oldval = bal
						$(".animate-supply") .prop('number', oldsup).animateNumber({
																						number: balsupply,
																						numberStep: function(now, tween) {
																							// see http://stackoverflow.com/a/14428340
																							var formatted = now.toFixed(4).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
																							$(tween.elem).text(formatted);
																						}
																						},
																						2000
																					);
						oldval = bal
						oldsup = balsupply
					}
					
					jQuery(".timeago").timeago();
				})
			}, 5000);

		})
	</script>
	<div class="full-box w-50p list-header latest-bl p-a-0">
		<h4 class="heading-bg">Latest Blocks <img src="{{URL::asset('resources/images/32x32.png')}}" class="rotating"/></h4>
		
		<div class="append-block"></div>
		@foreach($blocks as $block)
		<div class="listing-tx">
			
			<div class="ltx-icon"><img src="{{URL::asset('resources/images/box.png')}}"/></div>
			<div class="new-dual-split">
				<div class="new-tx-class"><a href="{{route('block',$block->block_hash)}}">{{$block->block_hash}}</a><div>Block height: </div><div class="new-tx-amount">{{$block->block_height}}</div></div>
				<div class="time-ago">
					<?php /*<abbr class="timeago testLongTerm" title="{{date('Y-m-d\TH:i:s\Z',($block->timestamp/1000))}}">{{date('Y-m-d\TH:i:s\Z',($block->timestamp/1000))}}</abbr>*/?>
				</div>
			</div>
			<div class="dual-split" >
				
				<div></div>
			</div>
			
		</div>
		@endforeach
	</div>
	<div class="full-box w-50p list-header latest-tx p-a-0">
		<h4 class="heading-bg">Latest Transactions <img src="{{URL::asset('resources/images/32x32.png')}}" class="rotating"/></h4>
		
		<div class="append-tx"></div>
		@foreach($transactions as $transaction)
			<?php if(!isset($transaction->jsonTx->Outpoints)) continue; ?>
			@foreach($transaction->jsonTx->Outpoints as $Outpoints)
				<?php
				if(strlen($transaction->jsonTx->Timestamp)==13){
					//$transaction->jsonTx->Timestamp =$transaction->jsonTx->Timestamp."000000"; 
				}?>

				<div class="listing-tx">
					<div class="ltx-icon"><img src="{{URL::asset('resources/images/transaction2.png')}}"/></div>
					<div class="new-dual-split">
						
						<div class="new-tx-class"><a href="{{route('wallet',$Outpoints->ScriptPublicKey)}}">{{$Outpoints->ScriptPublicKey}}</a> <div>received</div> <div class="new-tx-amount">{{number_format($Outpoints->Value,5)}} Met</div></div>
						
						<div class="time-ago">
							<abbr class="timeago testLongTerm" title="{{date('Y-m-d\TH:i:s\Z',($transaction->jsonTx->Timestamp/1000000000))}}">{{date('Y-m-d\TH:i:s\Z',($transaction->jsonTx->Timestamp/1000000000))}}</abbr>
						</div>
					</div>
				</div>
			@endforeach
		@endforeach
	</div>
	
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".timeago").timeago();
	});
</script>

@include('layout.footer')