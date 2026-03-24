<?php
// File: save_resident.php
// Path: blpc-system/save_resident.php
include 'db.php';

$name = $conn->real_escape_string($_POST['name']);
$address = $conn->real_escape_string($_POST['address']);
$map_link = $conn->real_escape_string($_POST['map_link']);

$conn->query("INSERT INTO residents (name, address, map_link) VALUES ('$name', '$address', '$map_link')");

header("Location: index.php");
exit();
?>