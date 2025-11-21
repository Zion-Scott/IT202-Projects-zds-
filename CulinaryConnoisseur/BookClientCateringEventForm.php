<?php
session_start();
require 'connect.php';

// caterer must be logged in and have a verified client
if (!isset($_SESSION['catererID'])) {
    echo "<script>
        alert('You must log in as a caterer first.');
        window.location.href = 'CulConn_index.php';
    </script>";
    exit;
}
if (!isset($_SESSION['booking_clientID'])) {
    echo "<script>
        alert('You must verify a client first.');
        window.location.href = 'BookClientCateringEvent.php';
    </script>";
    exit;
}

$catererID  = (int)$_SESSION['catererID'];
$clientID   = (int)$_SESSION['booking_clientID'];
$clientName = $_SESSION['booking_clientName'] ?? '';

//get POST data from DB
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateOfEvent = trim($_POST['dateOfEvent'] ?? '');
    $foodOrder   = trim($_POST['foodOrder'] ?? '');

    if ($dateOfEvent === '' || $foodOrder === '') {
        echo "<script>
            alert('Please fill out all fields before booking.');
            window.location.href = 'BookClientCateringEventForm.php';
        </script>";
        exit;
    }

    // generate random unique cateringID in 4 digit range
    do {
        $randomCateringID = rand(1001, 9999);
        $checkSql = "SELECT cateringID FROM ClientCateringInfo WHERE cateringID = $randomCateringID";
        $checkResult = mysqli_query($connect, $checkSql);
        if (!$checkResult) {
            $err = addslashes(mysqli_error($connect));
            echo "<script>
                alert('Error checking catering ID: $err');
                window.location.href = 'BookClientCateringEventForm.php';
            </script>";
            exit;
        }
    } while (mysqli_num_rows($checkResult) > 0);

    $dateSafe = mysqli_real_escape_string($connect, $dateOfEvent);
    $foodSafe = mysqli_real_escape_string($connect, $foodOrder);

    $insertSql = "
        INSERT INTO ClientCateringInfo (cateringID, clientID, catererID, dateOfEvent, foodOrder)
        VALUES ($randomCateringID, $clientID, $catererID, '$dateSafe', '$foodSafe')
    ";
    $insertResult = mysqli_query($connect, $insertSql);

    if (!$insertResult) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Insert error: $err');
            window.location.href = 'BookClientCateringEventForm.php';
        </script>";
        exit;
    }

    echo "<script>
        alert('Catering event booked. Your Catering ID is $randomCateringID.');
        // After booking, send them back to the verify page for another booking
        window.location.href = 'BookClientCateringEvent.php';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Book Client Catering Event</title>
    <link rel="stylesheet" href="CulConn.css">
    <!--JS alerts to direct user-->
    <script>
        function validateAndConfirm() {
            const dateField = document.getElementById('dateOfEvent');
            const foodField = document.getElementById('foodOrder');

            if (!dateField.value.trim()) {
                alert('Please enter a date for the event.');
                dateField.focus();
                return false;
            }
            if (!foodField.value.trim()) {
                alert('Please describe the food order.');
                foodField.focus();
                return false;
            }

            return confirm('Do you want to confirm booking this catering event?');
        }
    </script>
</head>
<body>
    <h1 class="title">Book a Client&apos;s Catering Event</h1>

    <p>
        <b>Caterer ID:</b> <?php echo htmlspecialchars($catererID); ?><br>
        <b>Client:</b> <?php echo htmlspecialchars($clientName); ?>
        (ID: <?php echo htmlspecialchars($clientID); ?>)
    </p>

    <form method="post" action="BookClientCateringEventForm.php" onsubmit="return validateAndConfirm();">
        <label for="dateOfEvent"><b>Date of Event:</b></label><br>
        <input type="date" id="dateOfEvent" name="dateOfEvent" required><br><br>

        <label for="foodOrder"><b>Food Order:</b></label><br>
        <textarea id="foodOrder" name="foodOrder" rows="4" cols="40" required></textarea><br><br>

        <button type="submit">Book Event</button>
    </form>

    <p style="margin-top:20px;">
        <a href="BookClientCateringEvent.php">Back to Client Verification</a>
    </p>
</body>
</html>
