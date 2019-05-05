<?php
$profilepic=$profilename="";
$get_comments = mysqli_query($con, "SELECT * FROM comments WHERE id='								$newcommentid' ");
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
					$user_obj = new User($con, $posted_by);
					$profilepic=$user_obj->getProfilePic();
					$profilename=$user_obj->getFirstAndLastName();
				}
			}
?>

















