<?php 
	include'db-session-setup.php';
	include 'header.php';

$userId = $_SESSION['id'];

if (!isset($_GET['challengeId'])) {
	echo "you need to pick a challenge. maybe redirect here...";
	exit;
} else {
	$access = false;
	$challengeId = $_GET['challengeId'];
	// check that user has access to this challenge
	// first check that challenge with given challenge id exists
	$check_challenge_sql = "SELECT user_id FROM challenges WHERE id = $challengeId";
	$check_challenge_result = $conn->query($check_challenge_sql);
	if ($check_challenge_result->num_rows == 1) {
		$row = $check_challenge_result->fetch_assoc();
		if ($row['user_id'] == $userId) {
			$access = true;
		} else {
			$check_reflection_sql = "SELECT 1 FROM reflections WHERE user_id = $userId AND challenge_id = $challengeId AND tribe_id = $tribeId";
			$check_reflection_result = $conn->query($check_reflection_sql);
			if ($check_reflection_result->num_rows == 1) {
				$access = true;
			}
		}
	}

	if ($access == false) { ?>
		<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
			<?php echo "<br />You do not have access to this prompt<br /><br />"; ?>
		</div> <?
		exit;
	}
	
}

$refIdStr = "refId" . $challengeId;

if (!isset($_SESSION[$refIdStr])) {
	$_SESSION[$refIdStr] = array();
}

if (isset($_SESSION[$refIdStr]['refId1'])) {
	$refId1 = $_SESSION[$refIdStr]['refId1'];
} else {
	$refId1 = 0;
}

if (isset($_SESSION[$refIdStr]['refId2'])) {
	$refId2 = $_SESSION[$refIdStr]['refId2'];
} else {
	$refId2 = 0;
}

if (isset($_SESSION[$refIdStr]['refId3'])) {
	$refId3 = $_SESSION[$refIdStr]['refId3'];
} else {
	$refId3 = 0;
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

				echo "<h1>View Reflections</h1><h3>Prompt: ", $challenge_name[0], "</h3><br />";

				$usql = "SELECT reflection, id FROM reflections WHERE challenge_id = $challengeId AND user_id = $userId AND tribe_id = $tribeId";
				$uresult = $conn->query($usql);
				// echo $uresult->fetch_assoc()['reflection'];

				if ($uresult->num_rows > 0) {
					//loops through reflections and returns last(most recent) reflection
					while ($row = $uresult->fetch_assoc()) {
						$ureflection = $row['reflection'];
						$ureflectionId = $row['id'];
					}

					?>

					<div class="formy yourefy">
					<div class="input-group" style="width:100%;">
					<br />

					<?
					//old code for photo uploads
					// if (substr($ureflection, 0, 7) === "uploads") {
					// 	echo "<img style='max-width: 60% !important;' src='$ureflection' />";
					// } else {
						echo $ureflection;
					// }

					?>

					<br />
					<br />
					</div><?
					//get comments for users' reflection if they exist
					$commentsql = "SELECT comment, accepted, id, commenter_id FROM comments WHERE content_type = 0 AND content_id = $ureflectionId AND tribe_id = $tribeId";
					$commentresult = $conn->query($commentsql);
					if ($commentresult->num_rows > 0) {
						// display all comments for this reflection
						while($comment = $commentresult->fetch_assoc()) { 
							if ($comment["accepted"] == 1) { ?>

								<div class="formy comment tcommenty">
									<div class="input-group" style="width:100%;">
										<ul><li><p><em><?

											echo $comment["comment"];

										?></em></p></li></ul> <?

										$commentId = $comment["id"];
										//get your response to above comment if it exists
										$commentreplysql = "SELECT comment, commenter_id FROM comments WHERE content_type = 1 AND content_id = $commentId AND tribe_id = $tribeId";
										$commentreplyresult = $conn->query($commentreplysql);
										if ($commentreplyresult->num_rows > 0) {
											$commentreply = $commentreplyresult->fetch_assoc(); ?> 

											<div class="formy comoncom ucommenty" style="margin-right:10px">
												<div class="input-group" style="width:100%;"><ul><li><p><?

												echo $commentreply["comment"];


												$conversationLink = '<form method="post" style="float:right"  action="processConversationLink.php">
												<input type="hidden" name="challengeId" value="' . $challengeId .  '" />
												<input type="hidden" name="museId" value="' . $userId .  '" />
												<input type="hidden" name="icebreakerId" value="' . $comment["commenter_id"] .  '" />
												<input type="submit" class="btn-link" value="Continue Conversation..." />
												</form>';

												echo $conversationLink; ?>

												</p></li></ul></div>
											</div><?

										} else if (isset($_POST['replyClicked']) && $_POST['replyClicked'] === $commentId) { ?>

											<form action="processCommentReply.php" name="comment_form" style="margin:0 auto; padding-right:10px" method="post" onsubmit="return validateCommentForm()">
												<input type="hidden" name="commentId" value="<?=$commentId?>" />
												<input type="hidden" name="commenteeId" value="<?=$comment['commenter_id']?>" />
												<input type="hidden" name="commenterId" value="<?=$userId?>" />
												<input type="hidden" name="challengeId" value="<?=$challengeId?>" />
												<div id="com-text" style="display:inline;">
													<div class="formy comment ucommenty">
														<div class="input-group">
											                <textarea class="form-control" style="height: 60px;" rows="3" name="comment_body"></textarea>
															<span class="input-group-btn">
											                       <input type="submit" class="btn btn-primary" style="height: 60px;" value="Submit" />
											                </span>
														</div>
													</div>
												</div>
											</form>

										<? } else { ?>

											<form method="post">
												<input type="hidden" name="replyClicked" value="<?=$commentId;?>" />
												<input type="submit" class="btn btn-primary" value="Reply" />
											</form>

						  		    	<? } ?>

									</div>
								</div><?

							} else { ?>
								<div class="formy comment pcommenty">
									<div class="input-group" style="width:100%;">
										<form action="processAcceptedComment.php" method="post" >
											<input type="hidden" name="commentId" value="<?=$comment["id"];?>" />
											<input type="hidden" name="challengeId" value="<?=$challengeId?>" />
											<input type="submit" class="btn btn-primary" value="Accept Comment" />
										</form>
									</div>
								</div>
							<? }
						}
					}

					?></div><?
				}

				//refId1 is equal to 0 when page is first visited after login
				if ($refId1 == 0) {
					$ssql = "SELECT * FROM reflections WHERE challenge_id = $challengeId AND user_id != $userId AND tribe_id = $tribeId ORDER BY RAND() LIMIT 3";
					$sresult = $conn->query($ssql);

					if ($sresult->num_rows > 0) {
						$counter = 0;
						while ($row = $sresult->fetch_assoc()) {
							$counter++;

							//not sure what below comment was referring to
							//id doesn't work here anymore
							if ($counter == 1) {
								$_SESSION[$refIdStr]['refId1'] = $row['id'];
								$refId1 = $row['id'];
							} else if ($counter == 2) {
								$_SESSION[$refIdStr]['refId2'] = $row['id'];
								$refId2 = $row['id'];
							} else if ($counter == 3) {
								$_SESSION[$refIdStr]['refId3'] = $row['id'];
								$refId3 = $row['id'];
							}
						}
					}
				}

				// get reflection text for each of the reflection ids queried above
				$sql = "SELECT reflection, user_id, id FROM reflections WHERE id IN ($refId1, $refId2, $refId3) AND user_id != $userId";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {

					?> 
					<div class="formy themrefy">
					<div class="input-group" style="width:100%;">
						<div id="list2"> 
					<ul> <?
					
					// output data of each row
		    		while($row = $result->fetch_assoc()) {


						echo '<li><p><em>';

						//old code for photo uploads
						// if (substr($row["reflection"], 0, 7) === "uploads") {
						// 	echo "<img style='max-width: 60% !important;' src='$ureflection' />";
						// } else {
							echo $row["reflection"];
						// }
						
						//why is there a closing span here?
						echo '</em></p></span></li>'; 

						//get my comment for this reflection if it exists
						$reflectionId = $row["id"];
						$mycommentsql = "SELECT comment, id FROM comments WHERE content_type = 0 AND content_id = $reflectionId AND commenter_id = $userId AND tribe_id = $tribeId";
						$mycommentresult = $conn->query($mycommentsql);
						if ($mycommentresult->num_rows > 0) {
							// display my comment for this reflection
							$mycomment = $mycommentresult->fetch_assoc(); ?>
							<div class="formy comment ucommenty" style="margin-right:10px">
								<div class="input-group" style="width:100%;">
									<ul><li><p><?

										echo $mycomment["comment"];

									?></p></li></ul><?

									//add check for comoncom
									$mycommentId = $mycomment["id"];
									$comreplysql = "SELECT comment, accepted, id, commenter_id FROM comments WHERE content_type = 1 AND content_id = $mycommentId and tribe_id = $tribeId";
									$comresult = $conn->query($comreplysql);
									if ($comresult->num_rows > 0) {
										$com = $comresult->fetch_assoc();
										if ($com["accepted"] == 1) { ?>

											<div class="formy comoncom tcommenty">
												<div class="input-group" style="width:100%;">
													<ul><li><p><em><?

														echo $com["comment"];

														$conversationLink = '<form method="post" style="float:right"  action="processConversationLink.php">
														<input type="hidden" name="challengeId" value="' . $challengeId .  '" />
														<input type="hidden" name="museId" value="' . $com["commenter_id"] .  '" />
														<input type="hidden" name="icebreakerId" value="' . $userId .  '" />
														<input type="submit" class="btn-link" value="Continue Conversation..." /></form>';

														echo $conversationLink; ?>

													</em></p></li></ul>
												</div>
											</div>
										<? } else { ?>

											<div class="formy comment pcommenty">
												<div class="input-group" style="width:100%;">
													<form action="processAcceptedComment.php" method="post" >
														<input type="hidden" name="commentId" value="<?=$com['id'];?>" />
														<input type="hidden" name="challengeId" value="<?=$challengeId?>" />
														<input type="submit" class="btn btn-primary" value="Accept Comment" />
													</form>
												</div>
											</div>
										<? }
									}

								?></div>
							</div><?
		
						} else if (isset($_POST['commentClicked']) && $_POST['commentClicked'] === $row["reflection"]) { ?>

							<form action="processComment.php" name="comment_form" style="margin:0 auto; padding-right:10px" method="post" onsubmit="return validateCommentForm()">
								<input type="hidden" name="reflectionId" value="<?=$row['id']?>" />
								<input type="hidden" name="commenteeId" value="<?=$row['user_id']?>" />
								<input type="hidden" name="commenterId" value="<?=$userId?>" />
								<input type="hidden" name="challengeId" value="<?=$challengeId?>" />
								<div id="com-text" style="display:inline;">
									<div class="formy comment ucommenty">
										<div class="input-group">
							                <textarea class="form-control" style="height: 60px;" rows="3" name="comment_body"></textarea>
											<span class="input-group-btn">
							                       <input type="submit" class="btn btn-primary" style="height: 60px;" value="Submit" />
							                </span>
										</div>
									</div>
								</div>
							</form>

						<? } else { ?>

							<form method="post">
								<input type="hidden" name="commentClicked" value="<? echo $row["reflection"];?>" />
								<input type="submit" class="btn btn-primary" value="Comment" />
							</form>

		  		    <?
		  		    	}

		  			}
		  		    ?> </ul></div></div></div><br /><br /><br /><?
				} else {

					//	Response if no challenge reflections are found in the db
		  			?> <p>No one has submitted any reflections for this prompt yet.</p> <?
				}
				?>

				
			</div>
			<? include "notificationSidebar.php"; ?>
		</div>

<?php include "footer.php";
?>