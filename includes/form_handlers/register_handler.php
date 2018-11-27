<?php 

// declare variables to prevent errors
$fname = "";  // first name
$lname = "";  // last name
$email = "";  // email
$email2 = "";  // email2
$password = "";  // password
$password2 = "";  // password2
$date = "";  // sign up date
$error_array = array();  // error messages

if (isset($_POST['reg_button'])) {  // isset检测变量是否已设置，$_POST变量用于收集来自method="POST"的表单中的值

	// registration form values
	$fname = strip_tags($_POST['reg_fname']);  // strip_targs removes any html tags and remain only the text
	$fname = str_replace(' ', '', $fname);  // remove spaces
	$fname = ucfirst(strtolower($fname));  // convert all the letters to lower case and keep the first letter as upper case
	$_SESSION['reg_fname'] = $fname;  // store the first name into session variable
	
	$lname = strip_tags($_POST['reg_lname']);
	$lname = str_replace(' ', '', $lname);
	$lname = ucfirst(strtolower($lname));
	$_SESSION['reg_lname'] = $lname;

	$email = strip_tags($_POST['reg_email']);
	$email = str_replace(' ', '', $email);
	$_SESSION['reg_email'] = $email;

	$email2 = strip_tags($_POST['reg_email2']);
	$email2 = str_replace(' ', '', $email2);
	$_SESSION['reg_email2'] = $email2;

	$password = strip_tags($_POST['reg_password']);

	$password2 = strip_tags($_POST['reg_password2']);

	$date = date("Y-m-d");  // get current date

	// check if emails match
	if ($email == $email2) {

		// check if email is in valid format
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$email = filter_var($email, FILTER_VALIDATE_EMAIL);

			// check if email already exists
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");
			// count the number of rows returned
			$num_rows = mysqli_num_rows($e_check);
			if ($num_rows > 0) {
				array_push($error_array, "Email already exists<br>");
			}

		} else {
			array_push($error_array, "Invalid Email format<br>");
		}

	} else {
		array_push($error_array, "Emails don't match<br>");
	}

	// check the length of first name
	if (strlen($fname) > 25 || strlen($fname) < 2) {
		array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
	}

	// check the length of last name
	if (strlen($lname) > 25 || strlen($lname) < 2) {
		array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
	}

	// check if passwords match
	if ($password != $password2) {
		array_push($error_array, "Your password don't match<br>");
	} else {

		// check the format of password
		if (preg_match('/[^A-Za-z0-9]/', $password)) {
			array_push($error_array, "Your password can only contain Englist characters or numbers<br>");
		}
	}

	// check the length of password
	if (strlen($password) > 30 || strlen($password) < 5) {
		array_push($error_array, "Your password must be between 5 and 30 characters<br>");
	}

	// no error message, go to the next step
	if (empty($error_array)) {
		$password = md5($password);  // encrypt password before sending it to database

		$username = strtolower($fname . "_" . $lname);  // generate a username by concatenating first name and last name
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
		
		// if username exists, add number after username
		$i = 0;
		while (mysqli_num_rows($check_username_query) != 0) {
			$i++;
			$username = $username . "_" . $i;
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
		}

		// randomly generate a default profile picture
		$rand = rand(1, 2);
		if ($rand == 1) {
			$profile_pic = "assets/images/profile_pics/defaults/default_profile_head";
		} else if ($rand == 2) {
			$profile_pic = "assets/images/profile_pics/defaults/default_profile_head2";
		}

		// submit the form and insert it into database
		$query = mysqli_query($con, "INSERT INTO users VALUES('', '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'yes', ',')");

		// successful registration message
		array_push($error_array, "<span style='color: #14C800;'>Successfully registered</span><br>");

		// clear session variables
		$_SESSION['reg_fname'] = "";
		$_SESSION['reg_lname'] = "";
		$_SESSION['reg_email'] = "";
		$_SESSION['reg_email2'] = "";

	}
}

 ?>