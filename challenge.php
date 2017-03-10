<?
	include'db-session-setup.php';
	include 'header.php';
	include 'navbar.php';
?>
		<div id="wrapper">
			<div id="container">
				<h1>Receive Prompt</h1>

				<?php

					$noAvailablePrompts = isset($_GET['noAvailablePrompts']);
					$challengeIdSet = isset($_GET['challengeId']);

					if ($noAvailablePrompts) { 
						$text = "<strong>" . "No New Prompts Available" . "</strong>" . ": come back later or submit one of your own!";
					}

					if (isset($text)) { ?>
						<div style="margin-top:40px;margin:0 auto;" class="alert alert-success">
						<?php echo $text; ?>
						</div>
						<br />
					<?php }

					if ($challengeIdSet) {
						$prospectiveChallengeId = $_GET['challengeId'];
						$check_status_sql = "SELECT id, status, shared FROM challenge_status WHERE user_id = $userId AND challenge_id = $prospectiveChallengeId AND active = 1 AND tribe_id = $tribeId";
						$check_status_result = $conn->query($check_status_sql);
						if ($check_status_result->num_rows == 0) { ?>
							<div style="margin-top:40px;margin:0 auto;" class="alert alert-danger">
							<?php echo "You do not have access to this prompt"; ?>
							</div><?
						} else {
							$challengeId = $prospectiveChallengeId;
							$currentPrompt = $check_status_result->fetch_assoc();
							$curStatus = $currentPrompt['status'];
							$statusId = $currentPrompt['id'];
							$shared = $currentPrompt['shared'];

							// get the name of the challenge with the corresponding challenge id
							$getChallenge_sql = "SELECT name FROM challenges WHERE id = $challengeId";
							$getChallenge_query = mysqli_query($conn, $getChallenge_sql);
							$current_challenge = mysqli_fetch_assoc($getChallenge_query);

							$challengeName = $current_challenge['name'];

							if ($curStatus == 0) { ?>

								<p>Your prompt: <b><?=$challengeName?></b><br /><br /> To accept the prompt and submit your response,<br /> press the button below.</p>
						
								<form action="processPromptAccepted.php" method="post">
									<input type="hidden" name="challengeId" value="<?=$challengeId?>" />
									<input type="hidden" name="statusId" value="<?=$statusId?>" />
									<input type="submit" class="btn btn-primary" value="Accept the Prompt" />
								</form>
								
								<!-- Cancel button -->
			 					<form action="processPromptCancelled.php" method="post">
			 						<input type="hidden" name="statusId" value="<?=$statusId?>" />
			 						<input type="submit" class="btn btn-primary" value="Pass" />
			 					</form>

							<? } else { ?>

								<p>
									Prompt: <b><?=$challengeName?></b>.
									<br />
								</p>

							 	<form action="processReflection.php" name="reflection_form" style="margin:0 auto;" method="post" onsubmit="return validateForm()">
							 		<input type="hidden" name="statusId" value="<?=$statusId?>" />
									<input type="hidden" name="challengeId" value="<?=$challengeId?>" />
									<input type="hidden" name="shared" value="<?=$shared?>" />
									<div id="ref-text" style="display:inline;">
										<h4>Reflection</h4>
										<p>Submit your response to the prompt you received.</p>
										<div class="formy texty">
											<div class="input-group">
								                <textarea class="form-control" style="height: 150px;" rows="3" name="reflection_body"></textarea>
												<span class="input-group-btn">
								                       <input type="submit" class="btn btn-primary" style="height: 150px;" value="Submit" />
								                </span>
											</div>
										</div>
									</div>
								</form>

							<? }

						}
					} else {

						//if challengeId isn't set in query string, check for any and all active challenge_status entries
						$status_sql = "SELECT `challenge_id` FROM `challenge_status` WHERE `user_id` = $userId AND active = 1 AND shared = 0 AND tribe_id = $tribeId";
						$status_result = $conn->query($status_sql);
						// if no active challenges, show retrieve challenge page
						if ($status_result->num_rows == 0) { ?>
							<br/><p>You have no active prompts.</p><br/>
						<? } else { ?>
							<div class="formy prompty">
								<div class="input-group" style="width:100%;">
									<div class="fancylist" style="width:100%;">
								  		<ul> <?

										while ($status = mysqli_fetch_assoc($status_result)) {
											$challengeId = $status['challenge_id'];

											// get the name of the challenge with the corresponding challenge id
											$getChallenge_sql = "SELECT name FROM challenges WHERE id = $challengeId";
											$getChallenge_query = mysqli_query($conn, $getChallenge_sql);
											$current_challenge = mysqli_fetch_assoc($getChallenge_query);

											$challengeName = $current_challenge['name'];

											echo '<a href="challenge.php?challengeId=' . $challengeId . '">
												<li><div><i class="fa fa-3x fa-bookmark"></i></div><h3>'
												. $challengeName
												. '</h3></li></a>';
										}

										?> </ul>
									</div>
								</div>
							</div> 
							
							
						<? } ?>
						<form action="processPromptRetrieval.php" method="post">
								<input type="submit" class="btn btn-primary" value="Receive New Prompt" />
						</form> 
						<?

						//if challengeId isn't set in query string, check for any and all active challenge_status entries
						$status_sql = "SELECT `challenge_id` FROM `challenge_status` WHERE `user_id` = $userId AND active = 1 AND shared = 1 AND tribe_id = $tribeId";
						$status_result = $conn->query($status_sql);
						// if no active challenges, show retrieve challenge page
						if ($status_result->num_rows == 0) { ?>
							<br/><p>You have no shared prompts.</p><br/>
						<? } else { ?>
							<div class="formy sharedprompty">
								<div class="input-group" style="width:100%;">
									<div class="fancylist" style="width:100%;">
								  		<ul> <?

										while ($status = mysqli_fetch_assoc($status_result)) {
											$challengeId = $status['challenge_id'];

											// get the name of the challenge with the corresponding challenge id
											$getChallenge_sql = "SELECT name FROM challenges WHERE id = $challengeId";
											$getChallenge_query = mysqli_query($conn, $getChallenge_sql);
											$current_challenge = mysqli_fetch_assoc($getChallenge_query);

											$challengeName = $current_challenge['name'];

											echo '<a href="challenge.php?challengeId=' . $challengeId . '">
												<li><div><i class="fa fa-3x fa-bookmark"></i></div><h3>'
												. $challengeName
												. '</h3></li></a>';
										}

										?> </ul>
									</div>
								</div>
							</div> 
							
							
						<? }

						if (isset($_SESSION['share_link_status'])) { ?>
							<div style="margin-top:40px;margin:0 auto;" class="alert alert-success">
								<?php echo $_SESSION['share_link_status']; ?>
							</div><?

							unset($_SESSION['share_link_status']);
						}

					} ?>
			</div>

			<? include "notificationSidebar.php"; ?>
			
		</div>
<?
	include 'footer.php';
?>