<?php
$servername = "sql1.njit.edu";
$username   = "zds";
$password   = "TheFundraiser821!";
$dbname     = "zds";

$con = mysqli_connect($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    http_response_code(500);
    echo "Database connection failed.";
    exit;
}