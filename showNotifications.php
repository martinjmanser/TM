<?php

	$tribeId = $_SESSION['tribeId'];
	$notifications = 'notifications' . $tribeId;

	// only necessary when a new user registers and doesn't go through the login page
	if (!isset($_SESSION[$notifications])) {
		$_SESSION[$notifications] = "nothing new";
	}

	$notificationLinks = $_SESSION[$notifications];

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