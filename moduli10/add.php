<?php
include_once("config.php");

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // FIX: Missing closing parenthesis in SQL
    $sql = "INSERT INTO users (name, username, email) VALUES (:name, :username, :email)";
    $sqlQuery = $conn->prepare($sql);

    $sqlQuery->bindParam(":name", $name);
    $sqlQuery->bindParam(":username", $username);
    $sqlQuery->bindParam(":email", $email);

    $sqlQuery->execute();

    echo "Data saved successfully";

    // FIX: Correct header redirect syntax
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- FIX: method='post' missing -->
    <form action="" method="post">
        <input type="text" name="name" placeholder="Name">
        <input type="text" name="username" placeholder="Username">
        <input type="email" name="email" placeholder="Email">

        <!-- FIX: input type spelling + button markup -->
        <input type="submit" name="submit" value="Add">
    </form>
</body>
</html>
