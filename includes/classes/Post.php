<?php
class Post {
	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	public function submitPost($body, $user_to, $imageName,$catname,$videoname,$ytlinks,$usrn) {
		//$body = strip_tags($body); //removes html tags 
		$body = mysqli_real_escape_string($this->con, $body);
		$body=trim($body);
		//$body = str_replace('\r\n', "\n", $body); 
		//$body = nl2br($body);
		//$check_empty = preg_replace('/\s+/', '', $body); //Deltes all spaces

		if($body != "") {

			// post youtube video
			// $body_array = preg_split("/\s+/", $body);

			// foreach($body_array as $key => $value) {

			// 	if(strpos($value, "www.youtube.com/watch?v=") !== false) {

			// 		$link = preg_split("!&!", $value);
			// 		$value = preg_replace("!watch\?v=!", "embed/", $link[0]);
			// 		$value = "<br><iframe width=\'420\' height=\'315\' src=\'" . $value ."\'></iframe><br>";
			// 		$body_array[$key] = $value;

			// 	}

			// }
			// $body = implode(" ", $body_array);

			//Current date and time
			$date_added = date("Y-m-d H:i:s");
			//Get username
			$added_by = $this->user_obj->getUsername();

			//If user is on own profile, user_to is 'none'
			if($user_to == $added_by) {
				$user_to = "none";
			}

			//insert post 
			
			$query = mysqli_query($this->con, "INSERT INTO posts(body,added_by,user_to,date_added,category_id,user_closed,deleted,likes,image,videos,yt_links) VALUES('$body', '$added_by', '$user_to', '$date_added','$catname', 'no', 'no', '0', '$imageName','$videoname','$ytlinks')");

			$returned_id = mysqli_insert_id($this->con);

			//Insert notification 
			if($user_to != 'none') {
				$notification = new Notification($this->con, $added_by);
				$notification->insertNotification($returned_id, $user_to, "like");
			}
			//Update post count for user 
			$num_posts = $this->user_obj->getNumPosts();
			$num_posts++;
			$update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
		}
		if($usrn=="") {
			header("Location:index.php");
		} else {
			header("Location:$usrn");
		}
	}



// Show All POSTS -------------------------------------------------------------------------------------------------

	public function loadPostsFriends($data, $limit) {
		$cat_id=$data['watch'];
		if($cat_id=="allposts")
		{
			$cate="";
		}
		else{
				$cate=" and category_id='$cat_id'";
		}
		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;


		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no'  $cate ORDER BY id DESC");
		if((mysqli_num_rows($data_query) > 0)==false) {
			echo "<p style='text-align: centre;'> No  posts to show! </p>";
		}

		if(mysqli_num_rows($data_query) > 0) {


			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image'];
				$videoPath = $row['videos'];
				$linkPath = $row['yt_links'];

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				

					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}




					// if($userLoggedIn == $added_by)
					// 	$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					// else 
						$delete_button = "";
					$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
					$user_row = mysqli_fetch_array($user_details_query);
					$first_name = $user_row['first_name'];
					$last_name = $user_row['last_name'];
					$profile_pic = $user_row['profile_pic'];




?>




					<script> 
						function toggle<?php echo $id; ?>(event) {

							var target = $(event.target);
							if (!target.is("a")) {
								var element = document.getElementById("toggleComment<?php echo $id; ?>");

								if(element.style.display == "block") 
									element.style.display = "none";
								else 
									element.style.display = "block";
							}
						}

					</script>




<?php




					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);



					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
					$end_date = new DateTime($date_time_now); //Current time
					$interval = $start_date->diff($end_date); //Difference between dates 
					if($interval->y >= 1) {
						if($interval == 1)
							$time_message = $interval->y . " year ago"; //1 year ago
						else 
							$time_message = $interval->y . " years ago"; //1+ year ago
					}
					else if ($interval-> m >= 1) {
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
						$lastimage=0;
						foreach ($imgexplode as $key => $imageexplode) {

							$lastimage=$key+1;
							$data="";
							$imageFileType=pathinfo($imageexplode,PATHINFO_EXTENSION);
							if($imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "jpg") {
								$data="<img src='$imageexplode' onclick='getimages($id,$key)'>";
							}
							elseif($imageFileType=="mp4"){
								$data="<video controls onclick='getimages($id,$key)'><source src='$imageexplode'  type='video/mp4'></video>";
							}
							else{
								$embed=str_replace("/watch?v=","/embed/", $imageexplode);
								if($linkc==2)
								{
								$data="<iframe allowfullscreen 
								src='$embed' >
									</iframe>";	
								}
								if($linkc==1 && $videoc==0 && $imgc==0)
								{
									$data="<div style='text-align:center; margin-top:5px;'><iframe  allowfullscreen onclick='getimages($id,$key)'
								src='$embed' >
									</iframe></div>";
								}
								if($linkc==1 && $videoc==0 && $imgc==1)
								{
									$data="<iframe allowfullscreen onclick='getimages($id,$key)'
								src='$embed' >
									</iframe>";
								}
								if($linkc==1 && $videoc==1 && $imgc==0)
								{
									$data="<iframe allowfullscreen onclick='getimages($id,$key)'
								src='$embed' >
									</iframe>";
								}

								
							}
						if($key==0)
						{
							if($totalimg==1 )
							{
								$imageDiv= "<div class='col-md-10 postedTwoImage' >
										$data
									</div>";
								}
									elseif($totalimg==2 || $totalimg>2){
										// $imageDiv= "<div class='col-md-5 postedTwoImage $additionalclass'>
										$imageDiv= "<div class='col-md-5 postedTwoImage'>
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
										// $imageDiv .= "<div class='col-md-5 postedTwoImage $additionalclass' >
										$imageDiv .= "<div class='col-md-5 postedTwoImage' >
												$data
											</div>									
										";
									break;
									}
									if($totalimg>2){
										$moreimg=$key+1;
										// $imageDiv .= "<div class='col-md-5 postedTwoImage $additionalclass' >
										$imageDiv .= "<div class='col-md-5 postedTwoImage' >
										$data
									</div><br>
									<div class='remaining' onclick='getimages($id,$moreimg)'>
											+ $remaining_images											
										</div>
										";
									break;
									
									}
									// elseif($totalimg==3){
									// 	if($lastimage==3)
									// 	{
									// 		$imageDiv .= "<div class='postedImagethreextra'>
									// 	$data
									// </div>";
									// 	}
									// 	else{
									// 	$imageDiv .= "<div class='postedImagethree'>
									// 	$data
									// </div>";
									// 	}
									// }
									//elseif($totalimg==4){
							// 				if($lastimage==3)
							// 				{
							// 					$showfourth=$key+1;
							// 					$remaining_images=$totalimg-($lastimage+1);
												
							// 					$imageDiv .= "<div class='postedImagefourextra'>
							// 				$data
							// 			</div>";
							// 			$imageFileType=pathinfo($imageexplode,PATHINFO_EXTENSION);

							// if($imageFileType == "jpeg" && $imageFileType == "png" && $imageFileType == "jpg") {
							// 	$data="<img src='$imgexplode[$showfourth]' onclick='getimages($id,$showfourth)'>";

							// }
							// elseif($imageFileType=="mp4"){
							// 	$data="<video  controls onclick='getimages($id,$showfourth)'><source src='$imgexplode[$showfourth]'  type='video/mp4'></video>";
							// }
							// else{
							// 	$embed=str_replace("/watch?v=","/embed/", $imgexplode[$showfourth]);
							// 	$data="<iframe width='320' height='240' onclick='getimages($id,$showfourth)'
							// 	src='$embed' >
							// 		</iframe>";
							// }
							// 			$imageDiv .="<div class='postedImagefour'>
							// 				'$data'
							// 			</div>
										
							// 			";
							// 					break;
							// 				}
										// 	else{

										// 	$imageDiv .= "<div class='postedImagefour'>
										// 	$data
										// </div>";
										// }
										//}
							// 		elseif($totalimg>4){
							// 				if($lastimage==3)
							// 				{
							// 					$showfourth=$key+1;
							// 					$remaining_images=$totalimg-($lastimage+1);

							// 					$imageDiv .= "<div class='postedImagefourextra'>
							// 				$data
							// 			</div>";
							// 			$imageFileType=pathinfo($imageexplode,PATHINFO_EXTENSION);
							// if(strtolower($imageFileType) == "jpeg" || strtolower($imageFileType) == "png" || strtolower($imageFileType) == "jpg") {
							// 	$data="<img src='$imgexplode[$showfourth]' onclick='getimages($id,$showfourth)'>";
							// }
							// elseif($imageFileType=="mp4"){
							// 	$data="<video  controls onclick='getimages($id,$showfourth)'><source src='$imgexplode[$showfourth]'  type='video/mp4'></video>";
							// }
							// else{
							// 	$embed=str_replace("/watch?v=","/embed/", $imgexplode[$showfourth]);
							// 	$data="<iframe width='320' height='240' onclick='getimages($id,$showfourth)'
							// 	src='$embed' >
							// 		</iframe>";
							// }
							// 			$imageDiv .="<div class='postedImagefour'>
							// 				'$data'
							// 			</div>
										
										
							// 			<div class='remaining'>
							// 				+ $remaining_images 
											
											
							// 			</div>
							// 			";
							// 					break;
							// 				}
							// 				else{

							// 				$imageDiv .= "<div class='postedImagefour'>
							// 				$data
							// 			</div>";
							// 			}
									//}
									
								}
					}
				}
					else {
						$imageDiv = "";
					}

					$str .= "
							<div class='post_profile_pic'>
								<img src='$profile_pic' width='50'>
							</div>

							<div class='posted_by' style='color:#ACACAC;'>
								<a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
								$delete_button									
							</div>
							<div id='post_body'>
								$body
								<br>
								<div class='row'>
								$imageDiv
								</div>
								<br>
								<br>
							</div>
							<div class='newsfeedPostOptions'>
								<span onClick='javascript:toggle$id(event)'>Comments($comments_check_num)</span>&nbsp;&nbsp;&nbsp;
								<iframe src='like.php?post_id=$id' scrolling='no'></iframe>
							</div>
							
							<div class='post_comment' id='toggleComment$id' style='display:none;'>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' class='comment_iframe$id' frameborder='0'></iframe>
							</div> 
							<hr>";
			?>



				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});


					});

				</script>




			<?php	

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
		}

		echo $str;


	}





	public function loadProfilePosts($data, $limit) {

		$page = $data['page']; 
		$profileUser = $data['profileUsername'];
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;


		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND ((added_by='$profileUser' AND user_to='none') OR user_to='$profileUser')  ORDER BY id DESC");
		if((mysqli_num_rows($data_query) > 0)==false) {
			echo "<p style='text-align: centre;'> No  posts to show! </p>";
		}
		if(mysqli_num_rows($data_query) > 0) {


			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$imagePath = $row['image'];
				$videoPath = $row['videos'];
				$linkPath = $row['yt_links'];

					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


					$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
					$user_row = mysqli_fetch_array($user_details_query);
					$first_name = $user_row['first_name'];
					$last_name = $user_row['last_name'];
					$profile_pic = $user_row['profile_pic'];


					?>
					<script> 
						function toggle<?php echo $id; ?>(event) {

							var target = $(event.target);
							if (!target.is("a")) {
								var element = document.getElementById("toggleComment<?php echo $id; ?>");

								if(element.style.display == "block") 
									element.style.display = "none";
								else 
									element.style.display = "block";
							}
						}

					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);


					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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
								$data="<iframe width='250' height='200' allowfullscreen  style='display:inline;' '
								src='$embed' >
									</iframe>";	
								}
								if($linkc==1 && $videoc==0 && $imgc==0)
								{
									$data="<div style='text-align:center; margin-top:5px;'><iframe width='528' height='315'  allowfullscreen onclick='getimages($id,$key)'
								src='$embed' >
									</iframe></div>";
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
								$imageDiv= "<div class='postedImage' >
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
									<div class='remaining' onclick='getimages($id,$moreimg)'>
											+ $remaining_images 
											
											
										</div>
										";
									break;
									
									}
									// elseif($totalimg==3){
									// 	if($lastimage==3)
									// 	{
									// 		$imageDiv .= "<div class='postedImagethreextra'>
									// 	$data
									// </div>";
									// 	}
									// 	else{
									// 	$imageDiv .= "<div class='postedImagethree'>
									// 	$data
									// </div>";
									// 	}
									// }
									//elseif($totalimg==4){
							// 				if($lastimage==3)
							// 				{
							// 					$showfourth=$key+1;
							// 					$remaining_images=$totalimg-($lastimage+1);
												
							// 					$imageDiv .= "<div class='postedImagefourextra'>
							// 				$data
							// 			</div>";
							// 			$imageFileType=pathinfo($imageexplode,PATHINFO_EXTENSION);

							// if($imageFileType == "jpeg" && $imageFileType == "png" && $imageFileType == "jpg") {
							// 	$data="<img src='$imgexplode[$showfourth]' onclick='getimages($id,$showfourth)'>";

							// }
							// elseif($imageFileType=="mp4"){
							// 	$data="<video  controls onclick='getimages($id,$showfourth)'><source src='$imgexplode[$showfourth]'  type='video/mp4'></video>";
							// }
							// else{
							// 	$embed=str_replace("/watch?v=","/embed/", $imgexplode[$showfourth]);
							// 	$data="<iframe width='320' height='240' onclick='getimages($id,$showfourth)'
							// 	src='$embed' >
							// 		</iframe>";
							// }
							// 			$imageDiv .="<div class='postedImagefour'>
							// 				'$data'
							// 			</div>
										
							// 			";
							// 					break;
							// 				}
										// 	else{

										// 	$imageDiv .= "<div class='postedImagefour'>
										// 	$data
										// </div>";
										// }
										//}
							// 		elseif($totalimg>4){
							// 				if($lastimage==3)
							// 				{
							// 					$showfourth=$key+1;
							// 					$remaining_images=$totalimg-($lastimage+1);

							// 					$imageDiv .= "<div class='postedImagefourextra'>
							// 				$data
							// 			</div>";
							// 			$imageFileType=pathinfo($imageexplode,PATHINFO_EXTENSION);
							// if(strtolower($imageFileType) == "jpeg" || strtolower($imageFileType) == "png" || strtolower($imageFileType) == "jpg") {
							// 	$data="<img src='$imgexplode[$showfourth]' onclick='getimages($id,$showfourth)'>";
							// }
							// elseif($imageFileType=="mp4"){
							// 	$data="<video  controls onclick='getimages($id,$showfourth)'><source src='$imgexplode[$showfourth]'  type='video/mp4'></video>";
							// }
							// else{
							// 	$embed=str_replace("/watch?v=","/embed/", $imgexplode[$showfourth]);
							// 	$data="<iframe width='320' height='240' onclick='getimages($id,$showfourth)'
							// 	src='$embed' >
							// 		</iframe>";
							// }
							// 			$imageDiv .="<div class='postedImagefour'>
							// 				'$data'
							// 			</div>
										
										
							// 			<div class='remaining'>
							// 				+ $remaining_images 
											
											
							// 			</div>
							// 			";
							// 					break;
							// 				}
							// 				else{

							// 				$imageDiv .= "<div class='postedImagefour'>
							// 				$data
							// 			</div>";
							// 			}
									//}
									
								}
					}
				}
					else {
						$imageDiv = "";
					}
					$str .= "<div class='status_post' >
								<div class='post_profile_pic'>
									<img src='$profile_pic' width='50'>
								</div>

								<div class='posted_by' style='color:#ACACAC;'>
									<a href='$added_by'> $first_name $last_name </a> &nbsp;&nbsp;&nbsp;&nbsp;$time_message
									$delete_button
								</div>
								<div id='post_body'>
									$body
									<br>
									$imageDiv
									<br>
									<br>
								</div>

								<div class='newsfeedPostOptions' onClick='javascript:toggle$id(event)' >
									Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
									<iframe  src='like.php?post_id=$id' scrolling='no'></iframe>
								</div>

							</div>
							<div class='post_comment' id='toggleComment$id' style='display:none;'>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' class='comment_iframe$id' frameborder='0'></iframe>
							</div>
							<hr>";

				?>
				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});


					});

				</script>
				<?php

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'> No more posts to show! </p>";
		}

		echo $str;


	}



// Just Show Group Posts Friends
/*
	public function loadJustPostsFriends($data, $limit) {

		$page = $data['page']; 
		$userLoggedIn = $this->user_obj->getUsername();

		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;


		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

		if(mysqli_num_rows($data_query) > 0) {


			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];
				$img_past = $row['image'];

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					continue;
				}

				


				$user_logged_obj = new User($this->con, $userLoggedIn);
				if($user_logged_obj->isFriend($added_by)){






					if($num_iterations++ < $start)
						continue; 


					//Once 10 posts have been loaded, break
					if($count > $limit) {
						break;
					}
					else {
						$count++;
					}

					$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
					$user_row = mysqli_fetch_array($user_details_query);
					$first_name = $user_row['first_name'];
					$last_name = $user_row['last_name'];
					$profile_pic = $user_row['profile_pic'];


					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
					$end_date = new DateTime($date_time_now); //Current time
					$interval = $start_date->diff($end_date); //Difference between dates 
					if($interval->y >= 1) {
						if($interval == 1)
							$time_message = $interval->y . " year ago"; //1 year ago
						else 
							$time_message = $interval->y . " years ago"; //1+ year ago
					}
					else if ($interval-> m >= 1) {
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

					$str .= "<div class='status_post'>
								<div class='post_profile_pic'>
									<img src='$profile_pic' width='50'>
								</div>

								<div class='posted_by' style='color:#ACACAC;'>
									<a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
								</div>
								<div id='post_body'>
									$body
									<br>
								</div>
								<div>
									<img src='images/$img_past' width='500'>
								</div>


							</div>
							<hr>";
				
				}

			} //End while loop

			if($count > $limit) 
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
							<input type='hidden' class='noMorePosts' value='false'>";
			else 
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
		}

		echo $str;


	}
*/









	public function getSinglePost($post_id) {

		$userLoggedIn = $this->user_obj->getUsername();

		$opened_query = mysqli_query($this->con, "UPDATE notifications SET opened='yes' WHERE user_to='$userLoggedIn' AND link LIKE '%=$post_id'");

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND id='$post_id'");

		if(mysqli_num_rows($data_query) > 0) {


			$row = mysqli_fetch_array($data_query); 
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					return;
				}

				$user_logged_obj = new User($this->con, $userLoggedIn);
				if($user_logged_obj->isFriend($added_by)){


					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


					$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
					$user_row = mysqli_fetch_array($user_details_query);
					$first_name = $user_row['first_name'];
					$last_name = $user_row['last_name'];
					$profile_pic = $user_row['profile_pic'];


					?>
					<script> 
						function toggle<?php echo $id; ?>(event) {

							var target = $(event.target);
							if (!target.is("a")) {
								var element = document.getElementById("toggleComment<?php echo $id; ?>");

								if(element.style.display == "block") 
									element.style.display = "none";
								else 
									element.style.display = "block";
							}
						}

					</script>
					<?php

					$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
					$comments_check_num = mysqli_num_rows($comments_check);


					//Timeframe
					$date_time_now = date("Y-m-d H:i:s");
					$start_date = new DateTime($date_time); //Time of post
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

					$str .= "<div class='status_post' onClick='javascript:toggle$id(event)'>
								<div class='post_profile_pic'>
									<img src='$profile_pic' width='50'>
								</div>

								<div class='posted_by' style='color:#ACACAC;'>
									<a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
									$delete_button
								</div>
								<div id='post_body'>
									$body
									<br>
									<br>
									<br>
								</div>

								<div class='newsfeedPostOptions'>
									Comments($comments_check_num)&nbsp;&nbsp;&nbsp;
									<iframe src='like.php?post_id=$id' scrolling='no'></iframe>
								</div>

							</div>
							<div class='post_comment' id='toggleComment$id' style='display:none;'>
								<iframe src='comment_frame.php?post_id=$id' id='comment_iframe' class='comment_iframe$id' frameborder='0'></iframe>
							</div>
							<hr>";


				?>
				<script>

					$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("includes/form_handlers/delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});


					});

				</script>
				<?php
				}
				else {
					echo "<p>You cannot see this post because you are not friends with this user.</p>";
					return;
				}
		}
		else {
			echo "<p>No post found. If you clicked a link, it may be broken.</p>";
					return;
		}

		echo $str;
	}

public function showcategories()
{
	$getcat = mysqli_query($this->con, "SELECT * FROM category");

		if(mysqli_num_rows($getcat)>0)
		{
			
			return $getcat;
		} 
}
public function showsinglecategorypost($watchcat)
{
	if($watchcat!="all_posts")
	{
		$getpost=mysqli_query($this->con,"select * from posts where category_id='$watchcat'");
		//if(mysqli_num_rows())
	}
}

public function loadPostsimages($data)
{
	$post_id=$data["postimage"];
	$imagePath=$videoPath=$linkPath="";
	$getdata=mysqli_query($this->con,"select image,videos,yt_links from posts where id='$post_id'");
	if(mysqli_num_rows($getdata)>0)
	{
		$getimg=mysqli_fetch_array($getdata);
		$imagePath= $getimg[0];
		$videoPath= $getimg[1];
		$linkPath= $getimg[2];
	}
		$combined="";
		if($imagePath != "" || $videoPath!="" ||$linkPath!="") {
						
						
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

		
		$str="";
		foreach ($imgexplode as $key => $images) {
			$imageFileType=pathinfo($images,PATHINFO_EXTENSION);
							if($imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "jpg") {
								$data="<a data-fancybox='gallery' class='a$key' href='$images'>$images</a>
			";
							}
							elseif($imageFileType=="mp4"){
								$data="<a data-fancybox='gallery' class='a$key'  href='$images'>
    $images
</a>";
							}
							else{
								$embed=str_replace("/watch?v=","/embed/", $images);
								
								$data="<a data-fancybox='gallery'  class='a$key'  href='$embed'>
    $images
</a>";	
								}
			$str .=$data;
		}
		echo $str;
	}
}

public function totalpost($id)
{
	if($id==6)
	{
		$getdata=mysqli_query($this->con,"select * from posts");
	}
	else{
	$getdata=mysqli_query($this->con,"select * from posts where category_id='$id'");
	}

$total = mysqli_num_rows($getdata);
return $total;

}
public function loadcommentimages($data)
{
	$post_id=$data["postimage"];
	$imagePath=$videoPath=$linkPath="";
	$getdata=mysqli_query($this->con,"select images,videos,yt_links from comments where id='$post_id'");
	if(mysqli_num_rows($getdata)>0)
	{
		$getimg=mysqli_fetch_array($getdata);
		$imagePath= $getimg[0];
		$videoPath= $getimg[1];
		$linkPath= $getimg[2];
	}
		$combined="";
		if($imagePath != "" || $videoPath!="" ||$linkPath!="") {
						
						
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

		
		$str="";
		foreach ($imgexplode as $key => $images) {
			$imageFileType=pathinfo($images,PATHINFO_EXTENSION);
							if($imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "jpg") {
								$data="<a data-fancybox='gallery' class='a$key' href='$images'>$images</a>
			";
							}
							elseif($imageFileType=="mp4"){
								$data="<a data-fancybox='gallery' class='a$key'  href='$images'>
    $images
</a>";
							}
							else{
								$embed=str_replace("/watch?v=","/embed/", $images);
								
								$data="<a data-fancybox='gallery'  class='a$key'  href='$embed'>
    $images
</a>";	
								}
			$str .=$data;
		}
		echo $str;
	}
}
public function correctans($data)
{
	$str="";
	$comid=$data["com"];
	$stat=$data["stat"];
	if($stat==0)
	{
	$updata=mysqli_query($this->con,"update comments set correctans=1 where id='$comid'");
			if($updata)
			{
				$str .="<span id='stchecked' class='fa fa-star st$comid' onclick='updateans($comid,1)' style='color:#f7d70b;  background-color:#2baf53; font-size: 21px; padding-left:2px;'>&nbsp;<i style='color:white;  background-color:#2f95d2; font-size: 19px; height:21px;float:right;'>&nbsp;Answer&nbsp;</i></span>
					";
			}
	}
	elseif($stat==1)
	{
		$updata=mysqli_query($this->con,"update comments set correctans=0 where id='$comid'");
				if($updata)
			{
				$str .="<span id='stunchecked'  class='fa fa-star st$comid' onclick='updateans($comid,0)' style='color:grey; font-size: 20px; '></span>";
			}
	}
	

	echo $str;

}


}

?>
