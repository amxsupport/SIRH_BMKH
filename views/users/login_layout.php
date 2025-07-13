<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRH - Direction Régionale de la Santé</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }
        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 1rem;
        }
        .login-card {
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            padding: 2rem 1rem;
            background: var(--navbar-bg);
            border-radius: 1rem 1rem 0 0;
            border-bottom: 1px solid var(--border-color);
        }
        .login-header i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        .login-header h1 {
            font-size: 1.5rem;
            margin: 0;
            color: var(--text-primary);
        }
        .login-header p {
            color: var(--text-secondary);
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] === 'error' ? 'danger' : $_SESSION['flash']['type'] ?> alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="bi bi-<?= $_SESSION['flash']['type'] === 'error' ? 'exclamation-triangle' : 'check-circle' ?>"></i>
                    <span><?= $_SESSION['flash']['message'] ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?php if (isset($content)) echo $content; ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
