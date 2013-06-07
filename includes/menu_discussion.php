<?php

//menu send page
function menu_discussion_my_hawk() {
	
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	$total_comments=0;
	
	?>
	
	<div class="wrap-my-hawk">
	<div class="title-img-my-hawk"></div>
	<h2>My Hawk</h2>
	
	<!-- print the number of posts selector -->
	<div class="header-nav-my-hawk clearfix">
		<form action="" method="POST" onsubmit="return myhawkSubmit()">
			<select name="numberpost">
				<option value="10" <?php if(isset($_POST['numberpost'])){if($_POST['numberpost']==10){echo 'selected="select"';}} ?> >Check in the last 10 posts</option>
				<option value="50" <?php if(isset($_POST['numberpost'])){if($_POST['numberpost']==50){echo 'selected="select"';}} ?> >Check in the last 50 posts</option>
				<option value="100" <?php if(isset($_POST['numberpost'])){if($_POST['numberpost']==100){echo 'selected="select"';}} ?> >Check in the last 100 posts</option>
			</select>
			<input class="button-secondary" type="submit" value="Start">
		</form>
		<p id="ajax-img-my-hawk">Getting data from Facebook ...</p>
		<p>Ask for support at <a href="http://www.danycode.com/my-hawk/" target="_blank">My Hawk Official Page</a></p>
	</div>
	
	<!-- if numberpost is not selected do not show the comments and the header of the comments -->
	<?php if(!isset($_POST['numberpost'])){ ?>
		<p class="welcome-message-my-hawk">My Hawk get informations from the Facebook graph API e provide to you the facebook comments that belong to your blog.</p>
		<div class="welcome-image-my-hawk"></div> <?php	
		return;	
	} ?>
	
	<!-- print the beginning of the comments -->
	<div class="container-comments-my-hawk">
	<div class="comments-header-my-hawk clearfix"><div class="comments-header-my-hawk-left">Author</div><div class="comments-header-my-hawk-center">Comment</div><div class="comments-header-my-hawk-right">Location</div></div>	
	
	<?php
	
	//set the $all_comments array counter
	$all_comments_counter=0;
	
	//set the number of the last posts to check based on the previous select form
	$number_of_posts=$_POST['numberpost'];
	$args=array( 'numberposts' => $number_of_posts );
	$lastposts = get_posts($args);
	foreach($lastposts as $post) : setup_postdata($post);	
		//print_r($post);
		$comment_url=$post->guid;
		$post_title=$post->post_title;

		//START - fetching information from facebook

		//variable initialization
		$request_url ="https://graph.facebook.com/comments/?ids=".$comment_url;

		//create the $fb_response array
		$requests = file_get_contents($request_url);
		$fb_response = json_decode($requests,true);//return an array

		//if the array is empty i call continue
		if(count($fb_response[$comment_url]["comments"]["data"])==0){
			$no_comments_counter=$no_comments_counter+1;
			continue;
		};

		//echo $fb_response[$comment_url]["comments"]["data"][0]["id"]; example
		
		//parse the $fb_response array
		foreach($fb_response[$comment_url]["comments"]["data"] as $response){
			
			//add main comments to the $all_comments array
			
			$all_comments[$all_comments_counter]["name"]=$response["from"]["name"];
			$all_comments[$all_comments_counter]["id"]=$response["from"]["id"];
			$all_comments[$all_comments_counter]["message"]=$response["message"];
			$all_comments[$all_comments_counter]["created_time"]=strtotime($response["created_time"]);//ISO8601 to unixtime	
			
			$all_comments_counter=$all_comments_counter+1;
			
			//if the array is empty i call continue
			if(count($response["comments"]["data"])==0){
				$no_comments_counter=$no_comments_counter+1;
				continue;
			};			
			
			//add nested comments to the $all_comments array
			foreach($response["comments"]["data"] as $response_nested){

				$all_comments[$all_comments_counter]["name"]=$response_nested["from"]["name"];
				$all_comments[$all_comments_counter]["id"]=$response_nested["from"]["id"];
				$all_comments[$all_comments_counter]["message"]=$response_nested["message"];
				$all_comments[$all_comments_counter]["created_time"]=strtotime($response_nested["created_time"]);//ISO8601 to unixtime	
				
				$all_comments_counter=$all_comments_counter+1;
				
			}		
			
		}
		
		//END - fetching information from facebook	
	
		//ordering the $all_comments array by created_time

		// Obtain a list of columns
		foreach ($all_comments as $key => $row) {
			$created_time[$key] = $row['created_time'];				
		}

		// Sort the data with created_time descending
		// Add $all_comments as the last parameter, to sort $all_comments by the common key (created_time)
		array_multisort($created_time, SORT_DESC, $all_comments);	
		
		//print all the comments
		for($i=0;$i<$all_comments_counter;$i++){
				output_comment_my_hawk($comment_url,$post_title,$all_comments[$i]["name"],$all_comments[$i]["id"],$all_comments[$i]["message"],$all_comments[$i]["created_time"]);		
		}
		
		//clear the arrays and the counter
		$total_comments=$total_comments+$all_comments_counter;
		unset($all_comments);unset($created_time);$all_comments_counter=0;
	
	endforeach;
	
	if($total_comments==0){echo '<p class="no-comments-my-hawk">No comments found.</p>';}

	?>
	
	<!-- print the end of the comments -->
	<div class="comments-header-my-hawk clearfix"><div class="comments-header-my-hawk-left">Author</div><div class="comments-header-my-hawk-center">Comment</div><div class="comments-header-my-hawk-right">Location</div></div>
	</div>
	
	<?php		
	

	

	
}

//output comment on screen
function output_comment_my_hawk($comment_url,$post_title,$username,$userid,$message,$data){
	?>


	<div class="single-comment-my-hawk clearfix">
		<div class="content-my-hawk-left">
			<img src="http://graph.facebook.com/<?php echo $userid; ?>/picture">
			<div class="name-my-hawk"><a href="http://www.facebook.com/<?php echo $userid; ?>" target="_blank"><?php echo $username; ?></a></div>
		</div>
		<div class="content-my-hawk-center">
			<div class="submitted-date-my-hawk"><?php echo gmdate("l dS \of F Y \a\\t h:i a",$data); ?></div>
			<div class="message-my-hawk"><?php echo $message; ?></div>
		</div>
		<div class="content-my-hawk-right">
			<div class="post-reference-my-hawk"><a href="<?php echo $comment_url; ?>" target="_blank"><?php echo $post_title; ?></a></div>
		</div>
	</div>	

	<?php

}

?>
