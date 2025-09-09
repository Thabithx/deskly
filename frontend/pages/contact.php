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
    <!-- FAQ Section -->
    <section class="faq-section">
      <h2>Frequently Asked Questions</h2>

      <div class="faq-item">
        <h3>What products does Deskly offer? <span>+</span></h3>
        <div class="faq-answer">
          <p>We provide a wide range of premium desk accessories designed to boost productivity, organization, and workspace aesthetics.</p>
        </div>
      </div>

      <div class="faq-item">
        <h3>How long is delivery? <span>+</span></h3>
        <div class="faq-answer">
          <p>Delivery typically takes 3–5 business days within the region, and 7–14 days for international orders.</p>
        </div>
      </div>

      <div class="faq-item">
        <h3>Do you offer returns? <span>+</span></h3>
        <div class="faq-answer">
          <p>Yes, we offer a 14-day return policy if you are not satisfied with your purchase.</p>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
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
        <form>
          <input type="text" placeholder="Your Name" required>
          <input type="email" placeholder="Your Email" required>
          <textarea rows="6" placeholder="Your Message" required></textarea>
          <button type="submit">Send Message</button>
        </form>
      </div>
    </section>
  </main>

  <?php include __DIR__ . '/../includes/footer.php'; ?>

  <script src="/deskly/frontend/assets/js/script.js"></script>
</body>
</html>
