<?php
include 'db.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    mysqli_query($conn, "INSERT INTO products (name, price, quantity)
                          VALUES ('$name', '$price', '$quantity')");
    header("Location: index.php");
}
?>

<form method="post">
    Name: <input type="text" name="name" required><br><br>
    Price: <input type="number" step="0.01" name="price" required><br><br>
    Quantity: <input type="number" name="quantity" required><br><br>
    <button name="submit">Add</button>
</form>
