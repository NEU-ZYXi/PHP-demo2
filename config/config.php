<?php

ob_start();  // turn on output buffering

session_start();  // call a session which will store the value of all the session variables inside

$timezone = date_default_timezone_set("America/Los_Angeles");  // set the default timezone

// connection to database, four parameters: hostname, username, password, database name
$con = mysqli_connect("localhost", "root", "", "Demo_social");

// check the connection
if (mysqli_connect_errno()) {
	echo "Failed to coonect: " + ysqli_connect_errno();
}

?>