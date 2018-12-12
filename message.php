<?php 
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);

if (isset($_GET['username'])) {
	$user_to = $_GET['username'];
} else {
	$user_to = $message_obj->getMostRecentUser();
	if ($user_to == false) {
		$user_to = "new";
	}
}

if ($user_to != "new") {
	$user_to_obj = new User($con, $user_to);
}

if (isset($_POST['post_message'])) {
	if (isset($_POST['message_body'])) {
		$body = mysqli_real_escape_string($con, $_POST['message_body']);
		$date = date("Y-m-d H:i:s");
		$message_obj->sendMessage($user_to, $body, $date);
	}
}

$current_user = new User($con, $user['username']);
$current_user_posts = $current_user->getNumPosts();

 ?>

 <div class="user_details column">
	<a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic']; ?>" width="200" height="auto"></a>

	<div class="user_details_left_right">
		<a id="profile_name" href="<?php echo $userLoggedIn; ?>">
			<?php 
				echo $user['first_name'] . " " . $user['last_name'] . "<br>";
			 ?>
		</a>

		<?php 
			echo $user['username'] . "<br><br>";
			echo "Posts: " . $current_user_posts . "<br>"; 
			echo "Likes: " . $user['num_likes'];
		?>
	</div>

</div>

<div class="main_column column" id="main_column">
	<?php 

	if ($user_to != "new") {
		echo "<h4>You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h4><hr>";
		echo "<div class='loaded_messages' id='scroll_messages'>" . $message_obj->getMessages($user_to) . "</div>";
	} else {
		echo "<h4>New Message</h4>";
	}

	 ?>

	 <div class="message_post">
	 	<form action="" method="POST">
	 		<?php 

	 		if ($user_to == "new") {
	 			echo "Select the friend you would like to message <br><br>";
	 			?>

	 			 To: <input type='text' onkeyup='getUsers(this.value, "<?php echo $userLoggedIn; ?>")' name='q' placeholder='Name' autocomplete='off' id='search_text_input'>

	 			 <?php
	 			echo "<div class='results'></div>";
	 		} else {
	 			echo "<textarea name='message_body' id='message_textarea' placeholder='Write your message'></textarea>";
	 			echo "<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>";
	 		}

	 		 ?>
	 	</form>
	 </div>

	 <script>
	 	var div = document.getElementById("scroll_messages");
	 	div.scrollTop = div.scrollHeight;
	 </script>
</div>

<div class="user_details column" id="conversations">
 	<h4>Conversations</h4>
 	<div class="loaded_conversations">
 		<?php echo $message_obj->getConvos(); ?>
 	</div>
 	<br>
 	<a href="message.php?username=new">New Message</a>
 </div>