<?php
session_start();
require __DIR__ . '/connect.php';

// Only handle POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: CulConn_index.php");
    exit;
}

/*
 * 1. Get form data (names MUST match CulConn_index.php)
 */
$firstName   = isset($_POST['firstName'])   ? trim($_POST['firstName'])   : '';
$lastName    = isset($_POST['lastName'])    ? trim($_POST['lastName'])    : '';
$password    = isset($_POST['password'])    ? trim($_POST['password'])    : '';
// NOTE: form field is name="idNumber"
$catererID   = isset($_POST['idNumber'])    ? (int)$_POST['idNumber']     : 0;
$phoneNumber = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : '';
$email       = isset($_POST['email'])       ? trim($_POST['email'])       : '';
$emailChecked = isset($_POST['emailConfirmation']);
$transaction  = isset($_POST['transactionType']) ? $_POST['transactionType'] : '';

/*
 * 2. Build the SELECT statement
 *    If checkbox is checked, include email in the WHERE clause.
 */
$firstNameEsc = mysqli_real_escape_string($connect, $firstName);
$lastNameEsc  = mysqli_real_escape_string($connect, $lastName);
$passwordEsc  = mysqli_real_escape_string($connect, $password);
$emailEsc     = mysqli_real_escape_string($connect, $email);
$catererIDEsc = (int)$catererID;

$sql = "SELECT *
        FROM Caterers
        WHERE firstName = '$firstNameEsc'
          AND lastName  = '$lastNameEsc'
          AND password  = '$passwordEsc'
          AND catererID = $catererIDEsc";

if ($emailChecked) {
    $sql .= " AND emailAddress = '$emailEsc'";
}

/*
 * 3. Execute the query
 */
$result = mysqli_query($connect, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    // No matching row → send them back to login with a clean form
    header("Location: CulConn_index.php");
    exit;
}

/*
 * 4. We have a match → fetch row and set session
 */
$row = mysqli_fetch_assoc($result);

$_SESSION['catererID']   = $row['catererID'];
$_SESSION['firstName']   = $row['firstName'];
$_SESSION['lastName']    = $row['lastName'];
$_SESSION['phoneNumber'] = $row['phoneNumber'];
$_SESSION['email']       = $row['emailAddress'];

/*
 * 5. Redirect based on dropdown choice
 */
switch ($transaction) {
    case "Search a caterer's accounts":
        header("Location: SearchCaterer.php");
        break;
    case "Book a clients catering event":
        header("Location: BookClientCateringEvent.php");
        break;
    case "Cancel a clients catering event":
        header("Location: CancelClientCateringEvent.php");
        break;
    case "Request additional catering services":
        header("Location: RequestAdditionalServices.php");
        break;
    case "Update additional catering services":
        header("Location: UpdateAdditionalCateringServices.php");
        break;
    case "Create a New Client":
        header("Location: CreateClientAccount.php");
        break;
    default:
        header("Location: CulConn_index.php");
        break;
}

exit;
