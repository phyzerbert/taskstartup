<?php 
include("includes/header.php");


$post=new Post($con, $userLoggedIn);
$abc=$post->showcategories();
while($row=mysqli_fetch_array($abc))
{
	$catid=$row["id"];
	$catname=$row["category"];
}

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
	
	if($_POST['post_text']!="" && $errorsub==1) {
		
		$post = new Post($con, $userLoggedIn);
		$post->submitPost($_POST['post_text'], 'none', $imgimplode,$post_category,$videoimplode,$links);
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
?>

 
	<div class="user_details column">
		<a href="<?php echo $userLoggedIn; ?>">  <img src="<?php echo $user['profile_pic']; ?>"> </a>

		<div class="user_details_left_right">
			<a href="<?php echo $userLoggedIn; ?>">
			<?php 
			echo $user['first_name'] . " " . $user['last_name'];

			 ?>
			</a>
			<br>
			<?php echo "Posts: " . $user['num_posts']. "<br>"; 
			echo "Likes: " . $user['num_likes'];

			?>
			<div style="margin-top: 20px;">
			<span  class='fa fa-star'  style="display:inline-block;  width: 37px; height:40px; color:#f7d70b;  background-color:#2baf53; font-size: 35px; padding-left:2px; padding-top: 4px;">&nbsp;</span>
			<?php
			$conew = mysqli_connect("localhost", "root", "", "social"); //Connection variable

		if(mysqli_connect_errno()) 
		{
		echo "Failed to connect: " . mysqli_connect_errno();
		}
		$res=mysqli_query($conew,"select count(*) from comments where posted_by='$userLoggedIn' and correctans=1 ");
		$totalans=mysqli_fetch_array($res);
			?>
			<span id="mainans" style="float: right; display:inline; color: #4da2fb; font-size: 26px; font-weight: bold; margin-right: 27px; margin-top: 4px;"><?php echo $totalans[0]; ?> <br><i style="all:initial; margin-left: -5px;">Answer</i></span>
			
		</div>
		</div>

	</div>
<!--slider-->
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

	<div class="main_column column">
		
		<form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
			<div class="form-group post-form">
				
			<label id="cat-label">Category:</label>
			<select id="cat-select" name="pcategory">
				
				<?php
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
			<textarea  id="post_text" placeholder="Got something to say?"></textarea>
			<input type="hidden" name="ytlinks" id="links" value="">
			<input type="hidden" name="post_text" id="post_textfield" value="">
			<input type="submit" name="postbtn" id="post_button" value="Post">

			<hr>

		</form>

		<div class="posts_area"></div>
		<img id="loading" src="assets/images/icons/loading.gif">


	</div>
<?php
if(isset($_GET['watch']))
{
	$watch=$_GET['watch'];
}
 else{
 	$watch="allposts";
 }
?>
	<script>
	var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	var watch='<?php echo $watch; ?>'
	$(document).ready(function() {

		$('#loading').show();

		//Original ajax request for loading first posts 
		$.ajax({

			url: "includes/handlers/ajax_load_posts.php",
			type: "POST",
			data: "page=1&userLoggedIn=" + userLoggedIn + "&watch=" +watch,
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
					url: "includes/handlers/ajax_load_posts.php",
					type: "POST",
					data: "page=" + page + "&userLoggedIn=" + userLoggedIn +"&watch="+watch,
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


<div class="cat" style=" margin-top: 120px; width: 250px; z-index: -1; float: left; font-size: 20px;">Categories</div>
<div class="user_details column" style="margin-top: 5px; height: 20%; ">
	<form style="">
  <select id="sidecat" name="sidecat" size="5" style="width: 225px; height: 95px; float:left!important;">
    <?php
				$abc=$post->showcategories(); 
while($row=mysqli_fetch_array($abc))
{
	$catid=$row["id"];
	$catname=$row["category"];
	?>
		<option class="sidecatoption" value="<?php echo $catid; ?>" style="font-size:15px; font-weight: normal;"><?php echo $catname; ?></option>
	<?php
}
?>
    <option class="sidecatoption" value="allposts">show all</option>
  </select>
  
</form>
</div>
	</div>
</body>
<script type="text/javascript">
 
 $(".sidecatoption").click(function(e)
 {
 	var watch="";
 	watch=$("#sidecat").val();
 	
	$('#loading').show();

		//Original ajax request for loading first posts 
		$.ajax({

			url: "includes/handlers/ajax_load_posts.php",
			type: "POST",
			data: "page=1&userLoggedIn=" + userLoggedIn + "&watch=" +watch,
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
					url: "includes/handlers/ajax_load_posts.php",
					type: "POST",
					data: "page=" + page + "&userLoggedIn=" + userLoggedIn +"&watch="+watch,
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













    
</html>