<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ & Messages - Admin Dashboard</title>
<link rel="stylesheet" href="/deskly/admin/src/css/admin.css">
<style>
/* Input + Edit Button Container */
.input-group {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Clean modern input */
.reply-input {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    color: #111;
    font-size: 14px;
    flex: 1;
}

.reply-input:focus {
    outline: none;
    border-color: #007aff;
}

/* Disabled input style */
.reply-input[disabled] {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Buttons */
.action-btn {
    padding: 6px 14px;
    border-radius: 6px;
    border: none;
    font-size: 13px;
    cursor: pointer;
    transition: background 0.2s;
}

/* Edit button (next to input) */
.edit-reply {
    background-color: #ddd;
    color: #111;
}

/* Submit button */
.submit-reply {
    background-color: #007aff;
    color: #fff;
}

/* Delete button */
.delete {
    background-color: #ff3b30;
    color: #fff;
}

/* Hover effect */
.action-btn:hover {
    opacity: 0.85;
}

/* Action buttons container (Submit + Delete) */
.action-buttons {
    display: flex;
    gap: 6px;
}

/* Status colors */
.status-pending { color: red; font-weight: bold; }
.status-answered { color: green; font-weight: bold; }
</style>
</head>
<body>

<?php include __DIR__.'/src/includes/header.php'; ?>

<div class="content-container">
    <h1>FAQ & Messages Management</h1>

    <div class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div id="faq-container">
            <!-- Top 4 FAQs will be populated dynamically -->
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
                    <th>Answer</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Messages populated dynamically -->
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__.'/src/includes/footer.php'; ?>

<script>
async function fetchMessages() {
    const tbody = document.querySelector('.messages-table tbody');
    tbody.innerHTML = '';

    try {
        const res = await fetch('/deskly/backend/api/getmessages.php');
        const messages = await res.json();

        messages.forEach(msg => {
            const tr = document.createElement('tr');
            const statusClass = msg.status === 'Answered' ? 'status-answered' : 'status-pending';

            tr.innerHTML = `
                <td>${msg.id}</td>
                <td>${msg.name}</td>
                <td>${msg.email}</td>
                <td>${msg.message}</td>
                <td>
                    <div class="input-group">
                        <input type="text" class="reply-input" value="${msg.answer ?? ''}" placeholder="Type your reply..." disabled>
                        <button class="action-btn edit-reply" data-id="${msg.id}">Edit</button>
                    </div>
                </td>
                <td class="${statusClass}">${msg.status}</td>
                <td>${new Date(msg.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn submit-reply" data-id="${msg.id}">Submit</button>
                        <button class="action-btn delete" data-id="${msg.id}">Delete</button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });

        addActionListeners();
    } catch (err) {
        console.error('Failed to fetch messages:', err);
    }
}

function addActionListeners() {
    // Edit button enables input
    document.querySelectorAll('.edit-reply').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            const input = row.querySelector('.reply-input');
            input.disabled = false;
            input.focus();
        });
    });

    // Submit reply
    document.querySelectorAll('.submit-reply').forEach(btn => {
        btn.addEventListener('click', async () => {
            const messageId = btn.dataset.id;
            const row = btn.closest('tr');
            const input = row.querySelector('.reply-input');
            const answer = input.value.trim();
            if (!answer) return alert('Please enter a reply');

            try {
                const res = await fetch('/deskly/backend/api/replymessage.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: messageId, answer })
                });
                const data = await res.json();
                if (data.success) fetchMessages();
                else alert('Failed to submit reply.');
            } catch (err) {
                console.error(err);
                alert('Error submitting reply.');
            }
        });
    });

    // Delete message
    document.querySelectorAll('.delete').forEach(btn => {
        btn.addEventListener('click', async () => {
            const messageId = btn.dataset.id;
            if (!confirm('Are you sure you want to delete this message?')) return;

            try {
                const res = await fetch('/deskly/backend/api/deletemessage.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: messageId })
                });
                const data = await res.json();
                if (data.success) fetchMessages();
                else alert('Failed to delete message.');
            } catch (err) {
                console.error(err);
                alert('Error deleting message.');
            }
        });
    });
}


async function fetchTopFAQs() {
    const container = document.getElementById('faq-container');
    container.innerHTML = ''; // Clear previous content

    try {
        const res = await fetch('/deskly/backend/api/gettopfaq.php');
        const faqs = await res.json();

        faqs.forEach(faq => {
            const faqItem = document.createElement('div');
            faqItem.classList.add('faq-item');

            faqItem.innerHTML = `
                <div class="faq-question">${faq.question}</div>
                <div class="faq-answer">${faq.answer}</div>
            `;
            container.appendChild(faqItem);
        });

        // FAQ toggle functionality
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            item.querySelector('.faq-question').addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });

    } catch (err) {
        console.error('Failed to fetch FAQs:', err);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    fetchMessages(); // existing messages fetch
    fetchTopFAQs(); // fetch top FAQs
});

</script>
</body>
</html>
