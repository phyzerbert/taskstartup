	<?php  
	require 'config/config.php';
	include("includes/classes/User.php");
	include("includes/classes/Post.php");
	include("includes/classes/Notification.php");

	$post_id="";

	if (isset($_SESSION['username'])) {
		$userLoggedIn = $_SESSION['username'];
		$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
		$user = mysqli_fetch_array($user_details_query);
	}
	else {
		header("Location: register.php");
	}

	?>

<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/css/reset.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/select_inline.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
<body>

	<style type="text/css">
	* {
		font-size: 12px;
		font-family: Arial, Helvetica, Sans-serif;
	}
	/*newcode*/
.twovid video{
	 
	width: 200px!important;
	height: 150px!important;
    
    
    margin: 5px auto;
    margin-left: 12px;
    border: 3px solid black;
}
.ctwolin{
	all:unset;
	
    
    
    margin: 5px auto;
    margin-left: 12px;
    
}
.ctwolin >
.ctwovid{
	all:unset;
	display: inline;
}
.oloi img{
	float: left;
	width: 200px!important;
	height: 150px!important;
}
.oloi iframe{
	margin-left: 10px;
	margin-top: 5px;
	width: 200px!important;
	height: 150px!important;

}
.olov video{
	float: left;
	margin-left: 1px;
	width: 200px!important;
	height: 150px!important;
}
.olov iframe{
	margin-left: 209px!important;
	margin-top: 5px;
	width: 200px!important;
	height: 150px!important;

}
.oneimg img {
	height: 150px;
    width: 200px;
    display: block;
    margin: 5px auto;

    border: 3px solid black;
}
.oneimg{
	margin-left: 28px;
}
.onevid{
	margin-right:382px;
}
.onevid video{
	height: 150px;
    width: 200px;
    
    display: block;
    margin: 5px auto;
    border: 3px solid black;
}

.twoimg img{
	width: 200px!important;
	height: 150px!important;
}
.oiov img{
	width: 200px!important;
	height: 150px!important;
}
.oiov video{
	width: 200px!important;
	height: 150px!important;
}
.cremaining{
	display: inline!important;
	max-width: 260px!important;
	min-height: 150px!important;
	text-align: center!important;
  color: #285296!important;
  background-color: #e6eae8!important;
  float: right;
  padding-top: 66px;
  margin-right:112px;
  text-align: center;
 text-decoration:underline!important;
  font-size: 26px!important;
}
#stchecked:hover
{
	cursor: pointer;
	color:grey!important;

}
#stunchecked:hover{
	cursor: pointer;
	color:#f7d70b!important;

}
#cat-file,#cat-video{
	display: none;
}
#cat-filesub,#cat-videosub{
	display: none;
}
.post-icons{
	margin-left: 40px;
	display: inline-block;
	
	
}
#img-upload ,#mp4-upload{
width: 40px;
height: 40px;	
}
#mp4-upload{
	margin-left: 10px;
}
/*#img-upload ,#mp4-upload:hover{

cursor: pointer;
}*/
#img-uploadsub ,#mp4-uploadsub{
width: 40px;
height: 40px;	
}
#mp4-uploadsub{
	margin-left: 10px;
}
#img-uploadsub ,#mp4-uploadsub:hover{

cursor: pointer;
}
/*.image-upload > input
{
    display: none;
}
.image-upload img
{
    width: 80px;
    cursor: pointer;
}*/
.post-icons{
	padding: 5px;
	
	border-style: solid;
  border-color: #818285;
}
.cbtn{
 background-color:white!important;
    background-repeat:no-repeat;
    border: none;
    cursor:pointer;
    overflow: hidden;
    outline:none;
}
.newbtn{
background-color: white!important;
    background-repeat:no-repeat;
    /*border: none;*/
    cursor:pointer;
    overflow: hidden;
    outline:none;
    height: 35px;
}
#t1{
	margin-left: 4px;
	border-color: #D3D3D3;
    width: 100%;
    height: 35px;
    border-radius: 5px;
    color: #616060;
    font-size: 14px;
    margin: 3px 3px 3px 5px;
}
.newbtn,#t1{
	margin: 0px !important;
}
hr{
	 
    border-bottom: 1px dashed black!important;
    color: black!important;
    height: 1px!important;
}
	</style>


	<script>
		function toggle() {
			var element = document.getElementById("comment_section");
			
			if(element.style.display == "block")
			{ 
				element.style.display = "none";
			}
			else
			{ 
				element.style.display = "block";
				
				
				 
			}
		}

	</script>

	<?php  
	//Get id of post
	if(isset($_GET['post_id'])) {
		$post_id = $_GET['post_id'];
	}

	$user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
	$row = mysqli_fetch_array($user_query);

	$posted_to = $row['added_by'];
	$user_to = $row['user_to'];


	if(isset($_POST['postComment' . $post_id])) {
		 
		$post_body = $_POST['post_body'];
		$post_body = mysqli_escape_string($con, $post_body);
		$date_time_now = date("Y-m-d H:i:s");
		//newcode
		$uploadOk = 1;
	$uploadOkv = 1;
	$errorsub=1;
	
	$errorMessage = "";
	$post_category="";
	$links=$_POST['ytlinks'];
	$parentcomment=$_POST['parentcomment'];

	if(strpos($links,",")==false && $links!="")
	{
		
		if(strlen($links)>52)
		{
			$errorsub=0;
			$errorMessage="Please Add Space between youtube links";
		}
		
	}
	else{
		if($links!="")
		{
		$linkexplode=explode(",",$links);
		foreach ($linkexplode as $linkexplodee) {
			
			if(strlen($linkexplodee)>52)
		{
			$errorsub=0;
			$errorMessage="Please Add Space between youtube links";
		}
		}
	}
	}
	$img_array=array();
	$img_count=0;
	//$post_category=$_POST['pcategory'];
	
	$video_array=array();
	$video_count=0;
	
	for($i=0;$i<count($_FILES['multivideo']['name']);$i++)
	{
		$videoname=$_FILES['multivideo']['name'][$i];

		if($videoname!="")
		{

			$targetDirv="assets/videos/";
			$videoname=$targetDirv.uniqid().basename($videoname);
			//var_dump($videoname);
			$videotype=pathinfo($videoname,PATHINFO_EXTENSION);

				if($videotype != "mp4")
				{

					$errorMessage="Sorry only mp4 video files are allowed";
					$uploadOk=0;
				}
				if($uploadOkv==1)
				{
					if(move_uploaded_file($_FILES['multivideo']['tmp_name'][$i], $videoname))
					{
						
						$video_count++;
						//echo '<script>alert($video_count)</script>';
						$video_array[$i]=$videoname;
					}
					else{
						
							$errorMessage="Error!video didnot upload";
							$uploadOkv=0;
				}

				}
				

		}
	}
	
	
	for ($i=0; $i <count($_FILES['fileToUpload']['name']) ; $i++) { 
		# code...
	
	$imageName = $_FILES['fileToUpload']['name'][$i];
	if($imageName != "") {
		$targetDir = "assets/images/posts/";
		$imageName = $targetDir . uniqid() . basename($imageName);
		$imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

		if($_FILES['fileToUpload']['size'][$i] > 10000000) {
			$errorMessage = "Sorry your file is too large";
			$uploadOk = 0;
		}

		if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg") {
			$errorMessage = "Sorry, only jpeg, jpg and png files are allowed";
			$uploadOk = 0;
		}

		if($uploadOk) {
			if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'][$i], $imageName)) {
				$img_count++;
				$img_array[$i]=$imageName;

			}
			else {
				//image did not upload
				$uploadOk = 0;
			}
		}

	}
}
if($_POST['post_text']=="") {
		$errorsub=0;
		$errorMessage="Text is compulsory with the comment";
	}
	$imgimplode=implode(",", $img_array);
	$videoimplode=implode(",",$video_array);
	
	if($_POST['post_text']!="" && $errorsub==1) {
		$post_body=$_POST['post_text'];
		// $post = new Post($con, $userLoggedIn);
		// $post->submitPost($_POST['post_text'], 'none', $imgimplode,$post_category,$videoimplode,$links);
		$insert_post = mysqli_query($con, "INSERT INTO comments(post_body,posted_by,posted_to,date_added,removed,post_id,parent_comment,images,videos,yt_links) VALUES ( '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id','$parentcomment','$imgimplode','$videoimplode','$links')");
	}

	if($errorsub==0)
	{
		?>
		
	<?php
}
		//endofnewcode
		



// insert notification
		if($posted_to != $userLoggedIn) {
			$notification = new Notification($con, $userLoggedIn);
			$notification->insertNotification($post_id, $posted_to, "comment");
		}
		
		if($user_to != 'none' && $user_to != $userLoggedIn) {
			$notification = new Notification($con, $userLoggedIn);
			$notification->insertNotification($post_id, $user_to, "profile_comment");
		}


		$get_commenters = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id'");
		$notified_users = array();
		while($row = mysqli_fetch_array($get_commenters)) {

			if($row['posted_by'] != $posted_to && $row['posted_by'] != $user_to 
				&& $row['posted_by'] != $userLoggedIn && !in_array($row['posted_by'], $notified_users)) {

				$notification = new Notification($con, $userLoggedIn);
				$notification->insertNotification($post_id, $row['posted_by'], "comment_non_owner");

				array_push($notified_users, $row['posted_by']);
			}

		}






		//echo "<p>Comment Posted! </p>";
	}
	?>
	
		<div class="terrorcomment<?php echo $post_id; ?>" style="display: none;">
				</div>
				<div id="topcomment<?php echo $post_id; ?>"  style="text-align:center; display: none;" class='alert alert-danger alert-dismissible'>
					<a href="####" class="close"  onclick="closecomment('topcomment<?php echo $post_id; ?>')">&times;</a>
				Add some text to describe the task ... close it and try again!

				</div>
			
			
	<div class="mainform">
	<form id="comment_form" class="commentf<?php echo $post_id; ?>"  enctype="multipart/form-data" onsubmit="mainform('<?php echo $post_id; ?>',event)">
		<div class="post-icons" style="margin-left: 420px;">
				<img height="20" id="img-upload" src="assets/icons/multi img icon1.png"/>

				<img height="20" id="mp4-upload" src="assets/icons/multi mp4 icon.png"/>
			</div>
			<label id="imagescount" class="imgcountr<?php echo $post_id; ?>"></label>
			<label id="videoscount" class="vidcountr<?php echo $post_id; ?>"></label>
			<input id="cat-file" class="imgfiler<?php echo $post_id; ?>" type="file" name="fileToUpload[]" multiple="multiple" accept=".png, .jpg, .jpeg"  onchange="totalimg()"
      >
			<input id="cat-video" class="vidfiler<?php echo $post_id; ?>" type="file" name="multivideo[]" multiple="multiple" accept=".mp4"  onchange="totalvid()">
		<textarea id="post_text" class="textar<?php echo $post_id; ?>" name="post_body"></textarea>

		
			

			
			<input type="hidden" name="ytlinks" class="linksr<?php echo $post_id; ?>" id="links" value="">
			<input type="hidden" class="parentcom<?php echo $post_id; ?>" name="parentcomment" id="parentc"  value="0">
			<input type="hidden" class="postide<?php echo $post_id; ?>" name="postid" value="<?php echo $post_id; ?>">
			<input type="hidden" name="post_text" class="newtextar<?php echo $post_id; ?>" id="post_textfield" value="">
		<input type="submit" id="post_button" name="postComment<?php echo $post_id; ?>" value="Post">
	</form>
</div>

	<!-- Load comments -->
	<?php  
	$get_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id='$post_id' ORDER BY id ASC");
	$get_postforc = mysqli_query($con, "SELECT * FROM posts WHERE id='$post_id'");
	$resforc=mysqli_fetch_array($get_postforc);
	$commpost=$resforc["added_by"];
	$count = mysqli_num_rows($get_comments);

	if($count != 0) {

		while($comment = mysqli_fetch_array($get_comments)) {
			$id = $comment['id'];
			$comment_body = $comment['post_body'];
			$posted_to = $comment['posted_to'];
			$posted_by = $comment['posted_by'];
			$date_added = $comment['date_added'];
			$removed = $comment['removed'];
			$imagePath = $comment['images'];
				$videoPath = $comment['videos'];
				$linkPath = $comment['yt_links'];
				$parent_comment = $comment['parent_comment'];
				$correctans=$comment['correctans'];
				$postid=$comment['post_id'];
				$ca="";
				if($posted_by!=$userLoggedIn)
				{
				if($commpost==$userLoggedIn)
				{
					if($correctans==0)
					{
					$ca="<span id='stunchecked'  class='fa fa-star st$id' onclick='updateans($id,0)' style='color:grey; font-size: 20px; '></span>";
				}
				elseif($correctans==1){
					$ca="<span id='stchecked' class='fa fa-star st$id' onclick='updateans($id,1)' style='color:#f7d70b;  background-color:#2baf53; font-size: 21px; padding-left:2px;'>&nbsp;<i style='color:white;  background-color:#2f95d2; font-size: 19px; height:21px;float:right;'>&nbsp;Answer&nbsp;</i></span>
					";
				}
			}
			else{
				if($correctans==1){
					$ca="<span id='stcheckedz' class='fa fa-star st$id'  style='color:#f7d70b;  background-color:#2baf53; font-size: 21px; padding-left:2px;'>&nbsp;<i style='color:white;  background-color:#2f95d2; font-size: 19px; height:21px;float:right;'>&nbsp;Answer&nbsp;</i></span>
					";
				}
			}
		}
			//Timeframe
			$date_time_now = date("Y-m-d H:i:s");
			$start_date = new DateTime($date_added); //Time of post
			$end_date = new DateTime($date_time_now); //Current time
			$interval = $start_date->diff($end_date); //Difference between dates 
			if($interval->y >= 1) {
				if($interval == 1)
					$time_message = $interval->y . " year ago"; //1 year ago
				else 
					$time_message = $interval->y . " years ago"; //1+ year ago
			}
			else if ($interval->m >= 1) {
				if($interval->d == 0) {
					$days = " ago";
				}
				else if($interval->d == 1) {
					$days = $interval->d . " day ago";
				}
				else {
					$days = $interval->d . " days ago";
				}


				if($interval->m == 1) {
					$time_message = $interval->m . " month". $days;
				}
				else {
					$time_message = $interval->m . " months". $days;
				}

			}
			else if($interval->d >= 1) {
				if($interval->d == 1) {
					$time_message = "Yesterday";
				}
				else {
					$time_message = $interval->d . " days ago";
				}
			}
			else if($interval->h >= 1) {
				if($interval->h == 1) {
					$time_message = $interval->h . " hour ago";
				}
				else {
					$time_message = $interval->h . " hours ago";
				}
			}
			else if($interval->i >= 1) {
				if($interval->i == 1) {
					$time_message = $interval->i . " minute ago";
				}
				else {
					$time_message = $interval->i . " minutes ago";
				}
			}
			else {
				if($interval->s < 30) {
					$time_message = "Just now";
				}
				else {
					$time_message = $interval->s . " seconds ago";
				}
			}
			//newcode
			if($imagePath != "" || $videoPath!="" ||$linkPath!="") {
						$imageDiv="";
						$combined="";
					 	if($imagePath!="")
					 	{
					 		if($combined=="")
					 		{
					 			$combined=$imagePath;
					 		}
					 		else{
					 	$combined=$combined.",".$imagePath;
					 }
					 }
					if($videoPath!="")
					 	{
					 		if($combined=="")
					 		{
					 			$combined=$videoPath;
					 		}
					 		else{
					 	$combined=$combined.",".$videoPath;
					 }
					 	
					 }
					 if($linkPath!="")
					 	{
					 		if($combined=="")
					 		{
					 			$combined=$linkPath;
					 		}
					 		else{
					 	$combined=$combined.",".$linkPath;
					 }
					 	
					 }

						$imgexplode=explode(",", $combined);
						$linkc=$videoc=$imgc=0;
						$additionalclass="";
						$singleadditionalclass="";

						
						
						$totalimg=count($imgexplode);
						for ($i=0; $i<count($imgexplode); $i++) { 
							$imageFileType=pathinfo($imgexplode[$i],PATHINFO_EXTENSION);
							if($imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "jpg") {
								$imgc++;
							}
							elseif($imageFileType=="mp4"){
								$videoc++;
							}
							else{
								$linkc++;
								
							}


							if($i==1)
							{
								break;
							}
						}
						if($videoc==1)
						{
							$singleadditionalclass="onevid";
						}
						if($imgc==1)
						{
							$singleadditionalclass="oneimg";
						}
						if($imgc==2)
						{
							$additionalclass="twoimg";
						}
						if($videoc==2)
						{
							$additionalclass="twovid";
						}
						if($linkc==2)
						{
							$additionalclass="twolin";
						}
						if($linkc==1 && $videoc==0 && $imgc==1){
							$additionalclass="oloi";
						}
						if($linkc==1 && $videoc==1 && $imgc==0){
							$additionalclass="olov";
						}
						if($linkc==0 && $videoc==1 && $imgc==1){
							$additionalclass="oiov";
						}
						$lastimage=0;
						foreach ($imgexplode as $key => $imageexplode) {

							$lastimage=$key+1;
							$data="";
							$imageFileType=pathinfo($imageexplode,PATHINFO_EXTENSION);
							if($imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "jpg") {
								$data="<img src='$imageexplode' onclick='getimages($id,$key)'>";
							}
							elseif($imageFileType=="mp4"){
								$data="<video  controls onclick='getimages($id,$key)'><source src='$imageexplode'  type='video/mp4'></video>";
							}
							else{
								$embed=str_replace("/watch?v=","/embed/", $imageexplode);
								if($linkc==2)
								{
								$data="<iframe width='200' height='150' allowfullscreen  style='display:inline;' '
								src='$embed' >
									</iframe>";	
								}
								if($linkc==1 && $videoc==0 && $imgc==0)
								{
									$data="<iframe width='200' height='150'  allowfullscreen onclick='getimages($id,$key)' style='margin-left:30px;'
								src='$embed' >
									</iframe>";
								}
								if($linkc==1 && $videoc==0 && $imgc==1)
								{
									$data="<iframe width='250' height='162' style='display:inline;'  allowfullscreen onclick='getimages($id,$key)'
								src='$embed' >
									</iframe>";
								}
								if($linkc==1 && $videoc==1 && $imgc==0)
								{
									$data="<iframe width='250' height='162' style='display:inline;'  allowfullscreen onclick='getimages($id,$key)'
								src='$embed' >
									</iframe>";
								}
								
							}
						if($key==0)
						{
							if($totalimg==1 )
							{
								$imageDiv= "<div class='cpostedImage $singleadditionalclass' >
										$data
									</div>";
								}
									elseif($totalimg==2 || $totalimg>2){
										$imageDiv= "<div class='postedImagetwo $additionalclass'>
										$data
									</div>";
									}
									// elseif($totalimg==3){
									// 	$imageDiv= "<div class='postedImagethree'>
									// 	$data
									// </div>";
									// }
									// else{
									// 	$imageDiv= "<div class='postedImagefour'>
									// 	$data
									// </div>";
									// }

								}
								else{
									$remaining_images=$totalimg-$lastimage;

									if($totalimg==2){
										$imageDiv .= "<div class='postedImagetwo $additionalclass' >
										$data
									</div>
									
										";
									break;
									}
									if($totalimg>2){
										$moreimg=$key+1;
										$imageDiv .= "<div class='postedImagetwo $additionalclass' >
										$data
									</div>
									<div class='cremaining' onclick='getimages($id,$moreimg)'>
											+ $remaining_images 
											
											
										</div>
										";
									break;
									
									}
									
									
								}
					}
				}
					else {
						$imageDiv = "";
					}
			//endofnewcode

			$user_obj = new User($con, $posted_by);
			if($parent_comment==0)
			{

			?>

			<div class="comment_section com<?php echo $id; ?>">
				<a href="<?php echo $posted_by?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic();?>" title="<?php echo $posted_by; ?>" style="float:left;" height="30"></a>
				<a href="<?php echo $posted_by?>" target="_parent"> <b> <?php echo $user_obj->getFirstAndLastName(); ?> </b></a>
				&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ca . "<br>". $comment_body . "<br>".$imageDiv; ?><br><button class="btn btn-sm btn-primary" onclick="subcomment('<?php echo $id ?>','<?php echo $postid ?>');">Reply</button>

			
			</div>
			<div class="errorcomment<?php echo $id; ?>" style="display: none;">
				</div>
				<div id="subucomment<?php echo $id; ?>"  style="text-align:center; display: none;" class='alert alert-danger alert-dismissible'>
					<a href="####" class="close"  onclick="closecomment('subucomment<?php echo $id; ?>')">&times;</a>
				Add some text to describe the task ... close it and try again!

				</div>
				<form style="margin-top: 6px; display: none;"  id="comment_form<?php echo $id; ?>" name="postComment<?php echo $post_id; ?>" method="POST" enctype="multipart/form-data" onsubmit="subcom('<?php echo $id; ?>',event);" >
			    <div class="row">
			        
			        <div class="col-xs-10">
			            <div class="input-group">
			                <textarea class="form-control"  id="t1<?php echo $id; ?>" name="post_body"></textarea>
			                <span class="input-group-addon  newbtn" onclick="imgicon('<?php echo $id; ?>')"><i class="fas fa-camera  fa-lg"></i></span>
			                <span class="input-group-addon newbtn" onclick="vidicon('<?php echo $id; ?>')"><i class="fas fa-video fa-lg"></i></span>
			                
			            </div>
			        </div>
		        <div class="col-xs-2">
		        	<input type="submit" id="post_buttonsub<?php echo $id; ?>" name="postComment<?php echo $post_id; ?>" value="Post" style="border: none!important; background-color: #20AAE5!important;color: #156588!important;border-radius: 5px!important;width: 94%!important;height: 48px!important;margin-top: 3px!important;font-family: 'Bellota-BoldItalic', sans-serif;text-shadow: #73B6E2 0.5px 0.5px 0px!important;margin-left: -22px!important;" >
		        </div>
        
    			</div>
    
		<!-- <div class="post-icons" style="margin-left: 420px;">
				<img height="20" id="img-upload" src="assets/icons/multi img icon1.png"/>

				<img height="20" id="mp4-upload" src="assets/icons/multi mp4 icon.png"/>
			</div> -->
			<label  id="imagescountsub<?php echo $id; ?>"></label>
			<label  id="videoscountsub<?php echo $id; ?>"></label>
			<input id="cat-filesub<?php echo $id; ?>" type="file" name="fileToUpload[]" multiple="multiple" accept=".png, .jpg, .jpeg"  onchange="totalimgsub('<?php echo $id; ?>')"
       style="display: none;">
			<input id="cat-videosub<?php echo $id; ?>" type="file" name="multivideo[]" multiple="multiple" accept=".mp4"  onchange="totalvidsub('<?php echo $id; ?>')" style="display: none;">
		<!-- <textarea id="post_text" name="post_body"></textarea> -->

		
			

			
			<input type="hidden" name="ytlinks" id="linksub<?php echo $id; ?>" value="">
			<input type="hidden" name="parentcomment" id="parentc<?php echo $id; ?>"  value="<?php echo $id; ?>">
			<input type="hidden" class="postidee<?php echo $id; ?>" name="postid" value="<?php echo $post_id; ?>">
			<input type="hidden" name="post_text" id="post_textfieldsub<?php echo $id; ?>" value="">

	</form>

 
				<hr/>
			</div>
			<?php
			$que=mysqli_query($con,"select * from comments where parent_comment='$id' order by id");
			
			//var_dump("select * from comments where parent_comment='$id' order by id");
			if(mysqli_num_rows($que)>0)
			{
				//$res=mysqli_fetch_array($que);
				?>
				<div class="replydiv<?php echo $id; ?>">
				<?php
				while ($subc=mysqli_fetch_array($que)) {
							$id = $subc['id'];
							//var_dump($id);
					$comment_body = $subc['post_body'];
					$posted_to = $subc['posted_to'];
					$posted_by = $subc['posted_by'];
					$date_added = $subc['date_added'];
					$removed = $subc['removed'];
					$imagePath = $subc['images'];
						$videoPath = $subc['videos'];
						$linkPath = $subc['yt_links'];
						$parent_comment = $subc['parent_comment'];
						$correctans=$subc['correctans'];
						$postid=$subc['post_id'];
						$ca="";
						if($posted_by!=$userLoggedIn)
				{
								if($commpost==$userLoggedIn)
								{
									if($correctans==0)
									{
									$ca="<span id='stunchecked'  class='fa fa-star st$id' onclick='updateans($id,0)' style='color:grey; font-size: 20px; '></span>";
								}
								elseif($correctans==1){
									$ca="<span id='stchecked' class='fa fa-star st$id' onclick='updateans($id,1)' style='color:#f7d70b;  background-color:#2baf53; font-size: 21px; padding-left:2px;'>&nbsp;<i style='color:white;  background-color:#2f95d2; font-size: 19px; height:21px;float:right;'>&nbsp;Answer&nbsp;</i></span>
									";
								}
							}
							else{
				if($correctans==1){
					$ca="<span id='stcheckedz' class='fa fa-star st$id'  style='color:#f7d70b;  background-color:#2baf53; font-size: 21px; padding-left:2px;'>&nbsp;<i style='color:white;  background-color:#2f95d2; font-size: 19px; height:21px;float:right;'>&nbsp;Answer&nbsp;</i></span>
					";
				}
			}
		}	

								//Timeframe
								$date_time_now = date("Y-m-d H:i:s");
								$start_date = new DateTime($date_added); //Time of post
								$end_date = new DateTime($date_time_now); //Current time
								$interval = $start_date->diff($end_date); //Difference between dates 
								if($interval->y >= 1) {
									if($interval == 1)
										$time_message = $interval->y . " year ago"; //1 year ago
									else 
										$time_message = $interval->y . " years ago"; //1+ year ago
								}
								else if ($interval->m >= 1) {
									if($interval->d == 0) {
										$days = " ago";
									}
									else if($interval->d == 1) {
										$days = $interval->d . " day ago";
									}
									else {
										$days = $interval->d . " days ago";
									}


									if($interval->m == 1) {
										$time_message = $interval->m . " month". $days;
									}
									else {
										$time_message = $interval->m . " months". $days;
									}

								}
								else if($interval->d >= 1) {
									if($interval->d == 1) {
										$time_message = "Yesterday";
									}
									else {
										$time_message = $interval->d . " days ago";
									}
								}
								else if($interval->h >= 1) {
									if($interval->h == 1) {
										$time_message = $interval->h . " hour ago";
									}
									else {
										$time_message = $interval->h . " hours ago";
									}
								}
								else if($interval->i >= 1) {
									if($interval->i == 1) {
										$time_message = $interval->i . " minute ago";
									}
									else {
										$time_message = $interval->i . " minutes ago";
									}
								}
								else {
									if($interval->s < 30) {
										$time_message = "Just now";
									}
									else {
										$time_message = $interval->s . " seconds ago";
									}
								}
													//newcode
								if($imagePath != "" || $videoPath!="" ||$linkPath!="") {
											$imageDiv="";
											$combined="";
										 	if($imagePath!="")
										 	{
										 		if($combined=="")
										 		{
										 			$combined=$imagePath;
										 		}
										 		else{
										 	$combined=$combined.",".$imagePath;
										 }
										 }
										if($videoPath!="")
										 	{
										 		if($combined=="")
										 		{
										 			$combined=$videoPath;
										 		}
										 		else{
										 	$combined=$combined.",".$videoPath;
										 }
										 	
										 }
										 if($linkPath!="")
										 	{
										 		if($combined=="")
										 		{
										 			$combined=$linkPath;
										 		}
										 		else{
										 	$combined=$combined.",".$linkPath;
										 }
										 	
										 }

											$imgexplode=explode(",", $combined);
											$linkc=$videoc=$imgc=0;
											$additionalclass="";
											$singleadditionalclass="";

											
											
											$totalimg=count($imgexplode);
											for ($i=0; $i<count($imgexplode); $i++) { 
												$imageFileType=pathinfo($imgexplode[$i],PATHINFO_EXTENSION);
												if($imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "jpg") {
													$imgc++;
												}
												elseif($imageFileType=="mp4"){
													$videoc++;
												}
												else{
													$linkc++;
													
												}


												if($i==1)
												{
													break;
												}
											}
											if($videoc==1)
											{
												$singleadditionalclass="onevid";
											}
											if($imgc==1)
											{
												$singleadditionalclass="oneimg";
											}
											if($imgc==2)
											{
												$additionalclass="twoimg";
											}
											if($videoc==2)
											{
												$additionalclass="twovid";
											}
											if($linkc==2)
											{
												$additionalclass="twolin";
											}
											if($linkc==1 && $videoc==0 && $imgc==1){
												$additionalclass="oloi";
											}
											if($linkc==1 && $videoc==1 && $imgc==0){
												$additionalclass="olov";
											}
											if($linkc==0 && $videoc==1 && $imgc==1){
												$additionalclass="oiov";
											}
											$lastimage=0;
											foreach ($imgexplode as $key => $imageexplode) {

												$lastimage=$key+1;
												$data="";
												$imageFileType=pathinfo($imageexplode,PATHINFO_EXTENSION);
												if($imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "jpg") {
													$data="<img src='$imageexplode' onclick='getimages($id,$key)'>";
												}
												elseif($imageFileType=="mp4"){
													$data="<video  controls onclick='getimages($id,$key)'><source src='$imageexplode'  type='video/mp4'></video>";
												}
												else{
													$embed=str_replace("/watch?v=","/embed/", $imageexplode);
													if($linkc==2)
													{
													$data="<iframe width='200' height='150' allowfullscreen  style='display:inline;' '
													src='$embed' >
														</iframe>";	
													}
													if($linkc==1 && $videoc==0 && $imgc==0)
													{
														$data="<iframe width='200' height='150'  allowfullscreen onclick='getimages($id,$key)' style='margin-left:30px;'
													src='$embed' >
														</iframe>";
													}
													if($linkc==1 && $videoc==0 && $imgc==1)
													{
														$data="<iframe width='250' height='162' style='display:inline;'  allowfullscreen onclick='getimages($id,$key)'
													src='$embed' >
														</iframe>";
													}
													if($linkc==1 && $videoc==1 && $imgc==0)
													{
														$data="<iframe width='250' height='162' style='display:inline;'  allowfullscreen onclick='getimages($id,$key)'
													src='$embed' >
														</iframe>";
													}
													
												}
											if($key==0)
											{
												if($totalimg==1 )
												{
													$imageDiv= "<div class='cpostedImage $singleadditionalclass' >
															$data
														</div>";
													}
														elseif($totalimg==2 || $totalimg>2){
															$imageDiv= "<div class='postedImagetwo $additionalclass'>
															$data
														</div>";
														}
														// elseif($totalimg==3){
														// 	$imageDiv= "<div class='postedImagethree'>
														// 	$data
														// </div>";
														// }
														// else{
														// 	$imageDiv= "<div class='postedImagefour'>
														// 	$data
														// </div>";
														// }

													}
													else{
														$remaining_images=$totalimg-$lastimage;

														if($totalimg==2){
															$imageDiv .= "<div class='postedImagetwo $additionalclass' >
															$data
														</div>
														
															";
														break;
														}
														if($totalimg>2){
															$moreimg=$key+1;
															$imageDiv .= "<div class='postedImagetwo $additionalclass' >
															$data
														</div>
														<div class='cremaining' onclick='getimages($id,$moreimg)'>
																+ $remaining_images 
																
																
															</div>
															";
														break;
														
														}
														
														
													}
										}
									}
										else {
											$imageDiv = "";
										}
								//endofnewcode
									$user_obj = new User($con, $posted_by);

									?>
									
									<div style="margin-left: 22px;" class="comment_section com<?php echo $id; ?>">
				<a href="<?php echo $posted_by?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic();?>" title="<?php echo $posted_by; ?>" style="float:left;" height="30"></a>
				<a href="<?php echo $posted_by?>" target="_parent"> <b> <?php echo $user_obj->getFirstAndLastName(); ?> </b></a>
				&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ca . "<br>". $comment_body . "<br>".$imageDiv; ?>
				

 
				<hr>
			</div>
		





















			<?php

						}
						?>

		
				</div>
				<?php
			}
		}
	}
	echo "<div class='uperr$post_id' style='display:none; text-align:center;'><br><br>No Comments to Show!</div>";

}

	else {
		echo "<div class='uperr$post_id' style='display:block; text-align:center;'><br><br>No Comments to Show!</div>";
	}

	?>
<div class="newcomment<?php echo $post_id; ?>"></div>





</body>
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
<script type="text/javascript">
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';
    	function getimages(name,current)
    	{
    		//alert('i am in');
    		//alert(name);
    		//alert(current);
    		$.ajax({

			url: "includes/handlers/ajax_load_imagesc.php",
			type: "POST",
			data: "userLoggedIn=" + userLoggedIn + "&postimage=" +name,
			cache:false,

			success: function(data) {
				$('#loading').hide();
				//alert(data);
				
				  $('#sliderul',window.parent.document).html(data);

				  parent.$('.a'+current).trigger('click');
				
			}
		});
    	}
    	function updateans(com,stat)
    	{
    		$.ajax({
    			url:"includes/handlers/ajax_correctans.php",
    			type:"POST",
    			data:"userLoggedIn=" + userLoggedIn + "&com="+ com +"&stat=" +stat,
    			cache:false,
    			success:function(data)
    			{
    				
    				$('.st'+com).replaceWith(data);
    				
    			}
    		});
    		upmain();
    	}
    	function upmain()
    	{
    		$.ajax({
    			url:"includes/handlers/ajax_upcorrect.php",
    			type:"POST",
    			data:"userLoggedIn=" + userLoggedIn,
    			cache:false,
    			success:function(data)
    			{
    				
    				parent.$('#mainans').replaceWith(data);
    				
    			}
    		});
    	}
    	function subcomment(com,post)
    	{
    		
    		$('#comment_form'+com).toggle();

    	}
    </script>

    <script type="text/javascript">
    	var totalimages=0;
    	var totalvideos=0;
    	$("#img-upload").click(function(){
    		
    		$("#cat-file")[0].click();
    	});
    	
    	$("#mp4-upload").click(function(){
    		
    		$("#cat-video")[0].click();
    	});
    	
    	function totalimg()
    	{
    		 totalimages = $("#cat-file")[0].files;
    		 var word="image";
    		 if(totalimages.length>1)
    		 {
    		 	word="images";
    		 }
    		$("#imagescount").html(totalimages.length+" "+word+" ");
    		totalvid();
    	}
    	
    	function totalvid()
    	{

    		 totalvideos = $("#cat-video")[0].files;
    		 
    // 		 for (var i = 0; i < this.totalvideos.length; i++)
    // {
    //     alert(this.totalvideos[i].name);
    //     alert(this.totalvideos.item(i).name); // alternatively
    // }
    		if(totalvideos.length!=0)
    		{
    		 var vword="video";
    		 if(totalvideos.length>1)
    		 {
    		 	vword="videos";
    		 }
    		 if(totalimages.length>0)
    		 {
    		$("#videoscount").html(" and "+totalvideos.length+" "+vword+" ");
    		}
    		else if(totalimages==0){
    			$("#videoscount").html(totalvideos.length+" "+vword+" ");
    		}
    	}
    	}
    	
    </script>
    <script type="text/javascript">
    	function mainform(postii,e)
    	{
    		e.preventDefault();
    		//$("#post_button").attr('type','button');
    		var text = $('#post_text').val();
    var source = (text || '').toString();
    var urlArray = [];
    var url;
    var matchArray;

    // Regular expression to find FTP, HTTP(S) and email URLs.
    var regexToken = /(((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+)/g;

if(source.match(regexToken))
{

    // Iterate through any URLs in the text.
    while( (matchArray = regexToken.exec( source )) !== null )
    {
        var token = matchArray[0];
        urlArray.push( token );
    }

    //return urlArray;
    	var links=urlArray.join(',');
       $('#links').val(links);
       var ex=links.split(',');
       $.each(ex, function( k, v ) {
  			source=myReplaceMethod(source,v,"");
});
       
       $('#post_textfield').val(source);

   }
   else{
   	$('#post_textfield').val($('#post_text').val());
   	
   }
   if($('#post_textfield').val()=="")
   {
   		$('#topcomment'+postii).css('display','block');
   }
   else{
   var form = $('.commentf'+postii)[0]; // You need to use standard javascript object here
var formData = new FormData(form);
   
   			$.ajax({
   				url:'livecomment.php',
   				type:'post',
   				data: formData,
   				contentType:false,
   				processData:false,
   				dataType:'json',
   				
   				success:function(result)
   				{
   					//console.log(result[1]);
   					if(result[0]=="fail")
   					{
   						
   						$(".terrorcomment"+postii).html('');
   						$(".terrorcomment"+postii).append("<div style='text-align:center;' class='alert alert-danger alert-dismissible'>"+result[1]+"</div>");
   						$(".terrorcomment"+postii).css('display','block');
   						
   					}
   					if(result[0]=="pass")
   					{
   						$('.newcomment'+postii).append(result[1]);
   						$('.uperr'+postii).css('display','none');

   						 //console.log(parent.$(".comment_iframe"+postii).contents().find(".com"+result[2]).html());

    						parent.$(".comment_iframe"+postii).contents().find("body").animate({
     scrollTop: $(".com"+result[2]).offset().top
   }, 200);
    						$(".imgcountr"+postii).html('');
    						$(".vidcountr"+postii).html('');
    						$(".imgfiler"+postii).val('');
    						$(".vidfiler"+postii).val('');
    						$(".textar"+postii).val('');
    						$(".linksr"+postii).val('');
    						$(".newtextar"+postii).val('');

    						// var parentcomm=$(".parentcom"+postii).val();
    						// var postidee=$(".postide"+postii).val();
    						// $(".commentf"+postii)[0].reset();
    						// $(".parentcom"+postii).val(parentcomm);
    						// $(".postide"+postii).val(postidee);
   					}
   					
   				}

   			});
       //$("#post_button").attr('type','submit');
       //$("#post_form").submit();
    	}	
    	}
    	function myReplaceMethod(str,find,replace_with){
    while (str.indexOf(find) !== -1 ){
        from = str.indexOf(find);
        to = from + find.length;
        str = str.substr(0,from)+replace_with+str.substr(to, str.length-to);
    }
    return str;
}
    </script>
    








    <!--startofnewcode -->
    <script type="text/javascript">
    	var totalimagesub=0;
    	var totalvideosub=0;
    	function imgicon(ipostc)
    	{
    		$("#cat-filesub"+ipostc)[0].click();

    	}
    	function vidicon(vpostc)
    	{
    		$("#cat-videosub"+vpostc)[0].click();

    	}
    	
    	// $("#mp4-uploadsub").click(function(){
    		
    	// 	$("#cat-videosub")[0].click();
    	// });
    	function totalimgsub(tipostc)
    	{
    		totalimagesub=0;
    		 totalimagesub = $("#cat-filesub"+tipostc)[0].files;
    		 var word="image";
    		 if(totalimagesub.length>1)
    		 {
    		 	word="images";
    		 }
    		$("#imagescountsub"+tipostc).html(totalimagesub.length+" "+word+" ");
    		totalvidsub(tipostc);
    	}
    	function totalvidsub(tvpostc)
    	{
    		totalvideosub=0;
    		 totalvideosub = $("#cat-videosub"+tvpostc)[0].files;
    		 
   
    		if(totalvideosub.length!=0)
    		{
    		 var vword="video";
    		 if(totalvideosub.length>1)
    		 {
    		 	vword="videos";
    		 }
    		 if(totalimagesub.length>0)
    		 {
    		$("#videoscountsub"+tvpostc).html(" and "+totalvideosub.length+" "+vword+" ");
    		}
    		else if(totalimagesub==0){
    			$("#videoscountsub"+tvpostc).html(totalvideosub.length+" "+vword+" ");
    		}
    	}
    	}
    	    	function subcom(posti,e)
    	
    	{

    		e.preventDefault();
    		
    		//$("#post_buttonsub"+posti).attr('type','button');
    		var text = $('#t1'+posti).val();
    var source = (text || '').toString();
    var urlArray = [];
    var url;
    var matchArray;

    // Regular expression to find FTP, HTTP(S) and email URLs.
    var regexToken = /(((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+)/g;

if(source.match(regexToken))
{

    // Iterate through any URLs in the text.
    while( (matchArray = regexToken.exec( source )) !== null )
    {
        var token = matchArray[0];
        urlArray.push( token );
    }

    //return urlArray;
    	var links=urlArray.join(',');
       $('#linksub'+posti).val(links);
       var ex=links.split(',');
       $.each(ex, function( k, v ) {
  			source=myReplaceMethod(source,v,"");
});
       
       $('#post_textfieldsub'+posti).val(source);

   }
   else{
   	$('#post_textfieldsub'+posti).val($('#t1'+posti).val());
   	
   }

		if($('#post_textfieldsub'+posti).val()=="")
		{
			$('#subucomment'+posti).css('display','block');
		}
       else{


       		
       	var form = $('#comment_form'+posti)[0]; 
var formData = new FormData(form);
  
   			$.ajax({
   				url:'livesubcomment.php',
   				type:'post',
   				data: formData,
   				contentType:false,
   				processData:false,
   				dataType:'json',
   				
   				success:function(result)
   				{
   					 
   					 if(result[0]=="fail")
   					 {
   					 	$(".errorcomment"+posti).html('');
   					 	$(".errorcomment"+posti).css('display','none');
   					 	$(".errorcomment"+posti).append("<div style='text-align:center;' class='alert alert-danger alert-dismissible'>"+result[1]+"</div>");
   					 	$(".errorcomment"+posti).css('margin-top','3px');
   					 	$(".errorcomment"+posti).css('display','block');
   						
   					 }
   					 if(result[0]=="pass")
   					 {
   					 	
   						$('.replydiv'+posti).append(result[1]);
   						var po=$('.postidee'+posti).val();
   						
   						 

    						parent.$(".comment_iframe"+po).contents().find("body").animate({
     scrollTop: $(".com"+result[2]).offset().top
   }, 200);
    						 $("#imagescountsub"+posti).html('');
    						 $("#videoscountsub"+posti).html('');
    						 $("#cat-filesub"+posti).val('');
    						 $("#cat-videosub"+posti).val('');
    						 $("#t1"+posti).val('');
    						 $("#linksub"+posti).val('');
    						 $("#post_textfieldsub"+posti).val('');
    						 //console.log(parent.$(".comment_iframe"+po).contents().find(".com"+result[2]).html());
    						
   					}
   					
   				}

   			});

    		}
    	}
    	function closecomment(eid)
{
	$('#'+eid).css('display','none');

}
    </script>
   

    <!--endofnewcode -->
</html>