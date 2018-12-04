<?php 
include("includes/header.php");



 ?>

 <div class="main_column column" id="main_column">
 	<h4>Friend Requests</h4>

 	<?php 
 	
 	$query = mysqli_query($con, "SELECT * FROM friend_requests WHERE user_to='$userLoggedIn'");
 	if (mysqli_num_rows($query) == 0) {
 		echo "You have no friend requests.";
 	} else {
 		while ($row = mysqli_fetch_array($query)) {
 			$user_from = $row['user_from'];
 			$user_from_obj = new User($con, $user_from);

 			echo $user_from_obj->getFirstAndLastName() . " sent you a friend request.";

 			$user_from_friend_array = $user_from_obj->getFriendArray();
 			
 			if (isset($_POST['accept_request' . $user_from])) {
 				$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=IFNULL(CONCAT(friend_array, '$user_from,'), ',$user_from,') WHERE username='$userLoggedIn'");
 				$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=IFNULL(CONCAT(friend_array, '$userLoggedIn,'), ',$userLoggedIn,') WHERE username='$user_from'");

 				$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
 				echo "Successfully accept the friend request.";
 				header("Location: request.php");
 			}

 			if (isset($_POST['decline_request' . $user_from])) {
 				$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
 				echo "Successfully decline the friend request.";
 				header("Location: request.php");
 			}

 			?>

 			<form action="request.php" method="POST">
		 	 	<input type="submit" name="accept_request<?php echo $user_from; ?>" id="accept_button" value="Accept">
		 	 	<input type="submit" name="decline_request<?php echo $user_from; ?>" id="decline_button" value="Decline">
		 	 </form>

 			<?php
 		}
 	}

 	 ?>

 </div>