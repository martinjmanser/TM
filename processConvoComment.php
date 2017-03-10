<?
include'db-session-setup.php';

	$commenterId = $_POST['commenterId'];
	$threadId = $_POST['threadId'];
	$commentBody = $_POST['convo_comment_body'];


	//Insert comment into DB
	$insert_convo_comment_sql = "INSERT INTO conversation (commenter_id, comment, thread_id) VALUES ($commenterId, \"$commentBody\", $threadId)";

	mysqli_query($conn, $insert_convo_comment_sql);
	//redirect to reflection page after comment is submitted
	$redirect = 'Location: conversationView.php?conversationId=' . $threadId;
	header($redirect);

?>