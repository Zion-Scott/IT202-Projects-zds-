--Log on to Database
<?php
//Makes DB connection
$servername = "";
$username = "";
$password = "";
$dbname = "";
$con = mysqli_connect($servername,$username,$password,$dbname);
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else
 {
echo "CONNECTED ";
}
?>


CREATE TABLE Caterers ( -- CATERER'S TABLE
 firstName     VARCHAR(60)  NOT NULL,
 lastName      VARCHAR(60)  NOT NULL,
 password      VARCHAR(10)  NOT NULL,
 catererID     INT(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
 phoneNumber   VARCHAR(40)  NOT NULL,
 emailAddress  VARCHAR(60)  NOT NULL UNIQUE
);
