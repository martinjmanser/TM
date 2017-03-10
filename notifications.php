<?php

	//get a list of all tribes that current user is a member of
	$pull_tribes_sql = "SELECT tribe_id FROM tribe_membership WHERE user_id = $userId";
	$pull_tribes_result = $conn->query($pull_tribes_sql);

	while($tribe = $pull_tribes_result->fetch_assoc()) {
		$tribeId = $tribe['tribe_id'];

		//check for notifications
		//check for new comments and add notification

		// initialize array to store notification links
		$notificationLinks = array();

		// add stored notifications to notification link array
		$retrieve_comment_notification_sql = "SELECT id, query_string FROM notifications WHERE user_id = $userId AND notification_type = 0 AND clicked = 0 AND tribe_id = $tribeId";
		$retrieve_comment_notification_result = $conn->query($retrieve_comment_notification_sql);
		if ($retrieve_comment_notification_result->num_rows > 0) {

			while($commentNotification = $retrieve_comment_notification_result->fetch_assoc()) {

				$commentNotificationLink = '<li><form method="post"  action="processNotificationLink.php">
							<input type="hidden" name="challengeId" value="' . $commentNotification['query_string'] .  '" />
							<input type="hidden" name="notificationId" value="' . $commentNotification['id'] .  '" />
							<input type="submit" class="btn-link" value="new comment' . $commentNotification['query_string'] . "old" . ' " />
						</form></li>';
				$notificationLinks[$commentNotification['id']] = $commentNotificationLink;
			}
		}

		// retreive comments posted to current user since their last login
		$new_comments_sql = "SELECT challenge_id FROM comments WHERE commentee_id = $userId AND tribe_id = $tribeId AND creation_date > (SELECT login_date FROM login_history WHERE user_id = $userId ORDER BY id DESC LIMIT 1) GROUP BY challenge_id";
		$new_comments_result = $conn->query($new_comments_sql);
		if ($new_comments_result->num_rows > 0) {
			
			while($new_comment = $new_comments_result->fetch_assoc()) {

				$newComment = $new_comment['challenge_id'];

				// insert new notification into db
				$insert_comment_notification_sql = "INSERT INTO notifications (user_id, notification_type, query_string, tribe_id) VALUES($userId, 0, $newComment, $tribeId)";
				mysqli_query($conn, $insert_comment_notification_sql);

				// get id of notification
				$notificationId = mysqli_insert_id($conn);

				$notification_link = '<li><form method="post"  action="processNotificationLink.php">
							<input type="hidden" name="challengeId" value="' . $newComment .  '" />
							<input type="hidden" name="notificationId" value="' . $notificationId .  '" />
							<input type="submit" class="btn-link" value="new comment' . $newComment . ' " />
						</form></li>';
				$notificationLinks[$notificationId] = $notification_link;

			}
		}

		// set session id variable as notification string with tribe id appended
		$notifications = 'notifications' . $tribeId;

		if (!empty($notificationLinks)) {
			$_SESSION[$notifications] = $notificationLinks;
		} else {
			$_SESSION[$notifications] = "nothing new";
		}

			
		// initialize array to store notification links
		$convoNotificationLinks = array();

		// add stored notifications to notification link array
		$retrieve_convo_notification_sql = "SELECT id, query_string FROM notifications WHERE user_id = $userId AND notification_type = 1 AND clicked = 0 AND tribe_id = $tribeId";
		$retrieve_convo_notification_result = $conn->query($retrieve_convo_notification_sql);
		if ($retrieve_convo_notification_result->num_rows > 0) {

			while($convoNotification = $retrieve_convo_notification_result->fetch_assoc()) {

				$convoNotificationLink = '<li><form method="post"  action="processConvoNotificationLink.php">
							<input type="hidden" name="convoLink" value="' . $convoNotification['query_string'] .  '" />
							<input type="hidden" name="notificationId" value="' . $convoNotification['id'] .  '" />
							<input type="submit" class="btn-link" value="new message' . $convoNotification['query_string'] . "old" . ' " />
						</form></li>';
				$convoNotificationLinks[$convoNotification['id']] = $convoNotificationLink;
			}
		}
			

		// retreive convo messages posted to current user since their last login
		// select the challenge_id, muse id and icebreaker id from thread and conversation where the creation date of the message is after the users last login
		// and the thread_id of the message is the same as the thread id and the user is either the icebreaker or the muse, but not the commenter (therefore current user is the commentee)
		// group by challenge_id, muse and icebreaker to remove duplicates
		$new_messages_sql = "SELECT c.thread_id as thread_id FROM thread t, conversation c WHERE t.tribe_id = $tribeId AND c.creation_date > (SELECT login_date FROM login_history WHERE user_id = $userId ORDER BY id DESC LIMIT 1) AND t.id = c.thread_id AND $userId IN(t.muse, t.icebreaker) AND c.commenter_id != $userId GROUP BY c.thread_id";
		$new_messages_result = $conn->query($new_messages_sql);
		if ($new_messages_result->num_rows > 0) {
			

			while($new_message = $new_messages_result->fetch_assoc()) {

				$newMessage = $new_message['thread_id'];

				// insert new notification into db
				$insert_message_notification_sql = "INSERT INTO notifications (user_id, notification_type, query_string, tribe_id) VALUES($userId, 1, $newMessage, $tribeId)";
				mysqli_query($conn, $insert_message_notification_sql);

				// get id of notification
				$convoNotificationId = mysqli_insert_id($conn);

				$convo_notification_link = '<li><form method="post"  action="processConvoNotificationLink.php">
							<input type="hidden" name="convoLink" value="' . $newMessage .  '" />
							<input type="hidden" name="notificationId" value="' . $convoNotificationId .  '" />
							<input type="submit" class="btn-link" value="new message' . $newMessage . '" />
						</form></li>';
				$convoNotificationLinks[$convoNotificationId] = $convo_notification_link;
			}
		}

		// set session id variable as notification string with tribe id appended
		$convoNotifications = 'convoNotifications' . $tribeId;

		if (!empty($convoNotificationLinks)) {
			$_SESSION[$convoNotifications] = $convoNotificationLinks;
		} else {
			$_SESSION[$convoNotifications] = "no new convo messages";
		}

	}



		

?>