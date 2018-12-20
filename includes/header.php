<?php 
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");

// make sure that the user has logged in the go to other pages
if (isset($_SESSION['username'])) {
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
} else {
	header("Location: register.php");
}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Welcome to My Blog</title>
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 	<script src="assets/js/bootstrap.js"></script>
 	<script src="assets/js/bootbox.min.js"></script>
 	<script src="assets/js/demo.js"></script>
 	<script src="assets/js/jquery.jcrop.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>

 	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
 	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
 	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
 	<link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css" />
 </head>
 <body>
 
 	<div class="top_bar">
 		<div class="logo">
 			<a href="index.php">Xi Blog</a>
 		</div>

 		<nav>

 			<?php 
 			$messages = new Message($con, $userLoggedIn);  // unread messages
 			$num_messages = $messages->getUnreadNumber();
 			 ?>

 			<a id="profile_icon" href="<?php echo $userLoggedIn; ?>">
 				<i class="fas fa-user"></i>
 				<?php echo $user['first_name']; ?>
 			</a>
 			<a href="index.php">
 				<i class="fas fa-home"></i>
 			</a>
 			<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
 				<i class="fas fa-bell"></i>
 				<?php
 				if ($num_messages >= 1) {
 					echo '<span class="notification_badge" id="unread_number">' . $num_messages . '</span>';
 				}
 				?>
 			</a>
 			<a href="message.php">
 				<i class="fas fa-comments"></i>
 			</a>
 			<a href="request.php">
 				<i class="fas fa-user-friends"></i>
 			</a>
 			<a href="#">
 				<i class="fas fa-cog"></i>
 			</a>
 			<a href="includes/handlers/logout.php">
 				<i class="fas fa-sign-out-alt"></i>
 			</a>
 		</nav>

 		<div class="dropdown_data_window" style="height: 0px;"></div>
 		<input type="hidden" id="dropdown_data_type" value="">
 	</div>

 	<script>
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';

		$(document).ready(function() {

			$('.dropdown_data_window').scroll(function() {
				var innerHeight = $('.dropdown_data_window').innerHeight();  // div containing drop down data
				var scrollTop = $('.dropdown_data_window').scrollTop();
				var page = $('.dropdown_data_window').find('.nextPageDropdownData').val();
				var noMoreDropdownData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

				if ((scrollTop + innerHeight >= $('.dropdown_data_window')[0].scrollHeight) && noMoreDropdownData == 'false') {
					var pageName;  // hold name of page to send ajax request to
					var type = $('#dropdown_data_type').val();

					if (type == 'notification') {
						pageName = "ajax_load_notifications.php";
					} else if (type == 'message') {
						pageName = "ajax_load_messages.php";
					}

					var ajaxReq = $.ajax({
						url: "includes/handlers/" + pageName,
						type: "POST",
						data: "page=" + page + "&userLoggedIn=" + userLoggedIn, 
						cache: false,

						success: function(response) {
							$('.dropdown_data_window').find('.nextPageDropdownData').remove();  // remove current next page
							$('.dropdown_data_window').find('.noMoreDropdownData').remove();
							$('.dropdown_data_window').append(response);
						}
					});
				}

				return false;
			});
		});
	</script>

 	<div class="wrapper">
 		


