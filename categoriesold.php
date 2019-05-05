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
<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Swirlfeed!</title>
	
	
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
	<style type="text/css">
		.categories{
			
	line-height: 35px;
    		background-color: #E6EAE8;
				}
		.cat-btn{
					background-color:#1999d1;
					height: 111px;

					color:#f2f4f7;
					font-size: 26px;



				}
				.cat-div{
							font-size: 28px;
							
							margin-top: 120px;
							color: #68696b;


						}
						.cat{
							text-align: center;
							margin-top: 30px;
						}
						.cat-show{
							background-color:#20aae5;
					height: 50px;
					width: 200px;

					color:white;
					font-size: 32px;
						}
						.show-div{
								margin-left: 451px;
								margin-top: 25px;
						}
						.logo a {
    font-family: 'Bellota-BoldItalic', sans-serif;
    margin-left: 10px;
    background-color: #1c99ce;
    font-size: 30px;

   
   text-shadow: #73B6E2 0.5px 0.5px 0px;
	color: #0e678e;
}


	</style>


</head>
<body class="categories">
	<div class="logo" style="text-align: center; margin-top: 50px;">
			<a href="index.php">&nbsp;  Swirlfeed! &nbsp;   </a>
		</div>
		<div class="cat-div" style="text-align: center;">Select Category</div>
		<div class="container">
		<div class="cat">
		<a href="index.php?watch=1" class="cat-anchor "><button class="cat-btn increase" >&nbsp;&nbsp;2D&nbsp;&nbsp;</button></a>
		<a href="index.php?watch=2" class="cat-anchor "><button class="cat-btn increase" >&nbsp;&nbsp;3D&nbsp;&nbsp;</button></a>
		<a href="index.php?watch=3" class="cat-anchor "><button class="cat-btn decrease" >Graphic   Design</button></a>
		<a href="index.php?watch=4" class="cat-anchor "><button class="cat-btn decrease" >Motion  Graphic</button></a>
		<a href="index.php?watch=5" class="cat-anchor "><button class="cat-btn increase" >Multimedia</button></a>
		<a href="index.php?watch=allposts" class="cat-anchor"><button class="cat-btn increase" >show all</button></a>
		</div>
		<!-- <div class="show-div">
		<a href="index.php?watch=allposts" class="cat-anchor"><button class="cat-btn" >show all</button></a> -->
		
		</div>
		</div>
</body>
</html>