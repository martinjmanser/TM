<?
include'db-session-setup.php';

	//only used to redirect back to proper page
	$challengeId = $_POST['challengeId'];
	$notificationId = $_POST['notificationId'];

	$notifications = 'notifications' . $tribeId;

	$notificationArray = $_SESSION[$notifications];

	//unset session variable key for clicked notification
	unset($notificationArray[intval($notificationId)]);

	// update notification in db as having been clicked (clicked = 1)
	$update_notification_sql = "UPDATE notifications SET clicked = 1 WHERE id = $notificationId";
	mysqli_query($conn, $update_notification_sql);
	
	
	if (empty($notificationArray)) {
		$_SESSION[$notifications] = "nothing new";
	} else {
		$_SESSION[$notifications] = $notificationArray;
	}

	//redirect to reflection page after comment is submitted
	$redirect = 'Location: reflectionView.php?challengeId=' . $challengeId;
	header($redirect);
?>