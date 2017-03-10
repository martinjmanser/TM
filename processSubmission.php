<?
	include'db-session-setup.php';

	$challengeName = $_POST['challenge_name'];
	//escape special characters 
	// $challengeName =  mysqli_real_escape_string($conn, $challengeName);

	$check_points_sql = "SELECT points FROM key_points WHERE user_id = $userId AND tribe_id = $tribeId";
	$check_points_result = mysqli_query($conn, $check_points_sql);
	$key_points = mysqli_fetch_assoc($check_points_result);

	if ($key_points['points'] >= 3) {

		$submit_challenge_sql = "INSERT INTO `challenges` (`name`, `user_id`) VALUES (\"$challengeName\", $userId)";
		$submit_challenge_result = mysqli_query($conn, $submit_challenge_sql);
		$challengeId = mysqli_insert_id($conn);

		if ($submit_challenge_result) { 
			// add challenge to tribe pool
			$insert_into_tribe_pool_sql = "INSERT INTO tribe_pools (tribe_id, challenge_id) VALUES ($tribeId, $challengeId)";
			mysqli_query($conn, $insert_into_tribe_pool_sql);

			// if challenge is submitted successfully, remove 3 keypoints from user
			$use_keypoints_sql = "UPDATE key_points SET points = points - 3 WHERE user_id = $userId AND tribe_id = $tribeId";
			mysqli_query($conn, $use_keypoints_sql);

			// Update keypoints session variable
			$check_points_sql = "SELECT points FROM key_points WHERE user_id = $userId AND tribe_id = $tribeId";
			$check_points_result = mysqli_query($conn, $check_points_sql);
			$key_points = mysqli_fetch_assoc($check_points_result);
			$_SESSION['keypoints'] = $key_points['points'];

			$status = 'success';
			$_SESSION['submittedChallenge'] = $challengeName;
		} else {
			$status = 'error';
		 }
	} else { 
		$status = 'keypoints';
	}

	$redirect = 'Location: submit.php?status=' . $status;
	header($redirect);
?>