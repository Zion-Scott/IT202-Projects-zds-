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


CREATE TABLE UserChats ( -- USERS TABLE
 userID        INT AUTO_INCREMENT   NOT NULL PRIMARY KEY,
 userName      VARCHAR(60)          NOT NULL,
 password      VARCHAR(20)          NOT NULL,
 ChatContent   VARCHAR(255)         NOT NULL
);


SELECT * FROM `UserChats` -- TABLE VIEWING

INSERT INTO UserChats (userName, password, ChatContent) VALUES
('Zion123',  'Password123!',   'Hi, good morning.'),
('Adrian456',  'SecurePassword456$',    'How has your day been?'),
('Jacob789',  'InsecurePassword789%',    'What do you do for fun?')