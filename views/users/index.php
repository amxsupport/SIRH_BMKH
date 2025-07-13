<?php require 'views/layout.php'; ?>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h2>User Management</h2>
        </div>
        <div class="col text-end">
            <a href="index.php?controller=user&action=register" class="btn btn-primary">Add New User</a>
        </div>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
            <?= $_SESSION['flash']['message'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'info' ?>"><?= ucfirst($user['role']) ?></span></td>
                                <td>
                                    <span class="badge bg-<?= $user['is_active'] ? 'success' : 'warning' ?>">
                                        <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td><?= $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never' ?></td>
                                <td>
                                    <a href="index.php?controller=user&action=edit&id=<?= $user['user_id'] ?>" 
                                       class="btn btn-sm btn-primary">Edit</a>
                                    <a href="index.php?controller=user&action=toggleStatus&id=<?= $user['user_id'] ?>" 
                                       class="btn btn-sm btn-<?= $user['is_active'] ? 'warning' : 'success' ?>"
                                       onclick="return confirm('Are you sure you want to <?= $user['is_active'] ? 'deactivate' : 'activate' ?> this user?')">
                                        <?= $user['is_active'] ? 'Deactivate' : 'Activate' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
