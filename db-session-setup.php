<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['name']) ) {
	header('Location: login.php?auth=0');
	exit;
}

// only thing different than db-setup.php

$userId = $_SESSION['id'];
$userName = $_SESSION['name'];
$tribeId = $_SESSION['tribeId'];

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