<?php  
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");
include("includes/classes/Notification.php");



if (isset($_SESSION['username'])) {
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
}
else {
	header("Location: register.php");
}

?>
  <!--
Author: W3layouts
Author URL: https://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>Welcome to Swirlfeed </title> 
<link rel="stylesheet" type="text/css" href="assets/css/reset.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/bootbox.min.js"></script>
	<script src="assets/js/demo.js"></script>
	<script src="assets/js/jquery.jcrop.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
<link rel="stylesheet" href="assets/css/select_inline.css" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css" />
<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>
<link href="assets/css/category_style.css" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Pricing Tables Design ,Flat Pricing Tables Design ,Popup Pricing Tables Design,Clean Pricing Tables Design "./>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<!--webfonts-->
<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic' rel='stylesheet' type='text/css'>
<!--//webfonts-->
</head>
<body>


<!--start-pricing-tablel-->
<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/modernizr.custom.53451.js"></script> 

 <script>
						$(document).ready(function() {
						$('.popup-with-zoom-anim').magnificPopup({
							type: 'inline',
							fixedContentPos: false,
							fixedBgPos: true,
							overflowY: 'auto',
							closeBtnInside: true,
							preloader: false,
							midClick: true,
							removalDelay: 300,
							mainClass: 'my-mfp-zoom-in'
						});
																						
						});
				</script>
				<div class="logo" style=" text-align: center; margin-top: 50px;">
			<a href="index.php">&nbsp;  Swirlfeed! &nbsp;   </a>
		</div>							
 <div class="pricing-plans">
					 <div class="wrap" style="margin-top:120px;">
					 	<div class="price-head" >
					 		<h1 style="color: #404244;">Categories</h1>
					 	</div>
					 	 

						<div class="pricing-grids">
						<div class="pricing-grid1">
							<div class="price-value">
									<h2><a href="index.php?watch=1"> 2D</a></h2>

									<!-- <h5><span>$ 19.99</span><lable> / month</lable></h5>
									<div class="sale-box"> -->
							<!-- <span class="on_sale title_shop">NEW</span>
							</div> -->

							</div>
							<?php
							$post=new Post($con, $userLoggedIn);
								$total=$post->totalpost(1);
							?>
							<div class="price-bg">
							<ul>
								<li class="whyt"><a href="index.php?watch=1"><?php echo $total; ?> post </a></li>
								<!-- <li><a href="#">10 Domain Names</a></li>
								<li class="whyt"><a href="#">5 E-Mail Address </a></li>
								<li><a href="#">50GB Monthly Bandwidth </a></li>
								<li class="whyt"><a href="#">Fully Support</a></li> -->
							</ul>
							<!-- <div class="cart1">
								<a class="popup-with-zoom-anim" href="#small-dialog">Purchase</a>
							</div> -->
							</div>
						</div>
						<?php
							$post=new Post($con, $userLoggedIn);
								$total=$post->totalpost(2);
							?>
						<div class="pricing-grid2">
							<div class="price-value two">
								<h3><a href="index.php?watch=2">3D</a></h3>
								<!-- <h5><span>$ 29.99</span><lable> / month</lable></h5>
								<div class="sale-box two">
							<span class="on_sale title_shop">NEW</span>
							</div> -->

							</div>
							<div class="price-bg">
							<ul>
								<li class="whyt"><a href="index.php?watch=2"><?php echo $total; ?> post </a></li>
								<!-- <li><a href="#">20 Domain Names</a></li>
								<li class="whyt"><a href="#">10 E-Mail Address </a></li>
								<li><a href="#">100GB Monthly Bandwidth </a></li>
								<li class="whyt"><a href="#">Fully Support</a></li> -->
							</ul>
							<!-- <div class="cart2">
								<a class="popup-with-zoom-anim" href="#small-dialog">Purchase</a>
							</div> -->
							</div>
						</div>
						<?php
							$post=new Post($con, $userLoggedIn);
								$total=$post->totalpost(3);
							?>
						<div class="pricing-grid3">
							<div class="price-value three" >
								<h4><a href="index.php?watch=3">Graphic Design</a></h4>
								

							</div>
							<div class="price-bg">
							<ul>
								<li class="whyt"><a href="index.php?watch=3"><?php echo $total; ?> post </a></li>
								
							</ul>
							
							</div>
						</div>
						<?php
							$post=new Post($con, $userLoggedIn);
								$total=$post->totalpost(4);
							?>
						<div class="pricing-grid4" >
							<div class="price-value three">
								<h4><a href="index.php?watch=4">Motion Graphic</a></h4>
								

							</div>
							<div class="price-bg">
							<ul>
								<li class="whyt"><a href="index.php?watch=4"><?php echo $total; ?> post </a></li>
								
							</ul>
							
							</div>
						</div>
						<?php
							$post=new Post($con, $userLoggedIn);
								$total=$post->totalpost(5);
							?>
						<div class="pricing-grid5">
							<div class="price-value three">
								<h4><a href="index.php?watch=5">Multimedia</a></h4>
								

							</div>
							<div class="price-bg">
							<ul>
								<li class="whyt"><a href="index.php?watch=5"><?php echo $total; ?> post </a></li>
								
							</ul>
							
							</div>
						</div>
						<?php
							$post=new Post($con, $userLoggedIn);
								$total=$post->totalpost(6);
							?>
						<div class="pricing-grid6">
							<div class="price-value three" id="showall">
								<h4><a href="index.php?watch=allposts">Show All</a></h4>
								

							</div>
							<div class="price-bg">
							<ul>
								<li class="whyt"><a href="index.php?watch=allposts"><?php echo $total; ?> post </a></li>
								
							</ul>
							
							</div>
						</div>

							<div class="clear"> </div>
							<!--pop-up-grid-->
								
								<!--pop-up-grid-->
							</div>
						<div class="clear"> </div>

					</div>
				
				</div>
	
</body>
</html>
  <script src='https://preview.w3layouts.com/demos/flat_pricing_tables_design/web/js/jquery.magnific-popup.js'></script>
<script src='https://preview.w3layouts.com/demos/flat_pricing_tables_design/web/js/modernizr.custom.53451.js'></script>

  <script type="text/javascript">
  	$('.pricing-grid1').click(function()
  	{
  		
  		window.location.href="index.php?watch=1";

  	});
  	$('.pricing-grid2').click(function()
  	{
  		
  		window.location.href="index.php?watch=2";

  	});
  	$('.pricing-grid3').click(function()
  	{
  		
  		window.location.href="index.php?watch=3";

  	});
  	$('.pricing-grid4').click(function()
  	{
  		
  		window.location.href="index.php?watch=4";

  	});
  	$('.pricing-grid5').click(function()
  	{
  		
  		window.location.href="index.php?watch=5";

  	});
  	$('.pricing-grid6').click(function()
  	{
  		
  		window.location.href="index.php?watch=allposts";

  	});
  	
  </script>

</body>

</html>
