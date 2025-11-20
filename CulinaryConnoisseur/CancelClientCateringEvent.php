<?php
session_start();
require 'connect.php'; // adjust path if needed

// Must be logged in as a caterer
if (!isset($_SESSION['catererID'])) {
    echo "<script>
        alert('You must log in as a caterer first.');
        window.location.href = 'CulConn_index.php';
    </script>";
    exit;
}

$catererID = (int) $_SESSION['catererID'];

/*
 * STEP 2: Actually delete, after user has confirmed
 * This runs when we come in with ?doDelete=1&catererID=...&clientID=...&cateringID=...
 */
if (isset($_GET['doDelete']) && $_GET['doDelete'] === '1') {
    $clientID   = isset($_GET['clientID'])   ? (int) $_GET['clientID']   : 0;
    $cateringID = isset($_GET['cateringID']) ? (int) $_GET['cateringID'] : 0;
    $catIDParam = isset($_GET['catererID'])  ? (int) $_GET['catererID']  : 0;

    // Safety: make sure this delete request matches the logged-in caterer
    if ($catIDParam !== $catererID || $clientID <= 0 || $cateringID <= 0) {
        echo "<script>
            alert('Invalid cancellation request.');
            window.location.href = 'CancelClientCateringEvent.php';
        </script>";
        exit;
    }

    // Optionally delete related supplies first (if you have EventSupplies)
    $deleteSuppliesSql = "
        DELETE FROM EventSupplies
        WHERE cateringID = $cateringID
    ";
    mysqli_query($connect, $deleteSuppliesSql); // ignore errors if no supplies

    // Delete the actual catering event
    $deleteEventSql = "
        DELETE FROM ClientCateringInfo
        WHERE cateringID = $cateringID
          AND clientID   = $clientID
          AND catererID  = $catererID
    ";
    $deleteResult = mysqli_query($connect, $deleteEventSql);

    if (!$deleteResult) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Error cancelling event: $err');
            window.location.href = 'CancelClientCateringEvent.php';
        </script>";
        exit;
    }

    if (mysqli_affected_rows($connect) === 0) {
        // Nothing deleted – record vanished between confirm and delete
        echo "<script>
            alert('No matching booking found to cancel.');
            window.location.href = 'CancelClientCateringEvent.php';
        </script>";
        exit;
    }

    // Success
    echo "<script>
        alert('Catering event cancelled.\\nCatererID: $catererID\\nClientID: $clientID\\nCateringID: $cateringID');
        window.location.href = 'CancelClientCateringEvent.php';
    </script>";
    exit;
}

/*
 * STEP 1: Handle the initial POST from the form
 * We only CHECK if the event exists and, if so, ask for confirmation.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientID   = trim($_POST['clientID'] ?? '');
    $cateringID = trim($_POST['cateringID'] ?? '');

    // Basic validation
    if ($clientID === '' || $cateringID === '' || !ctype_digit($clientID) || !ctype_digit($cateringID)) {
        echo "<script>
            alert('Please enter valid numeric values for Client ID and Catering ID.');
            window.location.href = 'CancelClientCateringEvent.php';
        </script>";
        exit;
    }

    $clientID   = (int) $clientID;
    $cateringID = (int) $cateringID;

    // Check if this event exists for this caterer + client + cateringID
    $checkSql = "
        SELECT cateringID
        FROM ClientCateringInfo
        WHERE cateringID = $cateringID
          AND clientID   = $clientID
          AND catererID  = $catererID
    ";
    $checkResult = mysqli_query($connect, $checkSql);

    if (!$checkResult) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Database error when checking booking: $err');
            window.location.href = 'CancelClientCateringEvent.php';
        </script>";
        exit;
    }

    if (mysqli_num_rows($checkResult) === 0) {
        // event doesnt exist
        echo "<script>
            alert('Booking does not exist, please re-check values.');
            window.location.href = 'CancelClientCateringEvent.php';
        </script>";
        exit;
    }

    // Event EXISTS -> show confirm dialog with JS.
    // If OK -> go to same page with ?doDelete=1...
    // If Cancel -> back to cancel form.
    echo "<script>
        if (confirm('Are you sure you want to cancel this event?')) {
            window.location.href =
                'CancelClientCateringEvent.php?doDelete=1'
                + '&catererID=$catererID'
                + '&clientID=$clientID'
                + '&cateringID=$cateringID';
        } else {
            window.location.href = 'CancelClientCateringEvent.php';
        }
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cancel Client Catering Event</title>
    <link rel="stylesheet" href="CulConn.css">
</head>
<body>
    <h1 class="title">Cancel a Client&apos;s Catering Event</h1>

    <p>
        Logged in Caterer ID:
        <?php echo htmlspecialchars($catererID); ?>
    </p>

    <form method="post" action="CancelClientCateringEvent.php">
        <label for="clientID"><b>Client ID:</b></label><br>
        <input type="text" id="clientID" name="clientID" required><br><br>

        <label for="cateringID"><b>Catering ID:</b></label><br>
        <input type="text" id="cateringID" name="cateringID" required><br><br>

        <button type="submit">Find &amp; Cancel Event</button>
    </form>

    <p style="margin-top:20px;">
        <a href="CulConn_index.php">Back to Login</a>
    </p>
</body>
</html>
