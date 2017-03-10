<?
session_start();
include'db-setup.php';


	$reflectionBody = $_POST['reflection_body'];
	$challengeId = $_POST['challengeId'];

	// Insert reflection into DB
	$insert_reflect_sql = "INSERT INTO teaser_reflections (challenge_id, reflection) VALUES ($challengeId, \"$reflectionBody\")";
	mysqli_query($conn, $insert_reflect_sql);
	$reflectionId = mysqli_insert_id($conn);

	$_SESSION['teaserReflectionId'] = $reflectionId;
	$_SESSION['teaserReflectionText'] = $reflectionBody;

	//redirect back to index page after teaser reflection is submitted
	$redirect = 'Location: index.php';
	header($redirect);

?>