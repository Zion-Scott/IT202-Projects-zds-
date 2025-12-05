


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'database_connect.php'; ?>
<!DOCTYPE html>
<html>
<body>
    <h1>Chat Messages</h1>

    <!-- Send login info to chatLogin.php -->
    <form id="chatForm" action="chatLogin.php" method="POST">

        <label>Create Name & Password</label> <br/><br/>

        Name:
        <input placeholder="ex. Zion123" 
               type="text" 
               name="userName" 
               required><br><br>

        Password:
        <input placeholder="ex. Password123!" 
               type="password" 
               name="password" 
               required><br><br>

        <label>Type what you want to say:</label><br>
        <textarea name="ChatContent"></textarea><br>

        <button type="submit">Login</button>
    </form>

</body>
</html>