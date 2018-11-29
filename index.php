<?php
include("includes/header.php");

// query in the database, specify the connection and write the SQL
// $query = mysqli_query($con, "INSERT INTO test VALUES('', 'Steven')");
// $query = mysqli_query($con, "INSERT INTO test VALUES(NULL, 'Steven')"); 

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
				echo "Posts: " . $user['num_posts'] . "<br>"; 
				echo "Likes: " . $user['num_likes'];
			?>
		</div>

	</div>

	<div class="main_column column">
		<form class="post_form" action="index.php" method="POST">
			<textarea name="post_text" id="post_text" placeholder="What's on your mind?"></textarea>
			<input type="submit" name="post" id="post_button" value="Post">
			<hr>
		</form>
	</div>	

</div>

</body>
</html>