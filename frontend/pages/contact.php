<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Deskly</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=1">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <section class="contact-section">
            <h2>Contact Us</h2>
            <div class="contact-container">
                <div class="contact-info">
                    <h3>Get in Touch</h3>
                    <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <h4>Email</h4>
                            <p>info@deskly.com</p>
                        </div>
                        <div class="contact-item">
                            <h4>Phone</h4>
                            <p>+1 (555) 123-4567</p>
                        </div>
                        <div class="contact-item">
                            <h4>Address</h4>
                            <p>123 Office Street<br>Business District, BD 12345</p>
                        </div>
                    </div>
                </div>
                
                <form class="contact-form" id="contactForm" onsubmit="handleContactForm(event)">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject:</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="contact-btn">Send Message</button>
                </form>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
