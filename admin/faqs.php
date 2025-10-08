<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ & Messages - Admin Dashboard</title>
    <link rel="stylesheet" href="/deskly/admin/src/css/admin.css">
</head>
<body>
    <?php include __DIR__.'/src/includes/header.php' ?>

    <div class="content-container">
        <h1>FAQ & Messages Management</h1>

        <div class="faq-section">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-item">
                <div class="faq-question">How can I reset a user's password?</div>
                <div class="faq-answer">
                    Go to the Users Management page, select the user, and click on "Reset Password".
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">How do I add a new FAQ?</div>
                <div class="faq-answer">
                    Click on the "Add FAQ" button, fill in the question and answer, and save.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">Can I export user messages?</div>
                <div class="faq-answer">
                    Yes, use the "Export Messages" button to download all messages in CSV format.
                </div>
            </div>
        </div>

        <div class="messages-section">
            <h2>User Messages</h2>
            <table class="messages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>john@example.com</td>
                        <td>Hello, I need help with my order.</td>
                        <td>2025-10-05</td>
                        <td>
                            <button class="action-btn reply">Reply</button>
                            <button class="action-btn delete">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Smith</td>
                        <td>jane@example.com</td>
                        <td>I want to change my shipping address.</td>
                        <td>2025-10-04</td>
                        <td>
                            <button class="action-btn reply">Reply</button>
                            <button class="action-btn delete">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php include __DIR__.'/src/includes/footer.php' ?>

    <script>
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            item.querySelector('.faq-question').addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
