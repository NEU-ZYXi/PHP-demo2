<?php 

if (isset($_POST['login_button'])) {

	$email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);  // sanitize email format
	$_SESSION['log_email'] = $email;  // store email into session variable

	$password = md5($_POST['log_password']);

	// query the user with email and password
	$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
	$check_login_query = 0;
	if ($check_database_query) {
		$check_login_query = mysqli_num_rows($check_database_query);
	}

	// check if there exists the correct user in the database
	if ($check_login_query == 1) {
		$row = mysqli_fetch_array($check_database_query);  // access the query result and store it into an array
		$username = $row['username'];

		// check whether the user is still active, if not, reopen it by updating the value in the user_active column
		$user_active_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_active='no'");
		if (mysqli_num_rows($user_active_query) == 1) {
			$reopen_account = mysqli_query($con, "UPDATE users SET user_active='yes' WHERE email='$email'");
		}

		$_SESSION['username'] = $username;
		header("Location: index.php");  // if logged in, redirect to index page
		exit();
	} else {
		array_push($error_array, "Email or password is incorrect<br>");
	}
}

 ?>