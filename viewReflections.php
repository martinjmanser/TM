<?php 
	include 'db-session-setup.php';
	include 'header.php';


$userId = $_SESSION['id'];

	include 'navbar.php';
?>
		<div id="wrapper">
			<div id="container">
				<h1>View Reflections</h1>
				<p>
					You can view reflections for prompts you've completed as well as prompts you've submitted that others have completed.
				</p>
				
				<!-- Generate Reflection Tiles from Challenges table in db -->
				<?php

				// get list of challenges that the user has submitted a reflection for (using reflection table) 
				// excluding those challenges that the user submitted
				$status_sql = "SELECT challenge_id FROM reflections WHERE user_id = $userId AND challenge_id NOT IN (SELECT id FROM challenges WHERE user_id = $userId) AND tribe_id = $tribeId";
				$status_result = $conn->query($status_sql);

				$noCompletedChallenges = 0;

				if ($status_result->num_rows == 0) {
					$noCompletedChallenges = 1;
					echo "You haven't completed any prompts yet!";
					echo '<br /><br />';
				} else {
					$completedIds = array();

					while ($row = mysqli_fetch_assoc($status_result)) {
						array_push($completedIds, $row['challenge_id']);
					}


					$getChallengeInfoSql = "SELECT id, name FROM challenges WHERE id IN (" . implode(',', $completedIds) . ")";
					$getChallengeInfoRes = $conn->query($getChallengeInfoSql);

					?> 
					<div class="formy challengey">
							<div class="input-group" style="width:100%;">
					<div class="fancylist" style="width:100%;">
					  <ul>
					<?

					// output data of each row
					while ($row = mysqli_fetch_assoc($getChallengeInfoRes)) {

						$challengeId = $row["id"];
					
						echo '<a href="reflectionView.php?challengeId=' . $challengeId . '">
						<li><div><i class="fa fa-3x fa-bookmark"></i></div><h3>'
							. $row["name"]
							. '</h3></li></a>';

						// check if share link is already in db
						$share_link_check_sql = "SELECT query_string, hash FROM share_links WHERE user_id = $userId AND challenge_id = $challengeId";
						$share_link_check_result = $conn->query($share_link_check_sql);

						if ($share_link_check_result->num_rows == 1) {
							// generate link from db fields

							$shareLinkArray = $share_link_check_result->fetch_assoc();
							$query_string = $shareLinkArray['query_string'];
							$hash = $shareLinkArray['hash'];

							$hashed_query_string = $query_string . "&h=" . $hash;

							echo "<h5 style='display:inline'>Share Link:</h5> <p style='font-size:12px; display:inline'>http://localhost:8888/whynot_writing1/index.php?" .  $hashed_query_string . "</p>";
						} else {
							$shareLink = '<form method="post"  action="processShareLink.php">
									<input type="hidden" name="challengeId" value="' . $row["id"] .  '" />
									<input type="submit" class="btn-link" value="share link" /></form>';

							echo $shareLink;

							?><!--<input type="submit" class="btn btn-primary" value="Generate Share Link(1)" /> --><?
						}
		  		    }

		  		    ?> </ul></div></div></div> <?
				}

				//get list of challenges submitted by current user
				$sql = "SELECT id, name FROM challenges WHERE user_id = $userId";
				$result = $conn->query($sql);

				if ($result->num_rows == 0) {
					echo "You haven't submitted any prompts yet!";
				} else {

					?>
					<div class="formy submittedy">
							<div class="input-group" style="width:100%;">
					<div class="fancylist" style="width:100%;">
					  <ul>
					 <?

					$submittedIds = array();

					while ($row = mysqli_fetch_assoc($result)) {
						echo '<li><div><i class="fa fa-3x fa-bookmark"></i></div>
						<h3><a href="reflectionView.php?challengeId=' . $row["id"] . '">'
							. $row["name"]
							. '</a></h3></li>';
					}

					?>
						</ul>
					</div></div></div>
					<?
				}

				?>
			</div>
			<? include "notificationSidebar.php"; ?>
		</div> 

<?php include "footer.php";
?>