
<!doctype html>
<html>
<head>
	<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0472VBY37F"></script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8142100124516782"
     crossorigin="anonymous"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-0472VBY37F');
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TSHMCJ87');</script>
<!-- End Google Tag Manager -->
	<!---------------FONTS-------------------->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<!---------------FONTS END-------------------->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	<link href="{{URL::asset('resources/css/app.css')}}" rel="stylesheet" >
	<script src="https://aishek.github.io/jquery-animateNumber/javascripts/jquery.animateNumber.js"></script>
	<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c="  crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<script src="https://timeago.yarp.com/jquery.timeago.js" type="text/javascript"></script>
	
	
	<meta charset="utf-8">
	<?php
	$path = "";
	if (Route::currentRouteName()=="tx"){
		$path = "- ".Request()->tx;
	}else if (Route::currentRouteName()=="block"){
		$path = "- ".Request()->hash;
	}else if (Route::currentRouteName()=="wallet"){
		$path = "- ".Request()->wallet;
	}
	?>

	<title>MetChain Explorer - {{ ucfirst(Route::currentRouteName()) }} {{$path}} </title>
	<script type="text/javascript">
		$(function(){
			jQuery(".timeago").timeago();
			

		})
	</script>
	
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TSHMCJ87"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	@if(Route::currentRouteName()!='home')
	<div class="top-nav">
		@include('module.search')
	</div>
	@endif
	<div class="extreme @if(Route::currentRouteName()!='home') inner-page @endif ">
		<header>
			<div class="logo">
				<img src="{{URL::asset('resources/images/logon.png')}}" class="flogo" />
			</div>
			<div class="navigation">
				<ul>
					<li><a href="{{route('home')}}">Home</a></li>
					
					<li><a href="{{route('GetNFTS')}}">NFT's</a></li>
					
					<li>Verified Wallets(Coming Soon)</li>
					<li><a href="{{route('richlist')}}">Top Wallets</a></li>
					
				</ul>
			</div>
		</header>
		
		
	</div>
	
