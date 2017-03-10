<?
include'db-session-setup.php';

	$challengeId = $_POST['challengeId'];
	$statusId = $_POST['statusId'];

	$complete_challenge_sql = "UPDATE challenge_status SET `status` = 1 WHERE id = $statusId";
	mysqli_query($conn, $complete_challenge_sql);

	//redirect to challenge page
	$redirect = 'Location: challenge.php?challengeId=' . $challengeId;
	header($redirect);

?>