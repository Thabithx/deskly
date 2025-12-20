<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin - Deskly Dashboard</title>
    <link rel="stylesheet" href="/deskly/admin/src/css/admin.css">
    <style>
        .form-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            max-width: 550px;
            margin: 60px auto;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        .form-container h2 { 
            margin-bottom: 30px; 
            text-align: center;
            font-size: 1.8rem;
            color: #333;
            font-weight: 600;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        .form-group label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: 600;
            color: #444;
            font-size: 0.95rem;
        }
        .form-group input { 
            width: 100%; 
            padding: 12px 15px; 
            border: 2px solid #e0e0e0; 
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        .form-group input:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
        }
        .btn-submit {
            width: 100%; 
            padding: 14px; 
            background: #000; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 1.05rem;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        .btn-submit:hover { 
            background: #333;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none;}
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <?php include __DIR__.'/src/includes/header.php'?>

    <div class="form-container">
        <h2>Add New Administrator</h2>
        <form id="addAdminForm">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="firstName" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="lastName" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Create Admin</button>
        </form>
        <a href="users.php" class="back-link">Back to User Management</a>
    </div>

    <?php include __DIR__.'/src/includes/footer.php'?>

    <script>
        document.getElementById('addAdminForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const res = await fetch('/deskly/backend/api/create_admin.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.status === 'success') {
                    alert('Admin created successfully!');
                    window.location.href = 'users.php';
                } else {
                    alert(data.message || 'Error occurred');
                }
            } catch (err) {
                console.error(err);
                alert('Request failed');
            }
        });
    </script>
</body>
</html>
