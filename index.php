	<?php 
		include("includes/header.php");

		$errormsg="";
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
				
			} else {
				if($links!="") {
					$linkexplode=explode(",",$links);
					foreach ($linkexplode as $linkexplodee) {
						
						if(strlen($linkexplodee)>52) {
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
						} else {						
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
						} else {
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
				$post->submitPost($_POST['post_text'], 'none', $imgimplode,$post_category,$videoimplode,$links,"");
			}

			if($errorsub==0)
			{
				$errormsg=$errorMessage;		
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
					// 	$conew = mysqli_connect("localhost", "root", "", "social"); //Connection variable

					if(mysqli_connect_errno()) 
					{
					echo "Failed to connect: " . mysqli_connect_errno();
					}
					$res=mysqli_query($con,"select count(*) from comments where posted_by='$userLoggedIn' and correctans=1 ");
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
				<input id="cat-file" type="file" name="fileToUpload[]" multiple="multiple" accept=".png, .jpg, .jpeg"  onchange="totalimg()">
				<input id="cat-video" type="file" name="multivideo[]" multiple="multiple" accept=".mp4"  onchange="totalvid()">			
			</div>
			<div style="text-align:center; display: none;" class='alert alert-danger alert-dismissible' id="mainposterror">
			<a href="#" class="close"  onclick="closeme('mainposterror')">&times;</a>
				Add some text to describe the task ... close it and try again!
			</div>
 			<?php if($errormsg!="") { ?>
				<div style="text-align:center;" class='alert alert-danger alert-dismissible'>
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo $errormsg; ?>
				</div>
			<?php } ?>
			<textarea  id="post_text" placeholder="Got something to say?" data-parsley-required></textarea>
			<input type="hidden" name="ytlinks" id="links" value="">
			<input type="hidden" name="post_text" id="post_textfield" value="">
			<input type="submit" name="postbtn" id="post_button"  value="Post">
		</form>

		<hr>

		<form id="fileupload" action="/uploader/index.php" method="POST" enctype="multipart/form-data">
			<noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
			<div class="row fileupload-buttonbar">
				<div class="col-lg-7">
					<span class="btn btn-success fileinput-button">
						<i class="glyphicon glyphicon-plus"></i>
						<span>Add files...</span>
						<input type="file" name="files[]" multiple>
					</span>
					<button type="submit" class="btn btn-primary start" id="uploader_start">
						<i class="glyphicon glyphicon-upload"></i>
						<span>Start upload</span>
					</button>
					<button type="reset" class="btn btn-warning cancel">
						<i class="glyphicon glyphicon-ban-circle"></i>
						<span>Cancel upload</span>
					</button>
					<button type="button" class="btn btn-danger delete">
						<i class="glyphicon glyphicon-trash"></i>
						<span>Delete</span>
					</button>
					<input type="checkbox" class="toggle">
					<span class="fileupload-process"></span>
				</div>
				<div class="col-lg-5 fileupload-progress fade">
					<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						<div class="progress-bar progress-bar-success" style="width:0%;"></div>
					</div>
					<div class="progress-extended">&nbsp;</div>
				</div>
			</div>
			<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
		</form>

		<hr>

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


		<div class="cat" style=" margin-top: 120px; width: 250px; z-index: -1; float: left; font-size: 20px;">Categories
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
						<option class="sidecatoption" value="allposts">Show all</option>
					</select>	
				</form>
			</div>
		</div>
	</div>

	<!-- The template to display files available for upload -->
	<script id="template-upload" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
			<tr class="template-upload fade">
				<td>
					<span class="preview"></span>
				</td>
				<td>
					{% if (window.innerWidth > 480 || !o.options.loadImageFileTypes.test(file.type)) { %}
						<p class="name">{%=file.name%}</p>
					{% } %}
					<strong class="error text-danger"></strong>
				</td>
				<td>
					<p class="size">Processing...</p>
					<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
				</td>
				<td>
					{% if (!i && !o.options.autoUpload) { %}
						<button class="btn btn-primary start" disabled>
							<i class="glyphicon glyphicon-upload"></i>
							<span>Start</span>
						</button>
					{% } %}
					{% if (!i) { %}
						<button class="btn btn-warning cancel">
							<i class="glyphicon glyphicon-ban-circle"></i>
							<span>Cancel</span>
						</button>
					{% } %}
				</td>
			</tr>
		{% } %}
	</script>
	<!-- The template to display files available for download -->
	<script id="template-download" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
			<tr class="template-download fade">
				<td>
					<span class="preview">
						{% if (file.thumbnailUrl) { %}
							<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
						{% } else { %}
							{% if (file.type == "video/mp4") { %}
								<video src="{%=file.url%}" width="100" controls></video>
							{% } else if (file.type == "application/x-zip-compressed" || file.type == "application/octet-stream") { %}
								<img src="/assets/plugins/uploader/img/rar_icon.png" width="50">
							{% } else if (file.type == "application/msword" || file.type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") { %}
								<img src="/assets/plugins/uploader/img/word_icon.png" width="50">
							{% } else { %}
								<img src="/assets/plugins/uploader/img/file_icon.png" width="50">
							{% } %}
						{% } %}
					</span>
				</td>
				<td>
					{% if (window.innerWidth > 480 || !file.thumbnailUrl) { %}
						<p class="name">
							{% if (file.url) { %}
								<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
							{% } else { %}
								<span>{%=file.name%}</span>
							{% } %}
						</p>
					{% } %}
					{% if (file.error) { %}
						<div><span class="label label-danger">Error</span> {%=file.error%}</div>
					{% } %}
				</td>
				<td>
					<span class="size">{%=o.formatFileSize(file.size)%}</span>
				</td>
				<td>
					{% if (file.deleteUrl) { %}
						<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
							<i class="glyphicon glyphicon-trash"></i>
							<span>Delete</span>
						</button>
						<input type="checkbox" name="delete" value="1" class="toggle">
					{% } else { %}
						<button class="btn btn-warning cancel">
							<i class="glyphicon glyphicon-ban-circle"></i>
							<span>Cancel</span>
						</button>
					{% } %}
				</td>
			</tr>
		{% } %}
	</script>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script>  -->
	<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
	<script src="assets/plugins/uploader/js/vendor/jquery.ui.widget.js"></script>
	<!-- The Templates plugin is included to render the upload/download listings -->
	<script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
	<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
	<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
	<!-- The Canvas to Blob plugin is included for image resizing functionality -->
	<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
	<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
	<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
	<!-- blueimp Gallery script -->
	<script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<script src="js/jquery.iframe-transport.js"></script>
	<!-- The basic File Upload plugin -->
	<script src="assets/plugins/uploader/js/jquery.fileupload.js"></script>
	<!-- The File Upload processing plugin -->
	<script src="assets/plugins/uploader/js/jquery.fileupload-process.js"></script>
	<!-- The File Upload image preview & resize plugin -->
	<script src="assets/plugins/uploader/js/jquery.fileupload-image.js"></script>
	<!-- The File Upload audio preview plugin -->
	<script src="assets/plugins/uploader/js/jquery.fileupload-audio.js"></script>
	<!-- The File Upload video preview plugin -->
	<script src="assets/plugins/uploader/js/jquery.fileupload-video.js"></script>
	<!-- The File Upload validation plugin -->
	<script src="assets/plugins/uploader/js/jquery.fileupload-validate.js"></script>
	<!-- The File Upload user interface plugin -->
	<script src="assets/plugins/uploader/js/jquery.fileupload-ui.js"></script>
	<!-- The main application script -->
	<script src="assets/plugins/uploader/js/main.js"></script>
	
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
    	$("#post_button").click(function(e) {    		
    		$("#post_button").attr('type','button');
    		var text = $('#post_text').val();
			var source = (text || '').toString();
			var urlArray = [];
			var url;
			var matchArray;

			// Regular expression to find FTP, HTTP(S) and email URLs.
			var regexToken = /(((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+)/g;

			if(source.match(regexToken)) {

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

			} else {
				$('#post_textfield').val($('#post_text').val());   	
			}
			var b="";
			b=$('#post_textfield').val();
			if(b=="") {
				$('#mainposterror').css('display','block');
				$("#post_button").attr('type','submit');
				e.preventDefault();
			} else {
				$("#post_button").attr('type','submit');
				alert(123);
				$("#uploader_start").trigger('click');
				e.preventDefault();
				// $("#post_form").submit();
			}	
		});
    function myReplaceMethod(str,find,replace_with){
		while (str.indexOf(find) !== -1 ){
			from = str.indexOf(find);
			to = from + find.length;
			str = str.substr(0,from)+replace_with+str.substr(to, str.length-to);
		}
		return str;
	}
	function closeme(eid)
	{
		$('#'+eid).css('display','none');
	}
    </script>    
</html>