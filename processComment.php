<?
include'db-session-setup.php';

	$reflectionId = $_POST['reflectionId'];
	$commenterId = $_POST['commenterId'];
	$commenteeId = $_POST['commenteeId'];
	$commentBody = $_POST['comment_body'];
	$challengeId = $_POST['challengeId'];


	//Insert comment into DB
	$insert_comment_sql = "INSERT INTO comments (content_id, content_type, commenter_id, commentee_id, challenge_id, comment, tribe_id) VALUES ($reflectionId, 0, $commenterId, $commenteeId, $challengeId, \"$commentBody\", $tribeId)";

	mysqli_query($conn, $insert_comment_sql);
	//redirect to reflection page after comment is submitted
	$redirect = 'Location: reflectionView.php?challengeId=' . $challengeId;
	header($redirect);

?>