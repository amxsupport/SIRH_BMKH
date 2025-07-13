<?php
// Get notifications from GLOBALS if not available directly
if (!isset($notifications) && isset($GLOBALS['notifications'])) {
    $notifications = $GLOBALS['notifications'];
}

// Initialize default values
if (!isset($notifications)) {
    $notifications = [];
    error_log("Warning: Notifications not available in retirement_notifications view");
}

// Calculate pending count safely
$pendingCount = !empty($notifications) ? count($notifications) : 0;
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">
            Notifications de Retraite
            <?php if ($pendingCount > 0): ?>
            <span class="badge bg-danger"><?php echo $pendingCount; ?></span>
            <?php endif; ?>
        </h4>
    </div>
    <div class="card-body">
        <?php if (empty($notifications)): ?>
            <div class="alert alert-info">
                Aucune notification de retraite en attente.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>PPR</th>
                            <th>Nom Complet</th>
                            <th>Corps</th>
                            <th>Date de Naissance</th>
                            <th>Date de Retraite</th>
                            <th>Établissement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notifications as $notif): ?>
                        <?php
                        // Safely get retirement date
                        try {
                            $retirement = new DateTime($notif['retirement_date'] ?? null);
                            $now = new DateTime();
                            $interval = $retirement->diff($now);
                            $months = $interval->y * 12 + $interval->m;
                        } catch (Exception $e) {
                            error_log("Error calculating retirement date: " . $e->getMessage());
                            $months = 0;
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($notif['ppr'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars(($notif['nom'] ?? '') . ' ' . ($notif['prenom'] ?? '')); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($notif['corps'] ?? '')); ?></td>
                            <td><?php echo isset($notif['date_naissance']) ? date('d/m/Y', strtotime($notif['date_naissance'])) : ''; ?></td>
                            <td>
                                <?php if (isset($notif['retirement_date'])): ?>
                                <span class="text-danger fw-bold">
                                    <?php echo date('d/m/Y', strtotime($notif['retirement_date'])); ?>
                                </span>
                                <br>
                                <small class="text-muted">
                                    (Dans <?php echo $months; ?> mois)
                                </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($notif['nom_etablissement'] ?? ''); ?>
                                <br>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($notif['province_etablissement'] ?? ''); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <?php if (isset($notif['employee_id'])): ?>
                                    <a href="index.php?action=view&id=<?php echo $notif['employee_id']; ?>" 
                                       class="btn btn-sm btn-info" title="Voir la fiche">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="index.php?action=markNotificationAsRead&id=<?php echo $notif['employee_id']; ?>" 
                                       class="btn btn-sm btn-success" title="Marquer comme lu">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="alert alert-warning mt-3">
                <i class="bi bi-info-circle"></i> 
                Les notifications sont générées automatiquement 6 mois avant la date de retraite.
                <p class="mb-0 mt-2">
                    L'âge de retraite est fixé à 63 ans pour tous les employés.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
