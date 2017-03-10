<?
include'db-session-setup.php';

	$challengeId = $_POST['challengeId'];


	$query_string = "cid=" . $challengeId . "&sid=" . $userId;

	$salt = "yousaltybro";

	$salted_query_string = $salt . $query_string;

	$hash = hash('sha256', $salted_query_string);


	//insert share link into the db
	$share_link_insert_sql = "INSERT INTO share_links (user_id, challenge_id, query_string, salt, hash) VALUES ($userId, $challengeId, '$query_string', '$salt', '$hash')";
	mysqli_query($conn, $share_link_insert_sql);


	//redirect to reflection page after comment is submitted
	$redirect = 'Location: viewReflections.php';
	header($redirect);
?>