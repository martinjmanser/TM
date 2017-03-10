<?php 
session_start();

$index = 1;

include 'db-setup.php';

if (isset($_SESSION['id']) && isset($_SESSION['name']) ) {
	header('Location: index.php');
	exit;
}

if (isset($_POST['registering'])) {
	$newusername = $_POST['newusername'];
	$newpassword = $_POST['newpassword'];
	$newpassword2 = $_POST['newpassword2'];


	//count records in user table with inputted user email
	// grab id and password of user if record exists, null otherwise?
	//TD: might want to separate check for user existance and grab of user fields
	$sql = "SELECT COUNT(*) as total FROM users WHERE email = '$newusername'";
	$query = $conn->query($sql);

	//i think I need an existance check here
	$result = mysqli_fetch_assoc($query);

	// if user does not exist, display an error 
	//TD: (should i check for null as well?)
	//TD: could also check for if it is not equal to 1, but will likely need a different error
	if ($result['total'] > 0) { ?>
		<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
		<br />
		<? echo "Username already exists, please try again <br /><br />"; ?>
		</div> 	

 	<? } else if($newpassword != $newpassword2) { ?>
		<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
		<br />
		<? echo "Passwords do not match, please try again <br /><br />"; ?>
		</div> 	

	<? } else if($newpassword == '') { ?>
		<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
		<br />
		<? echo "Password requirements not met, please try again <br /><br />"; ?>
		</div>

	<? } else {
		// username and password check out
		// add user to database
		$newuser_sql = "INSERT INTO users (email, password) VALUES ('$newusername', '$newpassword')";
		$newuser_query = $conn->query($newuser_sql);

		$userId = mysqli_insert_id($conn);

		//add record to login_history
		$record_login_sql = "INSERT INTO login_history (user_id) VALUES ($userId)";
		mysqli_query($conn, $record_login_sql);

		//add user to global tribe
		$newuser_tribe_assimilation_sql = "INSERT INTO tribe_membership (user_id, tribe_id) VALUES ('$userId', 1)";
		$newuser_tribe_assimilation_query = $conn->query($newuser_tribe_assimilation_sql);

		// global tribe(1) is hardcoded as first tribe new user joins
		$newuser_points_sql = "INSERT INTO key_points (user_id, tribe_id) VALUES ('$userId', 1)";
		$newuser_points_query = $conn->query($newuser_points_sql);


		//log user in
		$_SESSION['id'] = $userId;
		$_SESSION['name'] = $newusername;

		// check for share link and run activation script if necessary
		if (isset($_GET['cid'])) {
			include 'activateShareLink.php';
		}

		//send user where they need to go
		header('Location: index.php?justLoggedIn=1');
		exit;
	}
}


//old incorrect version
// if (isset($_POST['loggedin'])) {
// 	$user = $_POST['user'];
// 	$sql = "SELECT COUNT(*) as total, id FROM users WHERE name = '$user'";
// 	$query = $conn->query($sql);
// 	$result = mysqli_fetch_assoc($query);

// 	if ($result['total'] == 0) {
// 		$sql = "INSERT INTO users (name) VALUES ('$user')";
// 		$conn->query($sql);
// 		$_SESSION['id'] = mysqli_insert_id($conn);
// 	} else {
// 		$_SESSION['id'] = $result['id'];
// 	}

// 	$_SESSION['name'] = $user;

// 	header('Location: http://localhost:8888/whynot/challenge.php?justLoggedIn=1');
// 	exit;
// }

include 'header.php';
include 'navbar.php';

?>

		<div id="container">
			<h1>Register</h1><br /> <?php
			if (isset($_GET['auth'])) { ?>
				<div style="margin-top:40px;margin:0 auto" class="alert alert-danger">
				You have to log in to do that.
				</div>
				<br /><br />
			<?php } else if (isset($_GET['justLoggedOut'])) { ?>
				<div style="margin-top:40px;margin:0 auto;" class="alert alert-success">
				You've been logged out successfully.
				</div>
				<br /><br />
			<?php } ?>
			<p>Enter a username and password to register.</p><br /><br />
			<form method="post">
				<input type="hidden" name="registering" value="1" />
				<div class="input-group">
					<input type="text" class="form-control" name="newusername" placeholder="Username" style="height:50px;border-bottom-left-radius: 0" />
					<input type="password" class="form-control" name="newpassword" placeholder="Password" style="height:50px;border-bottom-left-radius: 4px;" />
					<input type="password" class="form-control" name="newpassword2" placeholder="Re-Type Password" style="height:50px;border-bottom-left-radius: 4px;" />
					<span class="input-group-btn">
						<input type="submit" value="Register" style="height: 150px;" class="btn btn-primary" />
					</span>
				</div>
			</form>
		</div>
		

<br />
<br />
<br />
<br />
<br />
</body>
</html>