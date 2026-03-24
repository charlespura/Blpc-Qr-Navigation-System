<?php
// File: update_resident.php
// Path: blpc-system/update_resident.php
include 'db.php';

$id = intval($_POST['id']);
$name = $conn->real_escape_string($_POST['name']);
$address = $conn->real_escape_string($_POST['address']);
$map_link = $conn->real_escape_string($_POST['map_link']);

$conn->query("UPDATE residents SET name='$name', address='$address', map_link='$map_link' WHERE id=$id");

header("Location: index.php");
exit();
?>