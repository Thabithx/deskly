  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Deskly</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=2">
  </head>
  <body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
      <div class="faq-section">
          <h2>Frequently Asked Questions</h2>
          <div id="faq-container">
              <!-- Top 4 FAQs -->
          </div>
      </div>

      <section class="contact-section">
        <div class="contact-info">
          <h2>Contact Us.</h2>
          <p style="margin-bottom: 30px">We’d love to hear from you. Reach out to us for any inquiries, support, or feedback.</p>

          <h3>Email</h3>
          <p>support@deskly.com</p>

          <h3>Phone</h3>
          <p>+94 77 123 4567</p>

          <h3>Address</h3>
          <p>No. 25, Main Street, Colombo, Sri Lanka</p>
        </div>

        <div class="contact-form">
          <form id="contact-form">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" rows="6" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
          </form>
        </div>

      </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="/deskly/frontend/assets/js/script.js"></script>
  </body>
  </html>
