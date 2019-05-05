<?php  
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Post.php");

//$limit = 10; //Number of posts to be loaded per call

$userLoggedIn=$_POST['userLoggedIn'];
$res=mysqli_query($con,"select count(*) from comments where posted_by='$userLoggedIn' and correctans=1 ");
		$totalans=mysqli_fetch_array($res);
		$totalc=$totalans[0];


$str='<span id="mainans" style="float: right; display:inline; color: #4da2fb; font-size: 26px; font-weight: bold; margin-right: 27px; margin-top: 4px;">'.$totalc.' <br><i style="all:initial; margin-left: -5px;">Answer</i></span>';
echo $str;
?>
