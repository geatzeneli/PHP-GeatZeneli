<?php
include "config/db.php";
if (!isset($_SESSION["user"])) header("Location: login.php");
?>

<h1 class="dash-title">Welcome, <?=$_SESSION["user"]?></h1>
<a href="logout.php">Logout</a>
