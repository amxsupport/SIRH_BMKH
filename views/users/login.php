<div class="login-card">
    <div class="login-header">
        <i class="bi bi-hospital"></i>
        <h1>SIRH</h1>
        <p>Direction Régionale de la Santé</p>
    </div>
    <div class="card-body p-4">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                <?= $_SESSION['flash']['message'] ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <form method="POST" action="index.php?controller=user&action=login">
            <div class="form-group mb-3">
                <label class="form-label" for="username">Nom d'utilisateur</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
            </div>
            <div class="form-group mb-4">
                <label class="form-label" for="password">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Connexion
            </button>
        </form>
    </div>
</div>
