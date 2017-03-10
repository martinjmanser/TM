<?php
//session_start();

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
?>