<?php ?>
	<div id="wrapper">
		<div id="container">
			<div id="landing-page">
				<h1>Welcome to <b>TribeMind</b></h1>
				<h4><i>The home of collective self-discovery</i></h4><br />
				<h4>What is a tribe mind?</h4>
				<p> A tribe mind is a collective of individuals committed to their own personal growth that 
				share prompts and questions intended to stimulate each other's process of contemplation, 
				introspection and self-reflection. Users are prompted to submit a reflection and in turn 
				they gain access to the reflections of other users. </p> <br />

				<h4> Try it Out: </h4>


				<div id="teaser">

					<? 

					//if teaser reflection id is set, show reflection view for that challenge
					if (isset($_SESSION['teaserReflectionId'])) {
						echo "<h1>View Reflections</h1><h3>Prompt: ", $_SESSION['teaserChallengeName'], "</h3><br />"; ?>

						<div class="formy yourefy">
							<div class="input-group" style="width:100%;">
							<br />

							<? echo $_SESSION['teaserReflectionText']; ?>

							<br />
							<br />
							</div>
						</div> <?

						$refIdStr = "refId" . $_SESSION['teaserChallengeId'];

						//if reflection session variable is set
						if (isset($_SESSION[$refIdStr])) {
							if(empty($_SESSION[$refIdStr])) {
								// Response if no challenge reflections are found in the db
	  							?> <p>No one has submitted any reflections for this prompt yet.</p> <?
							} else { ?>

								<div class="formy themrefy">
									<div class="input-group" style="width:100%;">
										<div id="list2">
											<ul> <?
								
											// output data of each row
								    		foreach ($_SESSION[$refIdStr] as $reflection) {
												echo '<li><p><em>';

													echo $reflection;
												
												echo '</em></p></li>';
											} ?> 

											</ul>
										</div>
									</div>
								</div>
							<? }

						} else {
							$ssql = "SELECT id, reflection FROM teaser_reflections WHERE challenge_id = " . $_SESSION['teaserChallengeId'] . " AND id != " . $_SESSION['teaserReflectionId'] . " ORDER BY RAND() LIMIT 3";
							$sresult = $conn->query($ssql);

							$_SESSION[$refIdStr] = array();

							if ($sresult->num_rows > 0) {
								$counter = 0;
								while ($row = $sresult->fetch_assoc()) {
									$counter++;						

									//not sure what below comment was referring to
									//id doesn't work here anymore
									if ($counter == 1) {
										$_SESSION[$refIdStr]['refId1'] = $row['reflection'];
									} else if ($counter == 2) {
										$_SESSION[$refIdStr]['refId2'] = $row['reflection'];
									} else if ($counter == 3) {
										$_SESSION[$refIdStr]['refId3'] = $row['reflection'];
									}
								} ?>

								<div class="formy themrefy">
									<div class="input-group" style="width:100%;">
										<div id="list2">
											<ul> <?
								
											// output data of each row
								    		foreach ($_SESSION[$refIdStr] as $reflection) {
												echo '<li><p><em>';

													echo $reflection;
												
												echo '</em></p></li>';
											} ?> 

											</ul>
										</div>
									</div>
								</div>
							<? }
						}
					} else { ?>

						<h1>Receive Prompt</h1>

						<?
						$shouldReceiveChallenge = isset($_POST['shouldReceiveChallenge']);
						$justCompletedChallenge = isset($_POST['completedChallenge']);

						if ($justCompletedChallenge) {
							$_SESSION['complete'] = 1;

						} else if ($shouldReceiveChallenge) {
							$rand_ch_sql = "SELECT id, name FROM teaser_challenges ORDER BY RAND() LIMIT 1";
							$rand_ch_query = mysqli_query($conn, $rand_ch_sql);

							if ($rand_ch_query->num_rows == 1) {
								$row = mysqli_fetch_assoc($rand_ch_query);
								$_SESSION['teaserChallengeName'] = $row['name'];
								$_SESSION['teaserChallengeId'] = $row['id'];
							} else { ?>
								<br />
								<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
								<?php echo "<br />No prompts available at this time<br /><br />"; ?>
								</div>
							<? }
						}


						if (isset($_SESSION['complete'])) { ?>
							<p> Prompt: <b><?=$_SESSION['teaserChallengeName']?></b>. <br /> </p>

						 	<form action="processTeaserReflection.php" name="reflection_form" style="margin:0 auto;" method="post" onsubmit="return validateForm()">
								<input type="hidden" name="challengeId" value="<?=$_SESSION['teaserChallengeId']?>" />
								<div id="ref-text" style="display:inline;">
									<h4>Reflection</h4>
									<p>Submit your response to the prompt you received.</p>
									<div class="formy texty">
										<div class="input-group">
							                <textarea class="form-control" style="height: 100px;" rows="3" name="reflection_body"></textarea>
											<span class="input-group-btn">
							                       <input type="submit" class="btn btn-primary" style="height: 100px;" value="Submit" />
							                </span>
										</div>
									</div>
								</div>
							</form>

						<? } else if (!isset($_SESSION['teaserChallengeName']) || !isset($_SESSION['teaserChallengeId'])) { ?>

							<br/><p>You have no active prompts.</p><br/>

							<form method="post">
								<input type="hidden" name="shouldReceiveChallenge" value="1" />
								<input type="submit" class="btn btn-primary" value="Receive New Prompt" />
							</form>
			
						<? } else { ?>

							<p>Your prompt: <b><?=$_SESSION['teaserChallengeName']?></b><br /><br /> When you're ready to complete the prompt,<br /> press the button below.</p>
					
							<form method="post">
								<input type="hidden" name="completedChallenge" value="1" />
								<input type="submit" class="btn btn-primary" value="Complete the Prompt" />
							</form>
							
							<!-- Cancel button -->
		 					<!-- <form method="post">
		 						<input type="hidden" name="cancelledChallenge" value="1" />
		 						<input type="submit" class="btn btn-primary" value="Pass" />
		 					</form> -->

						<? } 
					} ?>

				</div>

				<br />
				<p>Within the platform you will also have the option to comment on other users' reflections
				and begin a conversation, or share the prompt directly with your friends or any other groups
				you are a part of.</p>



			</div>
		</div>
	</div>