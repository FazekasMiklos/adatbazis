<?php
$servername = "localhost";
$username = "asd";
$password = "EUfav7H3rJmO3XIB";
$dbname = "teszt";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>