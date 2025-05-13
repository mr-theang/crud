<?php

$servername = "sql206.infinityfree.com"; 
$username = "if0_38973093";
$password = "8psDN2LGSLdXy7 "; 
$database = "if0_38973093_studentdb";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, port: $port);


if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}	
echo "Connected successfully";

?>