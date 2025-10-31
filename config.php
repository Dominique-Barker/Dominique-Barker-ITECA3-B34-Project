<?php
// Database connection
$host = "sql309.infinityfree.com"; 
$user = "if0_40270217";
$pass = "KXYZai8gmVqSK";
$dbname = "if0_40270217_dragonstone";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>