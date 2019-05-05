<?php  
	require 'config/config.php';
	include("includes/classes/User.php");
	include("includes/classes/Post.php");
	include("includes/classes/Notification.php");

	if (isset($_SESSION['username'])) {
		$backres=array();
		$userLoggedIn = $_SESSION['username'];
		$post_id=$_POST["postid"];
					$user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
				$row = mysqli_fetch_array($user_query);

				$posted_to = $row['added_by'];
				$user_to = $row['user_to'];


		// if(isset($_POST['postComment' . $post_id])) {
					 
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
								$errorMessage="Text is compulsory with the comment reply";
							}
							$imgimplode=implode(",", $img_array);
							$videoimplode=implode(",",$video_array);
				
							if($_POST['post_text']!="" && $errorsub==1) {
								$post_body=$_POST['post_text'];
								// $post = new Post($con, $userLoggedIn);
								// $post->submitPost($_POST['post_text'], 'none', $imgimplode,$post_category,$videoimplode,$links);
								$insert_post = mysqli_query($con, "INSERT INTO comments(post_body,posted_by,posted_to,date_added,removed,post_id,parent_comment,images,videos,yt_links) VALUES ( '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id','$parentcomment','$imgimplode','$videoimplode','$links')");
								$newcommentid = mysqli_insert_id($con);
								include("livesubcommentpart.php");
								$backres[0]="pass";
								$backres[1]="<div style='margin-left:22px;' class='comment_section com$id'><a href='$posted_by' target='_parent'><img src='$profilepic' title='$posted_by' style='float:left;' height='30'></a><a href=' posted_by' target='_parent'> <b>  $profilename  </b></a>&nbsp;&nbsp;&nbsp;&nbsp; $time_message &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$ca <br> $comment_body <br>$imageDiv<hr/></div>";
								$backres[2]=$id;


							}

								if($errorsub==0)
								{
									$backres[0]="fail";
									$backres[1]='<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.
											 $errorMessage;
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





echo json_encode($backres);
								//echo "<p>Comment Posted! </p>";
				//}
	}
