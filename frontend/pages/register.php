<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Deskly</title>
    <link rel="stylesheet" href="/deskly/frontend/assets/css/styles.css?v=2">
    
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <section class="auth-section">
            <div class="auth-container">
                <h2>Sign Up</h2>
                <form id="registerForm" onsubmit="handleRegister(event)">
                    <div class="form-group">
                        <input placeholder="First Name" type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <input placeholder="Last Name" type="text" id="lastName" name="lastName" required>
                    </div>
                    <div class="form-group">
                        <input placeholder="Email" type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <input placeholder="Password" type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <input placeholder="Confirm Password" type="password" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="auth-btn">Register</button>
                </form>
                <p class="auth-link">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
