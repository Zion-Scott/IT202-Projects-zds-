<?php
session_start();
require 'connect.php'; 

// must be logged in as a caterer
if (!isset($_SESSION['catererID'])) {
    echo "<script>
        alert('You must log in as a caterer first.');
        window.location.href = 'CulConn_index.php';
    </script>";
    exit;
}

$catererID = (int) $_SESSION['catererID'];

$stage = $_POST['stage'] ?? 'verify'; 
$verifiedCateringID = null;

// handle adding supplies
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $stage === 'add') {
    $cateringID = isset($_POST['cateringID']) ? (int) $_POST['cateringID'] : 0;
    $supplyType = trim($_POST['supplyType'] ?? '');
    $quantity   = trim($_POST['quantity'] ?? '');

    if ($cateringID <= 0 || $supplyType === '' || $quantity === '' || !ctype_digit($quantity)) {
        echo "<script>
            alert('Please provide a valid catering ID, supply type, and numeric quantity.');
            window.location.href = 'RequestAdditionalServices.php';
        </script>";
        exit;
    }

    $qtyInt = (int) $quantity;

    // check that cateringID exists
    $checkSql = "
        SELECT cateringID
        FROM ClientCateringInfo
        WHERE cateringID = $cateringID
    ";
    $checkResult = mysqli_query($connect, $checkSql);

    if (!$checkResult || mysqli_num_rows($checkResult) === 0) {
        echo "<script>
            alert('No catering event found for that ID, please re-enter.');
            window.location.href = 'RequestAdditionalServices.php';
        </script>";
        exit;
    }

    $supplySafe = mysqli_real_escape_string($connect, $supplyType);

    // insert statement for additional supplies
    $insertSql = "
        INSERT INTO EventSupplies (cateringID, supplyType, quantity)
        VALUES ($cateringID, '$supplySafe', $qtyInt)
    ";
    $insertResult = mysqli_query($connect, $insertSql);

    if (!$insertResult) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Error adding additional services: $err');
            window.location.href = 'RequestAdditionalServices.php';
        </script>";
        exit;
    }

    echo "<script>
        alert('Additional services have been added.');
        window.location.href = 'RequestAdditionalServices.php';
    </script>";
    exit;
}

//  verify cateringID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $stage === 'verify') {
    $cateringIDInput = trim($_POST['cateringID'] ?? '');

    if ($cateringIDInput === '' || !ctype_digit($cateringIDInput)) {
        echo "<script>
            alert('Please enter a valid numeric Catering ID.');
            window.location.href = 'RequestAdditionalServices.php';
        </script>";
        exit;
    }

    $cateringID = (int) $cateringIDInput;

    // check if this event exists in DB
    $checkSql = "
        SELECT cateringID, clientID, catererID, dateOfEvent, foodOrder
        FROM ClientCateringInfo
        WHERE cateringID = $cateringID
    ";
    $checkResult = mysqli_query($connect, $checkSql);

    if (!$checkResult) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Database error when checking catering event: $err');
            window.location.href = 'RequestAdditionalServices.php';
        </script>";
        exit;
    }

    if (mysqli_num_rows($checkResult) === 0) {
        echo "<script>
            alert('No catering event found for that ID, please re-enter.');
            window.location.href = 'RequestAdditionalServices.php';
        </script>";
        exit;
    }

    //  event exists –> show the second form
    $eventRow = mysqli_fetch_assoc($checkResult);
    $verifiedCateringID = (int) $eventRow['cateringID'];
    $verifiedClientID   = (int) $eventRow['clientID'];
    $verifiedEventInfo  = $eventRow;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Request Additional Catering Services</title>
    <link rel="stylesheet" href="CulConn.css">
    <script>
        function confirmExtraServices() {
            return confirm('Do you want to request these additional services for this event?');
        }
    </script>
</head>
<body>
    <h1 class="title">Request Additional Catering Services</h1>

    <p>
        Logged in Caterer ID:
        <?php echo htmlspecialchars($catererID); ?>
    </p>

    <!-- verify CateringID -->
    <?php if ($verifiedCateringID === null): ?>
        <form method="post" action="RequestAdditionalServices.php">
            <input type="hidden" name="stage" value="verify">

            <label for="cateringID"><b>Catering ID:</b></label><br>
            <input type="text" id="cateringID" name="cateringID" required><br><br>

            <button type="submit">Verify Event</button>
        </form>
    <?php else: ?>
        <!--  Event found, show extra services form -->
        <p>
            Catering event found:<br>
            Catering ID: <?php echo htmlspecialchars($verifiedCateringID); ?><br>
            Client ID: <?php echo htmlspecialchars($verifiedClientID); ?><br>
            Date of Event: <?php echo htmlspecialchars($verifiedEventInfo['dateOfEvent']); ?><br>
            Food Order: <?php echo htmlspecialchars($verifiedEventInfo['foodOrder']); ?>
        </p>

        <form method="post" action="RequestAdditionalServices.php" onsubmit="return confirmExtraServices();">
            <input type="hidden" name="stage" value="add">
            <input type="hidden" name="cateringID" value="<?php echo htmlspecialchars($verifiedCateringID); ?>">

            <label for="supplyType"><b>Supply Type:</b></label><br>
            <input type="text" id="supplyType" name="supplyType" required><br><br>

            <label for="quantity"><b>Quantity:</b></label><br>
            <input type="text" id="quantity" name="quantity" required><br><br>

            <button type="submit">Add Additional Services</button>
        </form>
    <?php endif; ?>

    <p style="margin-top:20px;">
        <a href="CulConn_index.php">Back to Login</a>
    </p>
</body>
</html>
