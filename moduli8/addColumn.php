<?php
$host='localhost';
$db='testdb';
$user='root';
$pass='';

try{
    $pdo=new PDO("mysql:host=$host;dbname=$db",$user,$pass);

    // $sql='ALTER TABLE  users ADD email varchar(255)';

    $pdo->exec($sql);
    echo "Column Created Succesfully";
}catch(PDOExcpetion $e){
    echo "Error:". $e->getMessage();
}