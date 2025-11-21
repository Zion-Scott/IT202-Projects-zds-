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

$stage = $_POST['stage'] ?? 'verify';  // 'verify' or 'edit'
$recordFound = false;
$currentRecord = null;

// STAGE 2: Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $stage === 'edit') {
    $cateringID = isset($_POST['cateringID']) ? (int) $_POST['cateringID'] : 0;
    $supplyID   = isset($_POST['supplyID'])   ? (int) $_POST['supplyID']   : 0;
    $supplyType = trim($_POST['supplyType'] ?? '');
    $quantity   = trim($_POST['quantity'] ?? '');

    if ($cateringID <= 0 || $supplyID <= 0 || $supplyType === '' || $quantity === '' || !ctype_digit($quantity)) {
        echo "<script>
            alert('Please provide valid values for Catering ID, Supply ID, supply type, and numeric quantity.');
            window.location.href = 'UpdateAdditionalCateringServices.php';
        </script>";
        exit;
    }

    $qtyInt      = (int) $quantity;
    $supplySafe  = mysqli_real_escape_string($connect, $supplyType);

    // Check again that this record still exists before updating
    $checkSql = "
        SELECT supplyID
        FROM EventSupplies
        WHERE supplyID = $supplyID
          AND cateringID = $cateringID
    ";
    $checkResult = mysqli_query($connect, $checkSql);

    if (!$checkResult || mysqli_num_rows($checkResult) === 0) {
        echo "<script>
            alert('Record not found, please check values.');
            window.location.href = 'UpdateAdditionalCateringServices.php';
        </script>";
        exit;
    }

    // Run UPDATE
    $updateSql = "
        UPDATE EventSupplies
        SET supplyType = '$supplySafe',
            quantity   = $qtyInt
        WHERE supplyID  = $supplyID
          AND cateringID = $cateringID
    ";
    $updateResult = mysqli_query($connect, $updateSql);

    if (!$updateResult) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Error updating record: $err');
            window.location.href = 'UpdateAdditionalCateringServices.php';
        </script>";
        exit;
    }

    if (mysqli_affected_rows($connect) === 0) {
        echo "<script>
            alert('No changes were made. The record may already have these values.');
            window.location.href = 'UpdateAdditionalCateringServices.php';
        </script>";
        exit;
    }

    echo "<script>
        alert('Additional services record has been updated.');
        window.location.href = 'UpdateAdditionalCateringServices.php';
    </script>";
    exit;
}

// STAGE 1: Handle initial verify of CateringID + SupplyID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $stage === 'verify') {
    $cateringIDInput = trim($_POST['cateringID'] ?? '');
    $supplyIDInput   = trim($_POST['supplyID'] ?? '');

    if ($cateringIDInput === '' || $supplyIDInput === '' ||
        !ctype_digit($cateringIDInput) || !ctype_digit($supplyIDInput)) {

        echo "<script>
            alert('Please enter valid numeric values for Catering ID and Supply ID.');
            window.location.href = 'UpdateAdditionalCateringServices.php';
        </script>";
        exit;
    }

    $cateringID = (int) $cateringIDInput;
    $supplyID   = (int) $supplyIDInput;

    $checkSql = "
        SELECT supplyID, cateringID, supplyType, quantity
        FROM EventSupplies
        WHERE supplyID  = $supplyID
          AND cateringID = $cateringID
    ";
    $checkResult = mysqli_query($connect, $checkSql);

    if (!$checkResult) {
        $err = addslashes(mysqli_error($connect));
        echo "<script>
            alert('Database error when checking record: $err');
            window.location.href = 'UpdateAdditionalCateringServices.php';
        </script>";
        exit;
    }

    if (mysqli_num_rows($checkResult) === 0) {
        echo "<script>
            alert('Record not found, please check values.');
            window.location.href = 'UpdateAdditionalCateringServices.php';
        </script>";
        exit;
    }

    // Record exists → we’ll show the update form below
    $recordFound   = true;
    $currentRecord = mysqli_fetch_assoc($checkResult);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Update Additional Catering Services</title>
    <link rel="stylesheet" href="CulConn.css">
    <script>
        function confirmUpdate() {
            return confirm('Do you want to update these additional services for this event?');
        }
    </script>
</head>
<body>
    <h1 class="title">Update Additional Catering Services</h1>

    <p>
        Logged in Caterer ID:
        <?php echo htmlspecialchars($catererID); ?>
    </p>

    <!-- verify CateringID and SupplyID -->
    <?php if (!$recordFound): ?>
        <form method="post" action="UpdateAdditionalCateringServices.php">
            <input type="hidden" name="stage" value="verify">

            <label for="cateringID"><b>Catering ID:</b></label><br>
            <input type="text" id="cateringID" name="cateringID" required><br><br>

            <label for="supplyID"><b>Supply ID:</b></label><br>
            <input type="text" id="supplyID" name="supplyID" required><br><br>

            <button type="submit">Find Record</button>
        </form>
    <?php else: ?>
        <!-- record found, show update form -->
        <p>
            Record found:<br>
            Catering ID: <?php echo htmlspecialchars($currentRecord['cateringID']); ?><br>
            Supply ID: <?php echo htmlspecialchars($currentRecord['supplyID']); ?><br>
            Current Type: <?php echo htmlspecialchars($currentRecord['supplyType']); ?><br>
            Current Quantity: <?php echo htmlspecialchars($currentRecord['quantity']); ?>
        </p>

        <form method="post" action="UpdateAdditionalCateringServices.php" onsubmit="return confirmUpdate();">
            <input type="hidden" name="stage" value="edit">
            <input type="hidden" name="cateringID" value="<?php echo htmlspecialchars($currentRecord['cateringID']); ?>">
            <input type="hidden" name="supplyID" value="<?php echo htmlspecialchars($currentRecord['supplyID']); ?>">

            <label for="supplyType"><b>New Supply Type:</b></label><br>
            <input type="text" id="supplyType" name="supplyType"
                   value="<?php echo htmlspecialchars($currentRecord['supplyType']); ?>" required><br><br>

            <label for="quantity"><b>New Quantity:</b></label><br>
            <input type="text" id="quantity" name="quantity"
                   value="<?php echo htmlspecialchars($currentRecord['quantity']); ?>" required><br><br>

            <button type="submit">Update Services</button>
        </form>
    <?php endif; ?>

    <p style="margin-top:20px;">
        <a href="CulConn_index.php">Back to Login</a>
    </p>
</body>
</html>
