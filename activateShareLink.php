<?php

	// all share links will be activated within the global tribe
	// therefore tribeId is hardcoded as global tribe(1) in this context
	$tribeId = 1;

	$challengeId = $_GET['cid'];
	$senderId = $_GET['sid'];
	$hash = $_GET['h'];

	// first check if the hash has been changed
	$query_string = "cid=" . $challengeId . "&sid=" . $senderId;
	$salt = "yousaltybro";
	$salted_query_string = $salt . $query_string;
	$recomputedHash = hash('sha256', $salted_query_string);
	if ($recomputedHash == $hash) {
		// add prompt to users shared prompts, check first if they have the prompt or have done it already(active or reflection submitted)

		// use a join by statement baby!
		$already_active_check_sql = "SELECT 1 FROM challenge_status WHERE challenge_id = $challengeId AND user_id = $userId AND active = 1 AND tribe_id = $tribeId";
		$already_active_check_result = $conn->query($already_active_check_sql);

		$already_reflected_check_sql = "SELECT 1 FROM reflections WHERE challenge_id = $challengeId AND user_id = $userId AND tribe_id = $tribeId";
		$already_reflected_check_result = $conn->query($already_reflected_check_sql);

		if ($already_active_check_result->num_rows == 1) {
			// prompt is already active, inform user somehow
			$_SESSION['share_link_status'] = "share link prompt is already active";
		} else if ($already_reflected_check_result->num_rows == 1) {
			// prompt has already been reflected upon, inform user somehow
			$_SESSION['share_link_status'] = "share link prompt has already been completed:" . "</br>" . "share link unlocked";

			// unlock share link if it hasn't been unlocked already
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
		} else {
			// prompt is cleared for activation

			//check if prompt is already in global tribe pool, if not, add it
			$check_tribe_pool_sql = "SELECT 1 FROM tribe_pools WHERE tribe_id = $tribeId AND challenge_id = $challengeId";
			$check_tribe_pool_result = $conn->query($check_tribe_pool_sql);

			if ($check_tribe_pool_result->num_rows == 0) {
				$insert_into_tribe_pool_sql = "INSERT INTO tribe_pools (tribe_id, challenge_id) VALUES ($tribeId, $challengeId)";
				mysqli_query($conn, $insert_into_tribe_pool_sql);
			}

			$insert_shared_prompt_sql = "INSERT INTO challenge_status (user_id, challenge_id, shared, tribe_id) VALUES ($userId, $challengeId, 1, $tribeId)";
			mysqli_query($conn, $insert_shared_prompt_sql);
			$_SESSION['share_link_status'] = "share link prompt successfully activated!";
		}
	} else {
		//return some sort of error message
		// this share link is invalid
		$_SESSION['share_link_status'] = "this share link is invalid";
	}




?>