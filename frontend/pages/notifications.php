<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Deskly</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=1">
    <style>
        .notifications-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .notifications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .notifications-header h1 {
            font-size: 2rem;
            margin: 0;
        }

        .notification-count {
            background: #000;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .notification-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid #000;
            display: flex;
            gap: 15px;
            transition: all 0.2s ease;
        }

        .notification-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .notification-icon.message {
            background: #f5f5f5;
            color: #000;
        }

        .notification-icon.order {
            background: #f5f5f5;
            color: #000;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 8px;
            color: #000;
        }

        .notification-message {
            color: #555;
            font-size: 0.95rem;
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .notification-details {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #333;
            border-left: 3px solid #000;
        }

        .notification-date {
            font-size: 0.85rem;
            color: #888;
            margin-top: 8px;
        }

        .notification-action {
            margin-top: 12px;
        }

        .notification-action a {
            display: inline-block;
            padding: 8px 18px;
            background: #000;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .notification-action a:hover {
            background: #333;
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #666;
        }

        .empty-state p {
            font-size: 1rem;
            color: #999;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php';?>

    <div class="notifications-container">
        <div class="notifications-header">
            <h1>Notifications</h1>
            <span class="notification-count" id="notificationCount">0</span>
        </div>

        <div class="notifications-list" id="notificationsList">
            <!-- Notifications will be loaded here -->
            <div class="empty-state">
                <div class="empty-state-icon">🔔</div>
                <h3>Loading notifications...</h3>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', loadNotifications);

        async function loadNotifications() {
            try {
                const res = await fetch('/deskly/backend/api/get_notifications.php');
                const data = await res.json();

                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                if (!data.success) {
                    showError('Failed to load notifications');
                    return;
                }

                const container = document.getElementById('notificationsList');
                const countEl = document.getElementById('notificationCount');
                
                if (data.notifications.length === 0) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <div class="empty-state-icon">🔔</div>
                            <h3>No notifications yet</h3>
                            <p>You'll see updates about your orders and message replies here.</p>
                        </div>
                    `;
                    countEl.textContent = '0';
                    return;
                }

                countEl.textContent = data.count;
                container.innerHTML = '';

                data.notifications.forEach(notif => {
                    const card = createNotificationCard(notif);
                    container.appendChild(card);
                });

            } catch (err) {
                console.error(err);
                showError('Error loading notifications');
            }
        }

        function createNotificationCard(notif) {
            const card = document.createElement('div');
            card.className = 'notification-card';

            const icon = notif.type === 'message_reply' ? '💬' : '📦';
            const iconClass = notif.type === 'message_reply' ? 'message' : 'order';
            
            const date = new Date(notif.date);
            const formattedDate = date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            let actionHTML = '';
            if (notif.link) {
                actionHTML = `<div class="notification-action">
                    <a href="${notif.link}">Track Order</a>
                </div>`;
            }

            let detailsHTML = '';
            if (notif.type === 'message_reply') {
                detailsHTML = `
                    <div class="notification-details">
                        <strong>Your Question:</strong> ${notif.original_question}<br><br>
                        <strong>Admin's Reply:</strong> ${notif.full_answer}
                    </div>
                `;
            }

            card.innerHTML = `
                <div class="notification-icon ${iconClass}">${icon}</div>
                <div class="notification-content">
                    <div class="notification-title">${notif.title}</div>
                    <div class="notification-message">${notif.message}</div>
                    ${detailsHTML}
                    <div class="notification-date">${formattedDate}</div>
                    ${actionHTML}
                </div>
            `;

            return card;
        }

        function showError(message) {
            const container = document.getElementById('notificationsList');
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">⚠️</div>
                    <h3>${message}</h3>
                    <p>Please try again later.</p>
                </div>
            `;
        }
    </script>
</body>
</html>
