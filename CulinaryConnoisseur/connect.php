<?php
// connect.php — DB connection for Culinary Connoisseurs (Project 2)

$servername = "sql1.njit.edu";   // always this at NJIT
$username   = "zds";             // your UCID
$password   = "TheFundraiser821!"; // your MySQL password
$dbname     = "zds";             // same as UCID

// Create connection
$connect = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}


?>