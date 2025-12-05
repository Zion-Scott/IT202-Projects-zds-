<?php
// chatPage.php
require 'database_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Simple Chat</title>
    <script src="simpleChat.js"></script> 
</head>
<body>
    <h1>Simple Chat</h1>

    <!-- top: list of users -->
    <h2>Users in chat table</h2>
    <table border="1">
        <tr><th>User Name</th></tr>
        <?php
        $result = mysqli_query($con, "SELECT userName FROM UserChats ORDER BY userName");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . htmlspecialchars($row['userName']) . "</td></tr>";
            }
        }
        ?>
    </table>

    <hr>

    <!-- middle: your own chat input -->
    <h2>Type your message</h2>
    <form id="chatForm" onsubmit="return false;">
        <br>
        Name: 
        <input placeholder="ex. Zion123" type="text" id="userName" name="userName"><br><br>

        Password: 
        <input placeholder="ex. Password123!" type="password" id="password" name="password"><br><br>

        <label>Type what you want to say: </label><br>
        <textarea id="ChatContent" name="ChatContent" rows="4" cols="50"></textarea><br><br>

        <!-- no submit button here keyup will trigger Ajax -->
        <div id="updateStatus" style="color:red;"></div>
    </form>

    <hr>

    <!-- bottom: listen to other users -->
    <h2>Listen to another user</h2>
    <form id="listenForm" onsubmit="return false;">
        Name to listen to:
        <input type="text" id="listenName" name="listenName"><br><br>
        <button type="button" id="listenBtn">Listen</button>
    </form>

    <br>
    <label>Incoming chat:</label><br>
    <textarea id="listenArea" rows="4" cols="50" readonly></textarea>
</body>
</html>
