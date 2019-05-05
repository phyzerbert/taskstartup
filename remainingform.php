<form style="margin-top: 6px; display: none;" action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form<?php echo $id; ?>" name="postComment<?php echo $post_id; ?>" method="POST" enctype="multipart/form-data" >
			    <div class="row">
			        
			        <div class="col-xs-10">
			            <div class="input-group">
			                <textarea class="form-control"  id="t1<?php echo $id; ?>" name="post_body"></textarea>
			                <span class="input-group-addon  newbtn" onclick="imgicon('<?php echo $id; ?>')"><i class="fas fa-camera  fa-lg"></i></span>
			                <span class="input-group-addon newbtn" onclick="vidicon('<?php echo $id; ?>')"><i class="fas fa-video fa-lg"></i></span>
			                
			            </div>
			        </div>
		        <div class="col-xs-2">
		        	<input type="submit" id="post_buttonsub<?php echo $id; ?>" name="postComment<?php echo $post_id; ?>" value="Post" style="border: none!important; background-color: #20AAE5!important;color: #156588!important;border-radius: 5px!important;width: 94%!important;height: 48px!important;margin-top: 3px!important;font-family: 'Bellota-BoldItalic', sans-serif;text-shadow: #73B6E2 0.5px 0.5px 0px!important;margin-left: -22px!important;"  onclick="subcom('<?php echo $id; ?>');">
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

			<input type="hidden" name="post_text" id="post_textfieldsub<?php echo $id; ?>" value="">

	</form>