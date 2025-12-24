<?php
include 'db.php';
$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    mysqli_query($conn, "UPDATE products
                         SET name='$name', price='$price', quantity='$quantity'
                         WHERE id=$id");
    header("Location: index.php");
}
?>

<form method="post">
    Name: <input type="text" name="name" value="<?= $product['name'] ?>"><br><br>
    Price: <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>"><br><br>
    Quantity: <input type="number" name="quantity" value="<?= $product['quantity'] ?>"><br><br>
    <button name="update">Update</button>
</form>
