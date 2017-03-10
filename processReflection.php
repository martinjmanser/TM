<?
include'db-session-setup.php';

	$reflectionBody = $_POST['reflection_body'];
	$challengeId = $_POST['challengeId'];
	$statusId = $_POST['statusId'];
	$shared = $_POST['shared'];


	// Insert reflection into DB
	$insert_reflect_sql = "INSERT INTO reflections (user_id, challenge_id, reflection, tribe_id) VALUES ($userId, $challengeId, \"$reflectionBody\", $tribeId)";
	mysqli_query($conn, $insert_reflect_sql);
	// Increment key points by 1
	$add_keypoint_sql = "UPDATE key_points SET points = points + 1 WHERE user_id = $userId AND tribe_id = $tribeId";
	mysqli_query($conn, $add_keypoint_sql);
	// Update keypoints session variable
	$check_points_sql = "SELECT points FROM key_points WHERE user_id = $userId AND tribe_id = $tribeId";
	$check_points_result = mysqli_query($conn, $check_points_sql);
	$key_points = mysqli_fetch_assoc($check_points_result);
	$_SESSION['keypoints'] = $key_points['points'];
	// Update challenge status to show reflection submission
	$submit_reflection_sql = "UPDATE `challenge_status` SET `active` = 0 WHERE id = $statusId";
	mysqli_query($conn, $submit_reflection_sql);

	//if prompt was shared, generate share link automatically
	if ($shared == 1) {

		// check if share link is already in db
		$share_link_check_sql = "SELECT 1 FROM share_links WHERE user_id = $userId AND challenge_id = $challengeId";
		$share_link_check_result = $conn->query($share_link_check_sql);

		if ($share_link_check_result->num_rows == 0) {
			//copied from processShareLink.php, could turn this into a function/utility in the future
			$query_string = "cid=" . $challengeId . "&sid=" . $userId;

			$salt = "yousaltybro";

			$salted_query_string = $salt . $query_string;

			$hash = hash('sha256', $salted_query_string);


			//insert share link into the db
			$share_link_insert_sql = "INSERT INTO share_links (user_id, challenge_id, query_string, salt, hash) VALUES ($userId, $challengeId, '$query_string', '$salt', '$hash')";
			mysqli_query($conn, $share_link_insert_sql);
		}
	}

	//redirect to reflection page after reflection is submitted
	$redirect = 'Location: reflectionView.php?challengeId=' . $challengeId;
	header($redirect);

?>