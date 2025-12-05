<?php
require 'database_connect.php';

$userName    = isset($_POST['userName'])    ? trim($_POST['userName'])    : '';
$password    = isset($_POST['password'])    ? trim($_POST['password'])    : '';
$ChatContent = isset($_POST['ChatContent']) ? trim($_POST['ChatContent']) : '';

if ($userName === '' || $password === '') {
    echo "Missing name or password.";
    exit;
}

/*
 * 1) Check if this username/password combo exists
 */
$checkSql  = "SELECT userID FROM UserChats WHERE userName = ? AND password = ?";
$checkStmt = mysqli_prepare($con, $checkSql);

if (!$checkStmt) {
    // If prepare fails, just act like no match for now
    echo "NO_MATCH";
    exit;
}

mysqli_stmt_bind_param($checkStmt, "ss", $userName, $password);
mysqli_stmt_execute($checkStmt);
mysqli_stmt_store_result($checkStmt);

if (mysqli_stmt_num_rows($checkStmt) === 0) {
    // No such user/password in the table
    mysqli_stmt_close($checkStmt);
    echo "NO_MATCH";
    exit;
}

mysqli_stmt_close($checkStmt);

/*
  2) Credentials are valid -> update the chat content
     even if the content is the same as before (0 affected rows),
      still return "OK" because the login was correct.
 */
$updateSql  = "UPDATE UserChats
               SET ChatContent = ?
               WHERE userName = ? AND password = ?";

$updateStmt = mysqli_prepare($con, $updateSql);

if (!$updateStmt) {

    echo "NO_MATCH";
    exit;
}

mysqli_stmt_bind_param($updateStmt, "sss", $ChatContent, $userName, $password);
mysqli_stmt_execute($updateStmt);
mysqli_stmt_close($updateStmt);

echo "OK";
exit;
?>
