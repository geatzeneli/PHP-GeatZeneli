<?php
include_once("config.php");

if(isset($_POST['update'])){
$id=$_POST['id'];
$name=$_POST['name'];
$username=$_POST['username'];
$email=$_POST['email'];

$sql="UPDATE users SET username=:username, name= :name, email=:email WHERE id=:id";

$prep=$conn->prepare($sql);
$prep->bindParam(':id', $id);
$prep->bindParam(':name', $name);
$prep->bindParam(':username', $username);
$prep->bindParam(':email', $email);

$prep->execute();

header("Location:dashboard.php");
}