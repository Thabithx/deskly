<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Deskly</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <section class="auth-section">
            <div class="auth-container">
                <h2>Login</h2>
               <form id="loginForm" method="POST">
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="auth-btn">Login</button>
                </form>
                <p class="auth-link">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('/deskly/backend/api/login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('An error occurred during login. Please try again.');
            }
        });
    </script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
