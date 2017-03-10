<?php

include 'db-user-session-setup.php';

	$tribeId = $_POST['tribeId'];
	//set tribe id into session variable
	$_SESSION['tribeId'] = $tribeId;

	//load key points session variable
	$check_points_sql = "SELECT points FROM key_points WHERE user_id = $userId AND tribe_id = $tribeId";
	$check_points_result = mysqli_query($conn, $check_points_sql);
	$key_points = mysqli_fetch_assoc($check_points_result);
	$_SESSION['keypoints'] = $key_points['points'];

	//redirect to challenge page after tribe is selected
	$redirect = 'Location: challenge.php';
	header($redirect);


?>