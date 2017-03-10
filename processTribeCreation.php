<?
include'db-user-session-setup.php';

	$tribeName = $_POST['tribe_name'];
	$globalTribeId = 1;

	$check_points_sql = "SELECT points FROM key_points WHERE user_id = $userId AND tribe_id = $globalTribeId";
	$check_points_result = mysqli_query($conn, $check_points_sql);
	$key_points = mysqli_fetch_assoc($check_points_result);

	if ($key_points['points'] >= 5) {

		// create tribe and add user to tribe membership table

		$submit_tribe_sql = "INSERT INTO tribes (name) VALUES (\"$tribeName\")";
		$submit_tribe_result = mysqli_query($conn, $submit_tribe_sql);
		$newTribeId = mysqli_insert_id($conn);

		if ($submit_tribe_result) {

			// if tribe is created successfully, remove 5 keypoints from user
			$use_keypoints_sql = "UPDATE key_points SET points = points - 5 WHERE user_id = $userId AND tribe_id = $globalTribeId";
			mysqli_query($conn, $use_keypoints_sql);

			// Update keypoints session variable
			$check_points_sql = "SELECT points FROM key_points WHERE user_id = $userId AND tribe_id = $globalTribeId";
			$check_points_result = mysqli_query($conn, $check_points_sql);
			$key_points = mysqli_fetch_assoc($check_points_result);
			$_SESSION['global_keypoints'] = $key_points['points'];


			// add user as a member of the tribe created
			$insert_into_tribe_membership_sql = "INSERT INTO tribe_membership (user_id, tribe_id) VALUES ($userId, $newTribeId)";
			mysqli_query($conn, $insert_into_tribe_membership_sql);


			// create new key_points entry for tribe founder for his tribe
			$newuser_points_sql = "INSERT INTO key_points (user_id, tribe_id) VALUES ('$userId', $newTribeId)";
			$newuser_points_query = $conn->query($newuser_points_sql);



			$status = 'success';
			$_SESSION['tribe_name'] = $tribeName;
		} else {
			$status = 'error';
		 }
	} else { 
		$status = 'keypoints';
	}



	$redirect = 'Location: index.php?status=' . $status;
	header($redirect);

?>