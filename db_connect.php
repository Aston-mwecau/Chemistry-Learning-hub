<?php
// db_connect.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username   = "root";              // default for XAMPP/WAMP
$password   = "";                  // default is empty
$dbname     = "chemistryhub"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
