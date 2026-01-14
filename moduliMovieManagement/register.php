<?php

include_once('config.php');
if(isset($_POST['sumbit'])){
    $emri=$_POST['emri'];
    $username=$_POST['username'];
    $email=$_POST['email'];
    $tempPass=$_POST['password'];
    $password=password_hash($tempPass,PASSWORD_DEFAULT);
    $tempConfirm=$_POST['confirm_password'];
    $confirm_password=password_hash($tempConfirm,PASSWORD_DEFAULT);
    $is_admin=$_POST['is_admin'];
}
