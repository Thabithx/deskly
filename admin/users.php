<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Deskly</title>
    <link rel="stylesheet" href="/deskly/admin/src/css/admin.css">
</head>
<body>
    <?php
        include __DIR__.'/../backend/controllers/db.php';
        $users = fetchUsers();
    ?>
    <?php include __DIR__.'/src/includes/header.php'?>

    <div class="users-container">
        <h1>User Management</h1>

        <!-- USERS TABLE -->
        <section>
            <h2>Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $userFound = false;
                        foreach ($users as $user):
                            if ($user['role'] === 'user'):
                                $userFound = true;
                    ?>
                        <tr id="user-<?= htmlspecialchars($user['id']) ?>">
                            <td>#<?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['first_name']." ".$user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td>
                                <button class="remove-btn" data-id="<?= htmlspecialchars($user['id']) ?>">Remove</button>
                            </td>
                        </tr>
                    <?php 
                            endif;
                        endforeach;
                        if (!$userFound): 
                    ?>
                        <tr><td colspan="5" style="text-align:center;">No users found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- ADMINISTRATORS TABLE -->
        <section>
            <h2>Administrators</h2>
            <table>
                <thead>
                    <tr>
                        <th>Admin ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $adminFound = false;
                        foreach ($users as $user):
                            if ($user['role'] === 'admin'):
                                $adminFound = true;
                    ?>
                        <tr id="user-<?= htmlspecialchars($user['id']) ?>">
                            <td>#<?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['first_name']." ".$user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td>
                                <button class="remove-btn" data-id="<?= htmlspecialchars($user['id']) ?>">Remove</button>
                            </td>
                        </tr>
                    <?php 
                            endif;
                        endforeach;
                        if (!$adminFound): 
                    ?>
                        <tr><td colspan="5" style="text-align:center;">No admins found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <?php include __DIR__.'/src/includes/footer.php'?>
    <script src="/deskly/admin/src/js/admin.js"></script>
</body>
</html>
