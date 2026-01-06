<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AETERNA | Monolithic Architecture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="background-overlay"></div>

    <nav class="navbar">
        <div class="logo">ÆTERNA</div>
        <div class="nav-links">
            <a href="#about">Philosophy</a>
            <a href="#contact">Inquiry</a>
        </div>
    </nav>

    <main>
        <section class="hero fade-in">
            <div class="hero-content">
                <span class="subtitle">ESTABLISHED 2026</span>
                <h1>Architecture for the <br>End of Time.</h1>
                <p>We design structures that breathe with the earth and stand against the erosion of trends. Minimalist. Permanent. Monolithic.</p>
            </div>
        </section>

        <section id="about" class="info-grid slide-up">
            <div class="info-card">
                <h3>Our Vision</h3>
                <p>Aeterna specializes in high-contrast obsidian structures. By utilizing local stone and light-path engineering, we create spaces that feel like modern sanctuaries.</p>
            </div>
            <div class="info-card">
                <h3>The Method</h3>
                <p>Every project begins with a deep study of shadows. We don't just build walls; we capture darkness and frame the sun.</p>
            </div>
        </section>

        <section id="contact" class="contact-section fade-in">
            <div class="contact-box">
                <div class="contact-text">
                    <h2>Begin a Legacy</h2>
                    <p>Request a consultation for private estates or commercial monoliths.</p>
                </div>
                
                <form action="" method="POST" class="modern-form">
                    <input type="text" name="name" placeholder="FULL NAME" required>
                    <input type="email" name="email" placeholder="EMAIL ADDRESS" required>
                    <textarea name="message" rows="4" placeholder="PROJECT DESCRIPTION" required></textarea>
                    <button type="submit" name="submit">Send Message</button>
                    
                    <?php
                    if(isset($_POST['submit'])){
                        $name = $conn->real_escape_string($_POST['name']);
                        $email = $conn->real_escape_string($_POST['email']);
                        $msg = $conn->real_escape_string($_POST['message']);

                        $sql = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$msg')";
                        if($conn->query($sql) === TRUE){
                            echo "<p class='success-msg'>Your inquiry has been logged in our secure archives.</p>";
                        }
                    }
                    ?>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 AETERNA ARCHITECTURAL GROUP. ALL RIGHTS RESERVED.</p>
    </footer>
</body>
</html>