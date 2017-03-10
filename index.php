<?php 
session_start();
//$index = 1;
include 'header.php';

if (isset($_GET['cid']) && isset($_GET['sid']) && isset($_GET['h'])) {
	$_SESSION['query_string'] = "?cid=" . $_GET['cid'] . "&sid=" . $_GET['sid'] . "&h=" . $_GET['h'];
} else {
	$_SESSION['query_string'] = '';
}


include 'db-setup.php';


if (isset($_SESSION['id'])) {
	// check for share link and run activation script if necessary
	$userId = $_SESSION['id'];
	if (isset($_GET['cid'])) {
		include 'activateShareLink.php';
	} 

	$globalTribeId = 1;

	//load global key points session variable
	$check_points_sql = "SELECT points FROM key_points WHERE user_id = $userId AND tribe_id = $globalTribeId";
	$check_points_result = mysqli_query($conn, $check_points_sql);
	$key_points = mysqli_fetch_assoc($check_points_result);
	$_SESSION['global_keypoints'] = $key_points['points'];

	include 'tribeSelectionNavbar.php';
	?>
	<div id="wrapper">
		<div id="container"><?

			$justLoggedIn = isset($_GET['justLoggedIn']);

			// set login text if user has just logged in
			if ($justLoggedIn) {
				$text = "Logged in successfully. Welcome, <strong>" . $_SESSION['name'] . "</strong>";
			}


			?><div id="landing-page">
				<h1>Welcome to <b>TribeMind</b></h1>
				<h4><i>The home of collective self-discovery</i></h4><br /><?

				// show log in message
				if (isset($text)) { ?>
					<div style="margin-top:40px;margin:0 auto;" class="alert alert-success">
						<?php echo $text; ?>
					</div>
					<br />
				<? } ?>

				<h4><b>Select a Tribe:</b></h4><br />
					<ul><?

						$pull_tribes_sql = "SELECT tribe_id FROM tribe_membership WHERE user_id = $userId";
						$pull_tribes_result = $conn->query($pull_tribes_sql);

						while($tribe = $pull_tribes_result->fetch_assoc()) {
							//spit out a form-link that will initialize the chosen tribe into the tribe session variable
							//and redirect to the challenge.php page
							$tribeId = $tribe['tribe_id'];

							$get_tribe_name_sql = "SELECT name FROM tribes WHERE id = $tribeId";
							$get_tribe_name_result = $conn->query($get_tribe_name_sql);
							$tribeNameRow = $get_tribe_name_result->fetch_assoc();
							$tribeName = $tribeNameRow['name'];

							$tribeSelectionLink = '<li><form method="post"  action="processTribeSelection.php">
								<input type="hidden" name="tribeId" value="' . $tribe['tribe_id'] .  '" />
								<input type="submit" class="btn-link" value="' . $tribeName .  ' " />
								</form></li>';

							echo $tribeSelectionLink;
						}
					?></ul>

					<h4><b>Created Tribes:</b></h4><br />


					<? if (isset($_POST['tribeCreationClicked'])) { ?>

						<form action="processTribeCreation.php" name="tribe_creation_form" method="post" onsubmit="">
							<div class="formy tribey">
								<div class="input-group">
					                <input class="form-control" type="text" name="tribe_name" />
									<span class="input-group-btn">
					                       <input type="submit" class="btn btn-primary" value="Submit" />
					                </span>
								</div>
							</div>
						</form>
 
					<? } else { ?>

						<form method="post">
							<input type="hidden" name="tribeCreationClicked" value="1" />
							<input type="submit" class="btn btn-primary" value="Create Tribe(5)" />
						</form>

					<? } ?>

					<? if (isset($_GET['status'])) {
						$status = $_GET['status'];

						if ($status == 'success') { 
							$tribeName = $_SESSION['tribe_name']; ?>
							<br />
							<div style="margin:0 auto;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-success">
							<?php echo "<br />Prompt <b>$tribeName</b> successfully created!<br /><br />"; ?>
							</div>

						<? } else if ($status == 'error') { ?>
							<br />
							<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
							<? echo "Something went wrong!<br /><br />"; ?>
							</div>
						<? } else if ($status == 'keypoints') { ?>
								<br />
								<div style="margin:0 auto;margin-top:20px;margin-bottom:20px;padding:0;padding-left:20px;" class="alert alert-danger">
								<? echo "<br />You don't have enough global key points to create a tribe, submit some more reflections and try again!<br /><br />"; ?>
								</div>
						<? }

					} ?>




			</div>
		</div>
	</div>
<? } else {
	include 'navbar.php';
	include 'teaser.php';

}

include "footer.php";
?>