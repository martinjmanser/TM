<?php

	$tribeId = $_SESSION['tribeId'];
	$convoNotifications = 'convoNotifications' . $tribeId;
	
	// only necessary when a new user registers and doesn't go through the login page
	if (!isset($_SESSION[$convoNotifications])) {
		$_SESSION[$convoNotifications] = "no new convo messages";
	}

	$notificationLinks = $_SESSION[$convoNotifications];

	if (is_array($notificationLinks)) {
		echo '<ul>';

		foreach ($notificationLinks as $link) {
			echo $link;
		}

		echo '</ul>';
	} else {
		echo $notificationLinks;
	}

?>