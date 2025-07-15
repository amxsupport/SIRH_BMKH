<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#0d6efd">
    <meta name="description" content="Système d'Information des Ressources Humaines - Direction Régionale de la Santé Béni Mellal-Khénifra">
    <title>SIRH - Direction Régionale de la Santé</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link rel="apple-touch-icon" href="assets/img/logo.png">
    
    <!-- Preload critical resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- Loading indicator style -->
    <style>
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .loading.active {
            display: flex;
        }
    </style>
</head>
<body>
    <!-- Loading Indicator -->
    <div class="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>

    <?php
    // Check if this is the login page
    $isLoginPage = isset($_GET['controller']) && $_GET['controller'] === 'user' && $_GET['action'] === 'login';
    
    // Check if authentication is required
    if (!isset($_SESSION['user']) && !$isLoginPage) {
        header('Location: index.php?controller=user&action=login');
        exit;
    }

    // Handle flash messages
    if (isset($_SESSION['flash'])) {
        $flashMessage = $_SESSION['flash'];
        unset($_SESSION['flash']);
    }
    ?>
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-brand">
            <button type="button" class="nav-toggle">
                <i class="bi bi-list"></i>
            </button>
            <a href="index.php" class="brand-logo">
                <i class="bi bi-hospital"></i>
                <span>SIRH</span>
            </a>
        </div>
        
        <?php if (isset($_SESSION['user'])): ?>
        <div class="nav-profile">
            <span class="nav-user">
                <i class="bi bi-person-circle"></i>
                <?= htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']) ?>
            </span>
            <a href="index.php?controller=user&action=logout" class="btn btn-outline-light btn-sm ms-2">
                <i class="bi bi-box-arrow-right"></i>
                Déconnexion
            </a>
        </div>
        <?php endif; ?>
    </nav>

    <!-- Main Container -->
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="app-sidebar">
            <div class="sidebar-content">
                <div class="organization-info">
                    <h6 class="org-name">DRS Béni Mellal-Khénifra</h6>
                    <p class="org-type">Direction Régionale de la Santé</p>
                </div>

            <?php if (isset($_SESSION['user'])): ?>
            <div class="menu-section">
                <h6 class="menu-title">Menu Principal</h6>

                <ul class="menu-items">
                    <li>
                        <a href="index.php" class="<?php echo !isset($_GET['action']) || $_GET['action'] === 'dashboard' ? 'active' : ''; ?>">
                            <i class="bi bi-speedometer2"></i>
                            <span>Tableau de Bord</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="menu-section">
                <h6 class="menu-title">Gestion des Employés</h6>
                <ul class="menu-items">
                
                    <li>
                        <a href="index.php?action=index" class="<?php echo isset($_GET['action']) && $_GET['action'] === 'index' ? 'active' : ''; ?>">
                            <i class="bi bi-people"></i>
                            <span>Liste des Employés</span>
                        </a>
                    </li>
                        <li>
                            <a href="index.php?action=create" class="<?php echo isset($_GET['action']) && $_GET['action'] === 'create' ? 'active' : ''; ?>">
                                <i class="bi bi-person-plus"></i>
                                <span>Nouveau</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?action=statistics" class="<?php echo isset($_GET['action']) && $_GET['action'] === 'statistics' ? 'active' : ''; ?>">
                                <i class="bi bi-graph-up"></i>
                                <span>Statistiques</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?action=checkRetirements" class="<?php echo isset($_GET['action']) && $_GET['action'] === 'checkRetirements' ? 'active' : ''; ?>">
                                <i class="bi bi-bell"></i>
                                <span>Notifications</span>
                                <?php if (isset($pendingNotifications) && $pendingNotifications > 0): ?>
                                <span class="menu-badge"><?php echo $pendingNotifications; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="menu-section">
                    <h6 class="menu-title">Gestion des Stagiaires</h6>
                    <ul class="menu-items">
                        <li>
                            <a href="index.php?action=internDashboard" class="<?php echo isset($_GET['action']) && $_GET['action'] === 'internDashboard' ? 'active' : ''; ?>">
                                <i class="bi bi-speedometer2"></i>
                                <span>Tableau de Bord Stagiaires</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?action=interns" class="<?php echo isset($_GET['action']) && $_GET['action'] === 'interns' ? 'active' : ''; ?>">
                                <i class="bi bi-mortarboard"></i>
                                <span>Liste des Stagiaires</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="menu-section">
                    <h6 class="menu-title">Gestion des Congés</h6>
                    <ul class="menu-items">
                        <li>
                            <a href="index.php?controller=leave&action=index" class="<?php echo isset($_GET['controller']) && $_GET['controller'] === 'leave' && $_GET['action'] === 'index' ? 'active' : ''; ?>">
                                <i class="bi bi-calendar-check"></i>
                                <span>Liste des Congés</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?controller=leave&action=create" class="<?php echo isset($_GET['controller']) && $_GET['controller'] === 'leave' && $_GET['action'] === 'create' ? 'active' : ''; ?>">
                                <i class="bi bi-calendar-plus"></i>
                                <span>Nouveau Congé</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?controller=leave&action=approve" class="<?php echo isset($_GET['controller']) && $_GET['controller'] === 'leave' && $_GET['action'] === 'approve' ? 'active' : ''; ?>">
                                <i class="bi bi-check2-circle"></i>
                                <span>Validation des Congés</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?controller=leave&action=balance" class="<?php echo isset($_GET['controller']) && $_GET['controller'] === 'leave' && $_GET['action'] === 'balance' ? 'active' : ''; ?>">
                                <i class="bi bi-calculator"></i>
                                <span>Solde des Congés</span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?controller=leave&action=statistics" class="<?php echo isset($_GET['controller']) && $_GET['controller'] === 'leave' && $_GET['action'] === 'statistics' ? 'active' : ''; ?>">
                                <i class="bi bi-graph-up"></i>
                                <span>Statistiques des Congés</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="menu-section">
                    <h6 class="menu-title">Rapports</h6>
                    <ul class="menu-items">
                        <li>
                            <a href="index.php?action=reports" class="<?php echo isset($_GET['action']) && $_GET['action'] === 'reports' ? 'active' : ''; ?>">
                                <i class="bi bi-file-earmark-text"></i>
                                <span>Rapports & Exports</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <div class="menu-section">
                    <h6 class="menu-title">Administration</h6>
                    <ul class="menu-items">
                        <li>
                            <a href="index.php?controller=user&action=index" class="<?php echo isset($_GET['controller']) && $_GET['controller'] === 'user' ? 'active' : ''; ?>">
                                <i class="bi bi-people-fill"></i>
                                <span>Gestion des Utilisateurs</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="sidebar-footer">
                <div class="app-version">
                    <span>DRSPS_BMKH</span>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            <div class="page-header">
                <?php
                $title = 'Tableau de Bord';
                if (isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'index': $title = 'Liste des Employés'; break;
                        case 'create': $title = 'Nouvel Employé'; break;
                        case 'statistics': $title = 'Statistiques'; break;
                        case 'checkRetirements': $title = 'Notifications de Retraite'; break;
                        case 'view': $title = 'Détails de l\'Employé'; break;
                        case 'update': $title = 'Modifier l\'Employé'; break;
                        case 'reports': $title = 'Rapports et Exports'; break;
                        case 'internDashboard': $title = 'Tableau de Bord Stagiaires'; break;
                        case 'interns': $title = 'Liste des Stagiaires'; break;
                        case 'createIntern': $title = 'Nouveau Stagiaire'; break;
                        case 'viewIntern': $title = 'Détails du Stagiaire'; break;
                        case 'updateIntern': $title = 'Modifier le Stagiaire'; break;
                        // Leave management titles
                        case 'leaves': $title = 'Liste des Congés'; break;
                        case 'createLeave': $title = 'Nouveau Congé'; break;
                        case 'editLeave': $title = 'Modifier le Congé'; break;
                        case 'viewLeave': $title = 'Détails du Congé'; break;
                        case 'approveLeave': $title = 'Validation des Congés'; break;
                        case 'leaveBalance': $title = 'Solde des Congés'; break;
                        case 'leaveStatistics': $title = 'Statistiques des Congés'; break;
                    }
                }
                ?>
                <div class="page-title">
                    <h1>
                        <?php
                        $icon = 'bi-speedometer2'; // Default icon
                        if (isset($_GET['action'])) {
                            switch ($_GET['action']) {
                                case 'index': $icon = 'bi-people'; break;
                                case 'create': $icon = 'bi-person-plus'; break;
                                case 'statistics': $icon = 'bi-graph-up'; break;
                                case 'checkRetirements': $icon = 'bi-bell'; break;
                                case 'view': $icon = 'bi-person-vcard'; break;
                                case 'update': $icon = 'bi-pencil-square'; break;
                                case 'reports': $icon = 'bi-file-earmark-text'; break;
                                case 'internDashboard': $icon = 'bi-speedometer2'; break;
                                case 'interns': $icon = 'bi-mortarboard'; break;
                                case 'createIntern': $icon = 'bi-person-plus'; break;
                                case 'viewIntern': $icon = 'bi-person-vcard'; break;
                                case 'updateIntern': $icon = 'bi-pencil-square'; break;
                                case 'leaves': $icon = 'bi-calendar-check'; break;
                                case 'createLeave': $icon = 'bi-calendar-plus'; break;
                                case 'editLeave': $icon = 'bi-pencil-square'; break;
                                case 'viewLeave': $icon = 'bi-calendar-event'; break;
                                case 'approveLeave': $icon = 'bi-check2-circle'; break;
                                case 'leaveBalance': $icon = 'bi-calculator'; break;
                                case 'leaveStatistics': $icon = 'bi-graph-up'; break;
                            }
                        }
                        ?>
                        <i class="bi <?php echo $icon; ?>"></i>
                        <?php echo $title; ?>
                    </h1>
                </div>

               
            </div>

            <div class="page-content">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="alert-content">
                            <i class="bi bi-exclamation-triangle"></i>
                            <span><?php echo $error; ?></span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="alert-content">
                            <i class="bi bi-check-circle"></i>
                            <span><?php echo $success; ?></span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php
                if (isset($content)) {
                    // Make variables available to the included view
                    if (isset($employees)) $GLOBALS['employees'] = $employees;
                    if (isset($pendingNotifications)) $GLOBALS['pendingNotifications'] = $pendingNotifications;
                    
                    // Debug output
                    error_log("Including content file: " . $content);
                    if (isset($employees)) {
                        error_log("Number of employees being passed to view: " . count($employees));
                    }
                    
                    include $content;
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
