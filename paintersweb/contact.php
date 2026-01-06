<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $service = htmlspecialchars($_POST['service']);
    $message = htmlspecialchars($_POST['message']);

    // In a real scenario, you'd use mail() or a library like PHPMailer here.
    echo "<h1>Thank you, $name!</h1>";
    echo "<p>We have received your request for $service. We will contact you at $email soon.</p>";
    echo "<a href='index.php'>Return to Home</a>";
} else {
    header("Location: index.php");
}
?>