<?php 
include("includes/header.php");
$username="";
if(isset($_GET['profile_username'])) {

  $username = $_GET['profile_username'];
}
$message_obj = new Message($con, $userLoggedIn);
//newcode
if(isset($_POST['postbtn'])){


  $uploadOk = 1;
  $uploadOkv = 1;
  $errorsub=1;
  
  $errorMessage = "";
  $post_category="";
  $links=$_POST['ytlinks'];


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
  $post_category=$_POST['pcategory'];
  
  $video_array=array();
  $video_count=0;
  
  for($i=0;$i<count($_FILES['multivideo']['name']);$i++)
  {
    $videoname=$_FILES['multivideo']['name'][$i];

    if($videoname!="")
    {

      $targetDirv="assets/videos/";
      $videoname=$targetDirv.uniqid().basename($videoname);
     
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
            echo '<script>alert($video_count)</script>';
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
    $errorMessage="Text is compulsory with the post";
  }
 
  $imgimplode=implode(",", $img_array);
  $videoimplode=implode(",",$video_array);
  $userfrom=$_POST['user_from'];
  $userto=$_POST['user_to'];
  if($userto==$userfrom)
  {
    $userto="none";
  }
  
  if($_POST['post_text']!="" && $errorsub==1) {
    
    $post = new Post($con, $userLoggedIn);
    $post->submitPost($_POST['post_text'], $userto, $imgimplode,$post_category,$videoimplode,$links,$username);
  }

  if($errorsub==0)
  {
    ?>
    <div style="text-align:center;" class='alert alert-danger alert-dismissible'>
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $errorMessage; ?>
      </div>
  <?php
}

}
//endnewcode

if(isset($_GET['profile_username'])) {

	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
	$user_array = mysqli_fetch_array($user_details_query);

	$num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}



if(isset($_POST['remove_friend'])) {
	$user = new User($con, $userLoggedIn);
	$user->removeFriend($username);
}

if(isset($_POST['add_friend'])) {
	$user = new User($con, $userLoggedIn);
	$user->sendRequest($username);
}
if(isset($_POST['respond_request'])) {
	header("Location: requests.php");
}



if(isset($_POST['post_message'])) {
  if(isset($_POST['message_body'])) {
    $body = mysqli_real_escape_string($con, $_POST['message_body']);
    $date = date("Y-m-d H:i:s");
    $message_obj->sendMessage($username, $body, $date);

//echo "<script>window.location='$username';</script>";
    $_SESSION["stay"]="yes";
  }
  
  

 


}






 ?>

 	<style type="text/css">
	 	.wrapper {
	 		margin-left: 0px;
			padding-left: 0px;
	 	}

 	</style>
	
 	<div class="profile_left">
 		<img src="<?php echo $user_array['profile_pic']; ?>">

 		<div class="profile_info">
 			<p><?php echo "Posts: " . $user_array['num_posts']; ?></p>
 			<p><?php echo "Likes: " . $user_array['num_likes']; ?></p>
 			<p><?php echo "Friends: " . $num_friends ?></p>
      <p style="margin-top: 4px;"><span  class='fa fa-star'  style="display:inline-block;  width: 37px; height:40px; color:#f7d70b;  background-color:#2baf53; font-size: 35px; padding-left:2px; padding-top: 4px;">&nbsp;</span>
      <?php
    //   $conew = mysqli_connect("localhost", "root", "", "social"); //Connection variable

    if(mysqli_connect_errno()) 
    {
    echo "Failed to connect: " . mysqli_connect_errno();
    }
    $usr=$_GET['profile_username'];
    $res=mysqli_query($con,"select count(*) from comments where posted_by='$usr' and correctans=1 ");
    $totalans=mysqli_fetch_array($res);
      ?>
      <span id="mainans" style="float: right; display:inline; color: #4da2fb; color:white; font-size: 26px; font-weight: bold; margin-right: 40px; margin-top: 4px;"><?php echo $totalans[0]; ?> <br><i style="all:initial; margin-left: -5px; color: white;">Answer</i></span></p>
 		</div>

 		<form action="<?php echo $username; ?>" method="POST" >
 			<?php 
 			$profile_user_obj = new User($con, $username); 
 			if($profile_user_obj->isClosed()) {
 				header("Location: user_closed.php");
 			}

 			$logged_in_user_obj = new User($con, $userLoggedIn); 

 			if($userLoggedIn != $username) {

 				if($logged_in_user_obj->isFriend($username)) {
 					echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
 				}
 				else if ($logged_in_user_obj->didReceiveRequest($username)) {
 					echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
 				}
 				else if ($logged_in_user_obj->didSendRequest($username)) {
 					echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
 				}
 				else 
 					echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';

 			}

 			?>
 		</form>
 		<input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something">

    <?php  
    if($userLoggedIn != $username) {
      echo '<div class="profile_info_bottom">';
        echo $logged_in_user_obj->getMutualFriends($username) . " Mutual friends";
      echo '</div>';
    }


    ?>

 	</div>


	<div class="profile_main_column column">


    <ul class="nav nav-tabs" role="tablist" id="profileTabs">
      <li role="presentation" class="active"><a href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Newsfeed</a></li>
      <li role="presentation"><a href="#about_div" aria-controls="about_div" role="tab" data-toggle="tab">About</a></li>
      <li role="presentation"><a href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab" >Messages</a></li>
    </ul>


    <div class="tab-content">

      <div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
        <div class="posts_area"></div>
        <img id="loading" src="assets/images/icons/loading.gif">
      </div>


      <div role="tabpanel" class="tab-pane fade" id="about_div">
        <?php echo '<h2>Hi .. </h2><br> <b>My Name:</b> '.$username; ?>
      </div>


      <div role="tabpanel" class="tab-pane fade" id="messages_div">
        <?php  
        
          
          echo "<h4>You and <a href='" . $username ."'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr><br>";

          echo "<div class='loaded_messages' id='scroll_messages'>";
            echo $message_obj->getMessages($username);
          echo "</div>";
        ?>



        <div class="message_post">
          <form action="" method="POST" >
              <textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>
              <input type='submit' name='post_message' class='info' id='message_submit' value='Send'>
          </form>

        </div>

        <script>
          var div = document.getElementById("scroll_messages");
          div.scrollTop = div.scrollHeight;
        </script>
      </div>


    </div>


<!-- 		<div class="posts_area"></div>
    <img id="loading" src="assets/images/icons/loading.gif"> -->


	</div>
<div style="display:none;">
        <div id="ninja-slider">
            <div class="slider-inner">
                <ul id="sliderul">
                    
                    
                </ul>
                <div id="fsBtn" class="fs-icon" title="Expand/Close"></div>
            </div>
        </div>
    </div>
<!--end of slider-->
<!-- Modal -->
<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="postModalLabel">Post something!</h4>
      </div>

      <div class="modal-body">
      	<!-- <p>This will appear on the user's profile page and also their newsfeed for your friends to see!</p> -->

      	<form class="profile_post" action="" method="POST" enctype="multipart/form-data">
      		<div class="form-group">
             <label id="cat-label">Category:</label>
      <select id="cat-select" name="pcategory">
        
        <?php
        $post=new Post($con, $userLoggedIn);
        $abc=$post->showcategories();
while($row=mysqli_fetch_array($abc))
{
  $catid=$row["id"];
  $catname=$row["category"];
  ?>
    <option value="<?php echo $catid; ?>"><?php echo $catname; ?></option>
  <?php
}
?>
      </select>
      <div class="post-icons">
        <img id="img-upload" src="assets/icons/multi img icon1.png"/>

        <img id="mp4-upload" src="assets/icons/multi mp4 icon.png"/>
      </div>
      <label id="imagescount"></label>
      <label id="videoscount"></label>
      <input id="cat-file" type="file" name="fileToUpload[]" multiple="multiple" accept=".png, .jpg, .jpeg"  onchange="totalimg()"
      >
      <input id="cat-video" type="file" name="multivideo[]" multiple="multiple" accept=".mp4"  onchange="totalvid()">
      
</div>
      
      <input type="hidden" name="ytlinks" id="links" value="">
      <input type="hidden" name="post_text" id="post_textfield" value="" >
      			<textarea class="form-control" rows="7" cols="50"
style="resize: none;" data-role="none" id="post_text" data-parsley-required data-parsley-error-message="Add some text to describe the task"></textarea>

      			<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
      			<input type="hidden" name="user_to" value="<?php echo $username; ?>">
      		
          <div align="right" class="form-group" style=" margin-top: 8px;" >
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" name="postbtn" id="post_button">Post</button>
          </div>
          </div>
      	</form>
      </div>


      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>


<script>
  var userLoggedIn = '<?php echo $userLoggedIn; ?>';
  var profileUsername = '<?php echo $username; ?>';

  $(document).ready(function() {

    $('#loading').show();

    //Original ajax request for loading first posts 
    $.ajax({
      url: "includes/handlers/ajax_load_profile_posts.php",
      type: "POST",
      data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
      cache:false,

      success: function(data) {
        $('#loading').hide();
        $('.posts_area').html(data);
      }
    });

    $(window).scroll(function() {
      var height = $('.posts_area').height(); //Div containing posts
      var scroll_top = $(this).scrollTop();
      var page = $('.posts_area').find('.nextPage').val();
      var noMorePosts = $('.posts_area').find('.noMorePosts').val();

      if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
        $('#loading').show();

        var ajaxReq = $.ajax({
          url: "includes/handlers/ajax_load_profile_posts.php",
          type: "POST",
          data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
          cache:false,

          success: function(response) {
            $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
            $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

            $('#loading').hide();
            $('.posts_area').append(response);
          }
        });

      } //End if 

      return false;

    }); //End (window).scroll(function())


  });

  </script>
<script type="text/javascript">
      function getimages(name,current)
      {
        //alert('i am in');
        $.ajax({

      url: "includes/handlers/ajax_load_images.php",
      type: "POST",
      data: "userLoggedIn=" + userLoggedIn + "&postimage=" +name,
      cache:false,

      success: function(data) {
        $('#loading').hide();
        //alert(data);
          $('#sliderul').html(data);
          $('.a'+current).trigger('click');
        
      }
    });
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
         
    //     for (var i = 0; i < this.totalvideos.length; i++)
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
      $("#post_button").click(function()
      {
        
        $("#post_button").attr('type','button');
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
    $('#post_text').val('');
//$("#parsley-id-11").css('color','green');
   $('.profile_post').parsley();



 }
       $("#post_button").attr('type','submit');
       $("#post_form").submit();
        
      });
      function myReplaceMethod(str,find,replace_with){
    while (str.indexOf(find) !== -1 ){
        from = str.indexOf(find);
        to = from + find.length;
        str = str.substr(0,from)+replace_with+str.substr(to, str.length-to);
    }
    return str;
}
    </script>
<?php
if(isset($_SESSION["stay"]) && $_SESSION["stay"]=="yes")
{
  
 $link = '#profileTabs a[href="#messages_div"]';
 echo "
 
 <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
 <script> 
        
             $('$link')[0].click();
        

        </script>";
        
        unset($_SESSION["stay"]);
}
?>



	</div>
</body>
</html>