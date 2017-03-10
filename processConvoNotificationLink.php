<?
include'db-session-setup.php';

	//only used to redirect back to proper page

	$convoLink = $_POST['convoLink'];
	$notificationId = $_POST['notificationId'];

	$convoNotifications = 'convoNotifications' . $tribeId;

	$convoNotificationArray = $_SESSION[$convoNotifications];

	//unset session variable key for clicked notification
	unset($convoNotificationArray[intval($notificationId)]);

	// update notification in db as having been clicked (clicked = 1)
	$update_notification_sql = "UPDATE notifications SET clicked = 1 WHERE id = $notificationId";
	mysqli_query($conn, $update_notification_sql);

	if (empty($convoNotificationArray)) {
		$_SESSION[$convoNotifications] = "no new convo messages";
	} else {
		$_SESSION[$convoNotifications] = $convoNotificationArray;
	}


	//redirect to reflection page after comment is submitted
	$redirect = 'Location: conversationView.php?conversationId=' . $convoLink;
	header($redirect);

?>