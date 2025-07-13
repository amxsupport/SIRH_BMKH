<?php require 'views/layout.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><?= isset($user) ? 'Edit User' : 'Add New User' ?></h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['flash'])): ?>
                        <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                            <?= $_SESSION['flash']['message'] ?>
                        </div>
                        <?php unset($_SESSION['flash']); ?>
                    <?php endif; ?>

                    <form method="POST" action="index.php?controller=user&action=<?= isset($user) ? 'edit&id=' . $user['user_id'] : 'register' ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= isset($user) ? htmlspecialchars($user['username']) : '' ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?= isset($user) ? htmlspecialchars($user['email']) : '' ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?= isset($user) ? htmlspecialchars($user['first_name']) : '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?= isset($user) ? htmlspecialchars($user['last_name']) : '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password"><?= isset($user) ? 'New Password (leave blank to keep current)' : 'Password *' ?></label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       <?= isset($user) ? '' : 'required' ?>>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role">Role *</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="user" <?= (isset($user) && $user['role'] === 'user') ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= (isset($user) && $user['role'] === 'admin') ? 'selected' : '' ?>>Administrator</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"><?= isset($user) ? 'Update User' : 'Create User' ?></button>
                            <a href="index.php?controller=user&action=index" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
