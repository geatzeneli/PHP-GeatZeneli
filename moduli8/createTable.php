<?php

$host='localhost';
$db='test';
$user='root';
$pass='';

try{

    $pdo=new PDO("mysql:host=$host;dbname=$db",$user,$pass);
    $sql='CREATE TABLE users( id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username varchar(30) NOT NULL,
    password varchar(50) NOT NULL)';

    $pdo->exec($sql);

    echo "Table created succesfully!";
}catch(Exception $e){
    echo "Error creating table" . $e->getMessage();   
}
?>