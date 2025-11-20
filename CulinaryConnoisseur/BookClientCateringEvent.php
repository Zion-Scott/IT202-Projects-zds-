<?php
session_start();
require 'connect.php'; // adjust path if connect.php is in a parent folder

// Make sure a caterer is logged in
if (!isset($_SESSION['catererID'])) {
    echo "<script>
        alert('You must log in as a caterer first.');
        window.location.href = 'CulConn_index.php';
    </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientID = trim($_POST['clientID'] ?? '');

    // basic validation
    if ($clientID === '' || !ctype_digit($clientID)) {
        echo "<script>
            alert('Please enter a valid numeric Client ID.');
            window.location.href = 'BookClientCateringEvent.php';
        </script>";
        exit;
    }

    $clientID = (int)$clientID;

    // Check if client exists in Clients table
    $sql = "SELECT clientID, firstName, lastName
            FROM Clients
            WHERE clientID = $clientID";
    $result = mysqli_query($connect, $sql);

    // Query error
    if (!$result) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Database error when checking client: $err');
            window.location.href = 'BookClientCateringEvent.php';
        </script>";
        exit;
    }

    // No rows = client does NOT exist
    if (mysqli_num_rows($result) === 0) {
        echo "<script>
            if (confirm('Client does not exist. Press OK to re-enter data or Cancel to create a new client account.')) {
                // OK → re-enter data on this page
                window.location.href = 'BookClientCateringEvent.php';
            } else {
                // Cancel → go to Create New Client Account page
                window.location.href = 'CreateClientAccount.php';
            }
        </script>";
        exit;
    } else {
        // Client exists → save to session and go to booking form page
        $row = mysqli_fetch_assoc($result);

        $_SESSION['booking_clientID']   = $row['clientID'];
        $_SESSION['booking_clientName'] = $row['firstName'] . ' ' . $row['lastName'];

        $clientNameJS = addslashes($row['firstName'] . ' ' . $row['lastName']);

        echo "<script>
            alert('Client found: {$clientNameJS} (ID: {$clientID}). Proceeding to booking form.');
            window.location.href = 'BookClientCateringEventForm.php';
        </script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Verify Client - Book Catering Event</title>
    <link rel="stylesheet" href="CulConn.css">
</head>
<body>
    <h1 class="title">Verify Client for Catering Event</h1>

    <p>Logged in Caterer ID:
        <?php echo htmlspecialchars($_SESSION['catererID']); ?>
    </p>

    <form method="post" action="BookClientCateringEvent.php">
        <label for="clientID"><b>Client ID:</b></label><br>
        <input type="text" id="clientID" name="clientID" required><br><br>

        <button type="submit">Verify Client</button>
    </form>

    <p style="margin-top:20px;">
        <a href="CulConn_index.php">Back to Login</a>
    </p>
</body>
</html>
