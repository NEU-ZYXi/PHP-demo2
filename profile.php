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
	</div>

	<div class="main_column column">
		<?php 
		echo $username . "<br>";
		 ?>
	</div>	

</div>

</body>
</html>