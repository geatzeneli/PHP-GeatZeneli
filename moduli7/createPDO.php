<?php
    $host='localhost';
    $user='root';
    $pass='';

try{
    $conn = new PDO("mysql:host=$host"),$user,$pass;
    $sql= "Create database testdb1";
    $conn->exec($sql);
}catch(Exception $e){
    echo "Database not created, something went wrong!";
}
?>