<?php
require 'database_connect.php';

$listenName = isset($_GET['listenName']) ? trim($_GET['listenName']) : '';
if ($listenName === '') {
    echo "";
    exit;
}

$sql = "SELECT ChatContent FROM UserChats WHERE userName = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $listenName);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $chatContent);

if (mysqli_stmt_fetch($stmt)) {
    echo $chatContent;
} else {
    echo "";   // no such user
}
?>
