<?
include'db-session-setup.php';

	// retrieve a random prompt that has not yet been completed by the user(no corresponding reflections)
	$rand_ch_sql = "SELECT id, name FROM challenges WHERE id NOT IN (SELECT challenge_id FROM reflections WHERE user_id = $userId AND tribe_id = $tribeId) AND id NOT IN (SELECT challenge_id FROM challenge_status WHERE user_id = $userId AND active = 1 AND tribe_id = $tribeId) AND id IN (SELECT challenge_id FROM tribe_pools WHERE tribe_id = $tribeId) ORDER BY RAND() LIMIT 1";
	$rand_ch_query = mysqli_query($conn, $rand_ch_sql);

	// if a challenge is retrieved, add it to the session variables and the challenge_status table
	if ($rand_ch_query->num_rows == 1) {
		$row = mysqli_fetch_assoc($rand_ch_query);
		$challengeId = $row['id'];

		$receive_challenge_sql = "INSERT INTO challenge_status (challenge_id, user_id, tribe_id) VALUES ($challengeId, $userId, $tribeId)";
		mysqli_query($conn, $receive_challenge_sql);

		$redirect = 'Location: challenge.php?challengeId=' . $challengeId;
	} else {
		$redirect = 'Location: challenge.php?noAvailablePrompts=1';
	}
	
	header($redirect);

?>