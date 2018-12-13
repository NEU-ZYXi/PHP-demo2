<?php
include("includes/header.php");

if (isset($_GET['profile_username'])) {
	$username = $_GET['profile_username'];
	$profile_user = new User($con, $username);
	$profile_user_posts = $profile_user->getNumPosts();
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
	$user_array = mysqli_fetch_array($user_details_query);

	if ($user_array['friend_array'] == NULL) {
		$num_friends = 0;
	} else {
		$num_friends = substr_count($user_array['friend_array'], ",") - 1;
	}
}

if (isset($_POST['remove_friend'])) {
	$user = new User($con, $userLoggedIn);
	$user->removeFriend($username);
}

if (isset($_POST['add_friend'])) {
	$user = new User($con, $userLoggedIn);
	$user->sendRequest($username);
}

if (isset($_POST['respond_friend'])) {
	header("Location: request.php");
}

$message_obj = new Message($con, $userLoggedIn);

if (isset($_POST['post_message'])) {
	if (isset($_POST['message_body'])) {
		$body = mysqli_real_escape_string($con, $_POST['message_body']);
		$date = date("Y-m-d H:i:s");
		$message_obj->sendMessage($username, $body, $date);
	}

	$link = '#profileTabs a[href="#messages_div"]';
	echo "<script>
			$(function() {
				$('". $link . "').tab('show');
			});
		</script>";
}

?>

	<style type="text/css">
		.wrapper {
			margin-left: 10px;
			padding-left: 10px;
			border-radius: 5px;
		}
	</style>
	
	<div class="profile_left">
		<img src="<?php echo $user_array['profile_pic']; ?>">
		<div class="profile_info">
			<p><?php echo "Posts: " . $profile_user_posts . "<br>"; ?></p>
			<p><?php echo "Likes: " . $user_array['num_likes'] . "<br>"; ?></p>
			<p><?php echo "Friends: " . $num_friends . "<br>"; ?></p>
		</div>

		<?php 

		if ($userLoggedIn != $username) {
			$logged_in_user_obj = new User($con, $userLoggedIn);
			echo '<div class="profile_info_bottom">';
			echo $logged_in_user_obj->getMutualFriends($username) . " Mutual friends";
			echo '</div>';
		}

		 ?>

		<form action="<?php echo $username; ?>" method="POST">
			<?php 
			
			$profile_user_obj = new User($con, $username); 
			if ($profile_user_obj->isActive()) {
				$logged_in_user_obj = new User($con, $userLoggedIn);

				// if we are in others' profile page
				if ($userLoggedIn != $username) {

					// current uesr is the friend of this person, show the Remove Friend button, add friend otherwise
					if ($logged_in_user_obj->isFriend($username)) {
						echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
					} else if ($logged_in_user_obj->didReceiveRequest($username)){
						echo '<input type="submit" name="respond_friend" class="warning" value="Respond to request"><br>';
					} else if ($logged_in_user_obj->didSendRequest($username)) {
						echo '<input type="submit" name="" class="default" value="Respond Sent"><br>';
					} else {
						echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
					}
				}
			} else {
				header("Location: user_closed.php");
			}

			?>
		</form>

		<input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post something">

	</div>

	<div class="profile_main_column column">
		<ul class="nav nav-tabs" role="tablist" id="profileTabs">
		  <li class="nav-item">
		    <a class="nav-link active" href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Newsfeed</a>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link" href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab">Messages</a>
		  </li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
				<div class="posts_area"></div>
				<!-- show the icon in the AJAX part -->
				<img id="loading" src="assets/images/icons/loading.gif"> 	
			</div>

			<div role="tabpanel" class="tab-pane fade" id="messages_div">
				<?php 

				echo "<h4>You and <a href='" . $username . "'>" . $profile_user->getFirstAndLastName() . "</a></h4><hr>";
				echo "<div class='loaded_messages' id='scroll_profile_messages'>" . $message_obj->getMessages($username) . "</div>";

				 ?>

				 <div class="message_post">
				 	<form action="" method="POST">
			 			<textarea name='message_body' id='message_textarea' placeholder='Write your message'></textarea>
			 			<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>
				 	</form>
				 </div>

				 <script>
				 	$('a[data-toggle="tab"]').on('shown.bs.tab', function() {
					 	var div = document.getElementById("scroll_profile_messages");
					 	if (div != null) {
					 		div.scrollTop = div.scrollHeight;
					 	}
					 });
				 </script>
			</div>
		</div>
	</div>	

	<!-- Modal -->
	<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">Post something</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

	      <div class="modal-body">
	        <p>What's on your mind?</p>
	        <form class="profile_post" action="" method="POST">
	        	<div class="form-group">
	        		<textarea class="form-control" name="post_body"></textarea>
	        		<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
	        		<input type="hidden" name="user_to" value="<?php echo $username; ?>">
	        	</div>
	        </form>
	      </div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- load the posts in the profile page -->
	<script>
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';
		var profileUsername = '<?php echo $username; ?>';

		$(document).ready(function() {
			$('#loading').show();

			// original AJAX request for loading first posts
			$.ajax({
				url: "includes/handlers/ajax_load_profile_posts.php",
				type: "POST",
				data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername, 
				cache: false,

				success: function(data) {
					$('#loading').hide();
					$('.posts_area').html(data);
				}
			});

			$(window).scroll(function() {
				var height = $('.posts_area').height();  // div containing posts
				var scroll_top = $(this).scrollTop();
				var page = $('.posts_area').find('.nextPage').val();
				var noMorePosts = $('.posts_area').find('.noMorePosts').val();

				if ((document.body.scrollHeight <= window.scrollY + window.innerHeight) && noMorePosts == 'false') {
					$('#loading').show();

					var ajaxReq = $.ajax({
						url: "includes/handlers/ajax_load_profile_posts.php",
						type: "POST",
						data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername, 
						cache: false,

						success: function(response) {
							$('.posts_area').find('.nextPage').remove();  // remove current next page
							$('.posts_area').find('.noMorePosts').remove();

							$('#loading').hide();
							$('.posts_area').append(response);
						}
					});
				}

				return false;
			});
		});
	</script>

</div>

</body>
</html>