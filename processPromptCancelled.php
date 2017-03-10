<?
include'db-session-setup.php';

	$statusId = $_POST['statusId'];

	$cancel_challenge_sql = "UPDATE `challenge_status` SET `active` = 0 WHERE id = $statusId";
	mysqli_query($conn, $cancel_challenge_sql);

	//redirect to challenge page
	$redirect = 'Location: challenge.php';
	header($redirect);

?>