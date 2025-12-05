<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require __DIR__ . '/database_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: homePage.php");
    exit;
}

$userName  = trim($_POST['userName']);
$password  = trim($_POST['password']);
$chatText  = trim($_POST['ChatContent']);

// Check user
$sql = "SELECT userID FROM UserChats WHERE userName = ? AND password = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ss", $userName, $password);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    // User does not exist → CREATE new user with message
    $insert = "INSERT INTO UserChats (userName, password, ChatContent) VALUES (?, ?, ?)";
    $stmt2 = mysqli_prepare($con, $insert);
    mysqli_stmt_bind_param($stmt2, "sss", $userName, $password, $chatText);
    mysqli_stmt_execute($stmt2);

} else {
    // User exists → UPDATE their message
    $update = "UPDATE UserChats SET ChatContent = ? WHERE userName = ? AND password = ?";
    $stmt2 = mysqli_prepare($con, $update);
    mysqli_stmt_bind_param($stmt2, "sss", $chatText, $userName, $password);
    mysqli_stmt_execute($stmt2);
}

// Save session
$_SESSION['userName'] = $userName;
$_SESSION['ChatContent'] = $chatText;

// Go to chat page
header("Location: chatPage.php");
exit;