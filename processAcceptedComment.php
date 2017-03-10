<?
include'db-session-setup.php';


	$commentId = $_POST['commentId'];

	//only used to redirect back to proper page
	$challengeId = $_POST['challengeId'];


	//update comment with accepted field = 1
	$update_comment_sql = "UPDATE comments SET accepted = 1 WHERE id = $commentId";

	mysqli_query($conn, $update_comment_sql);
	//redirect to reflection page after comment is submitted
	$redirect = 'Location: reflectionView.php?challengeId=' . $challengeId;
	header($redirect);

?>