<?
	include 'db-session-setup.php';
	include 'header.php';

	$userId = $_SESSION['id'];

	include 'navbar.php';
?>
		<div id="wrapper">
			<div id="container">
				<h1>Submit Prompt</h1>

				<?php
				if (isset($_GET['status'])) {
					$status = $_GET['status'];

					if ($status == 'success') { 
						$challengeName = $_SESSION['submittedChallenge']; ?>
						<br />
						<div style="margin:0 auto;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-success">
						<?php echo "<br />Prompt <b>$challengeName</b> submitted!<br /><br />"; ?>
						</div>

					<? } else if ($status == 'error') { ?>
						<br />
						<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
						<? echo "Something went wrong!<br /><br />"; ?>
						</div>
					<? } else if ($status == 'keypoints') { ?>
							<br />
							<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
							<? echo "<br />You don't have enough key points to submit a prompt, submit some more reflections and try again!<br /><br />"; ?>
							</div>
					<? }

				} ?>

					<p>Have an idea for a prompt?<br />Submit your idea to add it to the pool.</p><br />
					<form action="processSubmission.php" name="submit_form" method="post" onsubmit="return validateSubmitChallenge()">
						<div class="formy submity">
							<div class="input-group">
				                <input class="form-control" type="text" name="challenge_name" />
								<span class="input-group-btn">
				                       <input class="btn btn-primary" type="submit" value="Submit" />
				                </span>
							</div>
						</div>
					</form>
			</div>
			<? include "notificationSidebar.php"; ?>
		</div>
<?
	include 'footer.php';
?>