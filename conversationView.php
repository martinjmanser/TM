<?php 
	include'db-session-setup.php';
	include 'header.php';

	$userId = $_SESSION['id'];

	if (!isset($_GET['conversationId'])) {
		echo "you need to pick a conversation. maybe redirect here...";
		exit;
	} else {

		$access = false;

		$threadId = $_GET['conversationId'];
		$grab_thread_sql = "SELECT icebreaker, muse, challenge_id, tribe_id FROM thread WHERE id = $threadId";
		$grab_thread_result = $conn->query($grab_thread_sql);
		if ($grab_thread_result->num_rows == 1) {
			$thread = $grab_thread_result->fetch_assoc();
			$challengeId = $thread['challenge_id'];
			$museId = $thread['muse'];
			$icebreakerId = $thread['icebreaker'];
			$threadTribeId = $thread['tribe_id'];

			if ($threadTribeId == $tribeId) {
				if ($museId == $userId) {
					$iAmTheMuse = true;
					$access = true;
				} else if ($icebreakerId == $userId) {
					$iAmTheMuse = false;
					$access = true;
				}
			}

		}

		if ($access == false) { ?>
			<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
				<?php echo "<br />You do not have access to this conversation<br /><br />"; ?>
			</div> <?
			exit;
		}

	}

	include 'navbar.php';
?>

		<div id="wrapper">
			<div id="container">
				<!-- Display Reflection from selected Challenge  -->
				<?php

				$challenge_name_sql = "SELECT name FROM challenges WHERE id = $challengeId";
				$challenge_name_result = $conn->query($challenge_name_sql);
				$challenge_name = mysqli_fetch_row($challenge_name_result);

				echo "<h1>View Conversations</h1><h3>Prompt: ", $challenge_name[0], "</h3><br />";

				// if you are the user that posted the original reflection that was commented on
				if ($iAmTheMuse) {
					$usql = "SELECT reflection, id FROM reflections WHERE challenge_id = $challengeId AND user_id = $userId AND tribe_id = $tribeId";
					$uresult = $conn->query($usql);

					//get reflection
					$row = $uresult->fetch_assoc();
					$ureflection = $row['reflection'];
					$ureflectionId = $row['id'];


					?>

					<div class="formy yourefy">
						<div class="input-group" style="width:100%;">
						<br />

						<?
							echo $ureflection;
						?>

						<br />
						<br />
						</div><?
						//get comments for users' reflection if they exist
						$commentsql = "SELECT comment, id FROM comments WHERE content_type = 0 AND content_id = $ureflectionId AND commenter_id = $icebreakerId AND tribe_id = $tribeId";
						$commentresult = $conn->query($commentsql);

						// display all comments for this reflection
						$comment = $commentresult->fetch_assoc(); ?>

						<div class="formy comment tcommenty">
							<div class="input-group" style="width:100%;">
								<ul><li><p><em><?

									echo $comment["comment"];

								?></em></p></li></ul> <?

								$commentId = $comment["id"];
								//get your response to above comment if it exists
								$commentreplysql = "SELECT comment FROM comments WHERE content_type = 1 AND content_id = $commentId AND tribe_id = $tribeId";
								$commentreplyresult = $conn->query($commentreplysql);
								$commentreply = $commentreplyresult->fetch_assoc(); ?> 

								<div class="formy comoncom ucommenty" style="margin-right:10px">
									<div class="input-group" style="width:100%;"><ul><li><p><?

									echo $commentreply["comment"];

									?></p></li></ul></div>
								</div>
							</div>
						</div><?

					?></div><?
				} else {

					// get original reflection that prompted conversation
					$sql = "SELECT reflection, user_id, id FROM reflections WHERE challenge_id = $challengeId AND user_id = $museId AND tribe_id = $tribeId";
					$result = $conn->query($sql);
					$row = $result->fetch_assoc();

					?> 
					<div class="formy theirrefy">
					<div class="input-group" style="width:100%;">
						<div id="list2"> 
					<ul> <?

					echo '<li><p><em>';
						echo $row["reflection"];					
					echo '</em></p></span></li>'; 

					//get my comment for this reflection
					$reflectionId = $row["id"];
					$mycommentsql = "SELECT comment, id FROM comments WHERE content_type = 0 AND content_id = $reflectionId AND commenter_id = $userId AND tribe_id = $tribeId";
					$mycommentresult = $conn->query($mycommentsql);

					// display my comment for this reflection
					$mycomment = $mycommentresult->fetch_assoc(); ?>
					<div class="formy comment ucommenty" style="margin-right:10px">
						<div class="input-group" style="width:100%;">
							<ul><li><p><?

								echo $mycomment["comment"];

							?></p></li></ul><?

							//get response to my comment
							$mycommentId = $mycomment["id"];
							$comreplysql = "SELECT comment, accepted, id FROM comments WHERE content_type = 1 AND content_id = $mycommentId and tribe_id = $tribeId";
							$comresult = $conn->query($comreplysql);
							$com = $comresult->fetch_assoc();?>

							<div class="formy comoncom theircommenty">
								<div class="input-group" style="width:100%;">
									<ul><li><p><em><?

										echo $com["comment"]; ?>

									</em></p></li></ul>
								</div>
							</div><?
						?></div>
					</div><?

		  		    ?> </ul></div></div></div><br /><br /><br /><?	

				} ?>

			</div>
			<!--  maybe change this to a new container type -->
			<div id="container"><?

				$pullthreadsql = "SELECT comment, commenter_id FROM conversation WHERE thread_id = $threadId ORDER BY id";
				$pullthreadresult = $conn->query($pullthreadsql);
				if($pullthreadresult->num_rows > 0) {

					while($row = $pullthreadresult->fetch_assoc()) {
						if($row["commenter_id"] == $userId) {
							$commenty = "ucommenty";
						} else {
							$commenty = "theircommenty";
						}?>
						<div class="formy comment <?=$commenty?>" style="margin-right:10px">
							<div class="input-group" style="width:100%;">
								<ul><li><p><?

									echo $row["comment"];

								?></p></li></ul>
							</div>
						</div><?
					}
				} ?>
				<!-- display input box after the comment thread has been pulled -->
				<form action="processConvoComment.php" name="convo_comment_form" style="margin:0 auto; padding-right:10px" method="post" onsubmit="return validateConvoCommentForm()">
					<input type="hidden" name="commenterId" value="<?=$userId?>" />
					<input type="hidden" name="threadId" value="<?=$threadId?>" />
					<input type="hidden" name="challengeId" value="<?=$challengeId?>" />
					<input type="hidden" name="museId" value="<?=$museId?>" />
					<input type="hidden" name="icebreakerId" value="<?=$icebreakerId?>" />
					<div id="com-text" style="display:inline;">
						<div class="formy comment ucommenty">
							<div class="input-group">
				                <textarea class="form-control" style="height: 60px;" rows="3" name="convo_comment_body"></textarea>
								<span class="input-group-btn">
				                    <input type="submit" class="btn btn-primary" style="height: 60px;" value="Submit" />
				                </span>
							</div>
						</div>
					</div>
				</form>
			</div>
			<? include "notificationSidebar.php"; ?>
		</div>

<?php include "footer.php";
?>