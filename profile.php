<?php
include("includes/header.php");

if (isset($_GET['profile_username'])) {
	$username = $_GET['profile_username'];
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
			<p><?php echo "Posts: " . $user_array['num_posts'] . "<br>"; ?></p>
			<p><?php echo "Likes: " . $user_array['num_likes'] . "<br>"; ?></p>
			<p><?php echo "Friends: " . $num_friends . "<br>"; ?></p>
		</div>

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

	<div class="main_column column">
		<?php 
		echo $username . "<br>";
		 ?>
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

</div>

</body>
</html>