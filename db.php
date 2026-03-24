<?php
// File: db.php
// Path: blpc-system/db.php
$conn = new mysqli("localhost", "root", "", "blpc");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>