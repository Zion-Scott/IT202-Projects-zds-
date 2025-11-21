<?php
session_start();
require 'connect.php'; // adjust path if needed

// must be logged in as a caterer
if (!isset($_SESSION['catererID'])) {
    echo "<script>
        alert('You must log in as a caterer first.');
        window.location.href = 'CulConn_index.php';
    </script>";
    exit;
}

$catererID = (int) $_SESSION['catererID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // grab and trim all fields
    $clientID      = trim($_POST['clientID'] ?? '');
    $firstName     = trim($_POST['firstName'] ?? '');
    $lastName      = trim($_POST['lastName'] ?? '');
    $streetNumber  = trim($_POST['streetNumber'] ?? '');
    $streetName    = trim($_POST['streetName'] ?? '');
    $city          = trim($_POST['city'] ?? '');
    $state         = trim($_POST['state'] ?? '');
    $zipCode       = trim($_POST['zipCode'] ?? '');
    $phoneNumber   = trim($_POST['phoneNumber'] ?? '');

    // basic validation
    if (
        $clientID === '' || !ctype_digit($clientID) ||
        $firstName === '' || $lastName === '' ||
        $streetNumber === '' || $streetName === '' ||
        $city === '' || $state === '' || $zipCode === '' || $phoneNumber === ''
    ) {
        echo "<script>
            alert('Please fill out all fields with valid values. Client ID must be numeric.');
            window.location.href = 'CreateClientAccount.php';
        </script>";
        exit;
    }

    $clientIDInt = (int) $clientID;

    // secondary check, does clientID already exist?
    $checkSql = "SELECT clientID FROM Clients WHERE clientID = $clientIDInt";
    $checkResult = mysqli_query($connect, $checkSql);

    if (!$checkResult) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Database error when checking client ID: $err');
            window.location.href = 'CreateClientAccount.php';
        </script>";
        exit;
    }

    if (mysqli_num_rows($checkResult) > 0) {
        // ID already in use
        echo "<script>
            alert('A client with that ID already exists. Please choose a different Client ID.');
            window.location.href = 'CreateClientAccount.php';
        </script>";
        exit;
    }

    // escape strings 
    $firstSafe   = mysqli_real_escape_string($connect, $firstName);
    $lastSafe    = mysqli_real_escape_string($connect, $lastName);
    $streetNumSafe = mysqli_real_escape_string($connect, $streetNumber);
    $streetNameSafe = mysqli_real_escape_string($connect, $streetName);
    $citySafe    = mysqli_real_escape_string($connect, $city);
    $stateSafe   = mysqli_real_escape_string($connect, $state);
    $zipSafe     = mysqli_real_escape_string($connect, $zipCode);
    $phoneSafe   = mysqli_real_escape_string($connect, $phoneNumber);

    // insert sql statement
    $insertClientSql = "
        INSERT INTO Clients (clientID, firstName, lastName)
        VALUES ($clientIDInt, '$firstSafe', '$lastSafe')
    ";
    $clientResult = mysqli_query($connect, $insertClientSql);

    if (!$clientResult) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Error creating client record: $err');
            window.location.href = 'CreateClientAccount.php';
        </script>";
        exit;
    }

    // insert into ClientInfo / ClientPersonalInfo
    $insertInfoSql = "
        INSERT INTO ClientInfo (clientID, streetNumber, streetName, city, state, zipCode, phoneNumber)
        VALUES ($clientIDInt, '$streetNumSafe', '$streetNameSafe', '$citySafe', '$stateSafe', '$zipSafe', '$phoneSafe')
    ";
    $infoResult = mysqli_query($connect, $insertInfoSql);

    if (!$infoResult) {
        // report the error.
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Client main record created, but there was an error saving address info: $err');
            window.location.href = 'CreateClientAccount.php';
        </script>";
        exit;
    }

    // Success -> send user back to booking event
    echo "<script>
        alert('Client account created.');
        // If they came from booking, this takes them back to the verify step.
        window.location.href = 'BookClientCateringEvent.php';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create New Client Account</title>
    <link rel="stylesheet" href="CulConn.css">
</head>
<body>
    <h1 class="title">Create A New Client Account</h1>

    <p>
        Logged in Caterer ID:
        <?php echo htmlspecialchars($catererID); ?>
    </p>

    <form method="post" action="CreateClientAccount.php">
        <h3>Client Information</h3>

        <label for="clientID"><b>Client ID:</b></label><br>
        <input type="text" id="clientID" name="clientID" required><br><br>

        <label for="firstName"><b>First Name:</b></label><br>
        <input type="text" id="firstName" name="firstName" required><br><br>

        <label for="lastName"><b>Last Name:</b></label><br>
        <input type="text" id="lastName" name="lastName" required><br><br>

        <h3>Address &amp; Contact</h3>

        <label for="streetNumber"><b>Street Number:</b></label><br>
        <input type="text" id="streetNumber" name="streetNumber" required><br><br>

        <label for="streetName"><b>Street Name:</b></label><br>
        <input type="text" id="streetName" name="streetName" required><br><br>

        <label for="city"><b>City:</b></label><br>
        <input type="text" id="city" name="city" required><br><br>

        <label for="state"><b>State (e.g. NJ):</b></label><br>
        <input type="text" id="state" name="state" maxlength="2" required><br><br>

        <label for="zipCode"><b>ZIP Code:</b></label><br>
        <input type="text" id="zipCode" name="zipCode" required><br><br>

        <label for="phoneNumber"><b>Phone Number:</b></label><br>
        <input type="text" id="phoneNumber" name="phoneNumber" required><br><br>

        <button type="submit">Create Client Account</button>
    </form>

    <p style="margin-top:20px;">
        <a href="CulConn_index.php">Back to Login</a>
    </p>
</body>
</html>
