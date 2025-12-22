<?php

include_once('config.php');

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $username=$_POST['username'];
    $email=$_POST['email'];
    $tempPass=$_POST['password'];
    $password=password_hash($tempPass,PASSWORD_DEFAULT);
    
    if(empty($name) || empty($username) || empty($email) || empty($password)){
        echo "You need to fill all the fields";
    }else{
        $sql="SELECT username from users WHERE username=:username";

        $tempSQL=$conn->prepare($sql);
        $tempSQL->bindParam(':username',$username);
        $tempSQL->execute();

        if($tempSQL->rowCount()>0){
            echo "Username exists";
            header("refresh:2;url=signup.php");
        }
    }else{
        $sql="SELECT username from users WHERE username=:username";

        $tempSQL=$conn->prepare($sql);
        $tempSQL->bindParam(':username',$username);
        $tempSQL->execute();

        if($tempSQL->rowCount()>0){
            echo "Username exists";
            header("refresh:2;url=signup.php");
        }else{
            $sql="INSERT INTO users (name,username,email,password) VALUES (:name,:username,:email,:password)";
            $insertSql=$conn->prepare($sql);

            $insertSql->bindParam(':name',$name);
            $insertSql->bindParam(':username',$username);
            $insertSql->bindParam(':email',$email);
            $insertSql->bindParam(':password',$password);

            $insertSql->execute();
            echo "Data saved succesfully";
            header("refresh:2;url=login.php"); 
        }
    }
}