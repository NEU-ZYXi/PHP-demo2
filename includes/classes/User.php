<?php 

// PHP OOP
class User {

	// fields
	private $user;
	private $con;

	// constructor
	public function __construct($con, $username) {
		$this->con = $con;
		$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
		$this->user = mysqli_fetch_array($user_details_query);
	}

	public function getFirstAndLastName() {
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT first_name, last_name FROM users WHERE username='$username'");
		$row = mysqli_fetch_array($query);
		return $row['first_name']. " " . $row['last_name'];
	}

	public function getUsername() {
		return $this->user['username'];
	}

	public function getNumPosts() {
		$username = $this->getUsername();
		$query = mysqli_query($this->con, "SELECT num_posts FROM users WHERE username='$username'");
		$row = mysqli_fetch_array($query);
		return $row['num_posts'];
	}

	public function isActive() {
		$username = $this->getUsername();
		$query = mysqli_query($this->con, "SELECT user_active FROM users WHERE username='$username'");
		$row = mysqli_fetch_array($query);

		if ($row['user_active'] == 'yes') {
			return false;
		} else {
			return true;
		}
	}
}

 ?>