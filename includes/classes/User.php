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
		$query = mysqli_query($this->con, "SELECT * FROM posts WHERE added_by='$username' AND deleted='no'");
		$count = mysqli_num_rows($query);
		$update = mysqli_query($this->con, "UPDATE users SET num_posts='$count' WHERE username='$username'");
		return $count;
	}

	public function isActive() {
		$username = $this->getUsername();
		$query = mysqli_query($this->con, "SELECT user_active FROM users WHERE username='$username'");
		$row = mysqli_fetch_array($query);

		if ($row['user_active'] == 'yes') {
			return true;
		} else {
			return false;
		}
	}

	public function isFriend($username_to_check) {
		$usernameComma = "," . $username_to_check . ",";

		// check if the friend username is in the friend array or it's the own username
		if ((strstr($this->user['friend_array'], $usernameComma) || $username_to_check == $this->user['username'])) {
			return true;
		} else {
			return false;
		}
	}

	public function getMutualFriends($user_to_check) {
		$mutualFriends = 0;
		$user_array = $this->user['friend_array'];
		$user_array_explode = explode(",", $user_array);  // split the string into an array

		$query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$user_to_check'");
		$row = mysqli_fetch_array($query);
		$user_to_check_array = $row['friend_array'];
		$user_to_check_array_explode = explode(",", $user_to_check_array);

		// count the mutual friends
		foreach ($user_array_explode as $i) {
			foreach ($user_to_check_array_explode as $j) {
				if ($i != "" && $i == $j) {
					$mutualFriends++;
				}
			}
		}
		return $mutualFriends;
	}

	public function getProfilePic() {
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT profile_pic FROM users WHERE username='$username'");
		$row = mysqli_fetch_array($query);
		return $row['profile_pic'];
	}

	public function getFriendArray() {
		$username = $this->user['username'];
		$query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$username'");
		$row = mysqli_fetch_array($query);
		return $row['friend_array'];
	}

	public function didReceiveRequest($user_from) {
		$user_to = $this->user['username'];
		$check_request_query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'" );
		if (mysqli_num_rows($check_request_query) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function didSendRequest($user_to) {
		$user_from = $this->user['username'];
		$check_request_query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_to='$user_to' AND user_from='$user_from'" );
		if (mysqli_num_rows($check_request_query) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function removeFriend($user_to_remove) {
		$logged_in_user = $this->user['username'];

		$query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$user_to_remove'");
		$row = mysqli_fetch_array($query);
		$friend_array_username = $row['friend_array'];

		$new_friend_array = str_replace($user_to_remove . ",", "", $this->user['friend_array']);
		$remove_friend = mysqli_query($this->con, "UPDATE users SET friend_array='$new_friend_array' WHERE username='$logged_in_user'");
		$new_friend_array = str_replace($this->user['username'] . ",", "", $friend_array_username);
		$remove_friend = mysqli_query($this->con, "UPDATE users SET friend_array='$new_friend_array' WHERE username='$user_to_remove'");
	}

	public function sendRequest($user_to) {
		$user_from = $this->user['username'];
		$query = mysqli_query($this->con, "INSERT INTO friend_requests VALUES('', '$user_to', '$user_from')");
	}
}

 ?>