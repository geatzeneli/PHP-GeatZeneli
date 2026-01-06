<?php
$conn = new mysqli("localhost", "root", "", "blacksite");

if ($conn->connect_error) {
    die("Database Connection Failed");
}
session_start();
?>
