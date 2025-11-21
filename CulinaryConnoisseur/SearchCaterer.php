<?php

session_start();

// If not logged in, boot back to login
if (!isset($_SESSION['catererID'])) {
    header('Location: CulConn_index.php'); 
    exit;
}

require 'connect.php';


$catererID = (int) $_SESSION['catererID']; 

$sql = "
    SELECT
        c.firstName  AS catererFirstName,
        c.lastName   AS catererLastName,
        c.catererID,
        c.phoneNumber AS catererPhone,
        c.emailAddress AS catererEmail,

        cl.firstName AS clientFirstName,
        cl.lastName  AS clientLastName,
        cl.clientID,

        ci.streetNumber,
        ci.streetName,
        ci.city,
        ci.state,
        ci.zipCode,
        ci.phoneNumber AS clientPhone,

        cc.cateringID,
        cc.dateOfEvent,
        cc.foodOrder,

        es.supplyType,
        es.quantity
    FROM Caterers c
    JOIN ClientCateringInfo cc
        ON c.catererID = cc.catererID
    JOIN Clients cl
        ON cc.clientID = cl.clientID
    JOIN ClientInfo ci
        ON cl.clientID = ci.clientID
    LEFT JOIN EventSupplies es
        ON cc.cateringID = es.cateringID
    WHERE c.catererID = $catererID
    ORDER BY cc.dateOfEvent, cl.lastName, cl.firstName, es.supplyType
";

$result = mysqli_query($connect, $sql);

if (!$result) {
    die("Query error: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Caterer Account Search</title>
    <link rel="stylesheet" href="CulConn.css">
</head>
<body>
    <h1 class="title">Caterer Account Summary</h1>

    <p>
        Logged in as:
        <?php
            echo htmlspecialchars($_SESSION['catererFirstName'] . ' ' . $_SESSION['catererLastName']);
            echo " (ID: " . htmlspecialchars($catererID) . ")";
        ?>
    </p>

    <style> /*styling for ONLY this table page*/
    body {
        width: auto !important; 
        max-width: 95vw !important;
        margin: 0 auto;
        padding: 40px;
        background: url('CulConn.jpg') center/cover no-repeat fixed;
        border: none; /* remove the blue border JUST for this page */
    }

    .table-container {
        overflow-x: auto;
        margin: 0 auto;
        width: 95%;
    }

    table {
        margin: 0 auto;
        border-collapse: collapse;
        background-color: white;
    }

    table th, table td {
        border: 1px solid black;
        padding: 6px 10px;
        text-align: left;
        white-space: nowrap;
    }
    </style>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Caterer Name</th>
                    <th>Caterer ID</th>
                    <th>Caterer Phone</th>
                    <th>Caterer Email</th>

                    <th>Client Name</th>
                    <th>Client ID</th>

                    <th>Street #</th>
                    <th>Street Name</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zip</th>
                    <th>Client Phone</th>

                    <th>Catering ID</th>
                    <th>Date of Event</th>
                    <th>Food Ordered</th>

                    <th>Additional Item</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td>
                            <?php
                            echo htmlspecialchars($row['catererFirstName'] . ' ' . $row['catererLastName']);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['catererID']); ?></td>
                        <td><?php echo htmlspecialchars($row['catererPhone']); ?></td>
                        <td><?php echo htmlspecialchars($row['catererEmail']); ?></td>

                        <td>
                            <?php
                            echo htmlspecialchars($row['clientFirstName'] . ' ' . $row['clientLastName']);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['clientID']); ?></td>

                        <td><?php echo htmlspecialchars($row['streetNumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['streetName']); ?></td>
                        <td><?php echo htmlspecialchars($row['city']); ?></td>
                        <td><?php echo htmlspecialchars($row['state']); ?></td>
                        <td><?php echo htmlspecialchars($row['zipCode']); ?></td>
                        <td><?php echo htmlspecialchars($row['clientPhone']); ?></td>

                        <td><?php echo htmlspecialchars($row['cateringID']); ?></td>
                        <td><?php echo htmlspecialchars($row['dateOfEvent']); ?></td>
                        <td><?php echo htmlspecialchars($row['foodOrder']); ?></td>

                        <td><?php echo htmlspecialchars($row['supplyType'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity'] ?? ''); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <p style="margin-top:20px;"> <a href="CulConn_index.php">Back to Login</a> </p>
</body>
</html>
