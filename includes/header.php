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
 			<a id="profile_icon" href="<?php echo $userLoggedIn; ?>">
 				<i class="fas fa-user"></i>
 				<?php echo $user['first_name']; ?>
 			</a>
 			<a href="index.php">
 				<i class="fas fa-home"></i>
 			</a>
 			<a href="message.php">
 				<i class="fas fa-comments"></i>
 			</a>
 			<a href="#">
 				<i class="fas fa-bell"></i>
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
 	</div>

 	<div class="wrapper">
 		


