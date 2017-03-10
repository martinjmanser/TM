<?php

	$servername = "localhost";
	$username = "mjmanser_whynot";
	$password = "mjmanser_whynot";
	$dbname = "mjmanser_whynot_writing1";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	//get all challenges
	$retrieve_challenges_sql = "SELECT id FROM challenge_status";
	$retrieve_challenges_result = $conn->query($retrieve_challenges_sql);


	while ($row = $retrieve_challenges_result->fetch_assoc()) {
		$challenge_id = $row['id'];
		$update_challenge_status_tribe_id = "UPDATE challenge_status SET tribe_id = 1";
		mysqli_query($conn, $update_challenge_status_tribe_id);
		// echo mysqli_error($conn);
	}

?> 