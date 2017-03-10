<?
include'db-session-setup.php';
	
	$icebreakerId = $_POST['icebreakerId'];
	$museId = $_POST['museId'];
	$challengeId = $_POST['challengeId'];


	$check_thread_sql = "SELECT id FROM thread WHERE icebreaker = $icebreakerId AND muse = $museId AND challenge_id = $challengeId and tribe_id = $tribeId";
	$check_thread_result = $conn->query($check_thread_sql);
	if ($check_thread_result->num_rows == 1) {
		$row = $check_thread_result->fetch_assoc();
		$threadId = $row['id'];
	} else {
		$newthreadsql = "INSERT INTO thread (icebreaker, muse, challenge_id, tribe_id) VALUES ($icebreakerId, $museId, $challengeId, $tribeId)";
		$newthreadresult = $conn->query($newthreadsql);
		$threadId = mysqli_insert_id($conn);
	}

	//redirect to conversation page
	$redirect = 'Location: conversationView.php?conversationId=' . $threadId;
	header($redirect);
?>