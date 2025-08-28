<?php
$host = "localhost";
$user = "root";       // ปรับตาม MySQL ของคุณ
$pass = "";
$dbname = "booking_lx";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>
