<?php require_once 'views/layout.php'; ?>

<!-- Main Content -->
<main class="app-main">
    <div class="page-header">
        <div class="page-title">
            <h1>Validation des Congés</h1>
        </div>
    </div>

    <div class="page-content">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-check-circle"></i>
                    Demandes en Attente de Validation
                </div>
                <div>
                    <form method="GET" class="d-inline-flex gap-2">
                        <input type="hidden" name="controller" value="leave">
                        <input type="hidden" name="action" value="approve">
                        <select name="status" class="form-select form-select-sm">
                            <option value="en_attente" <?= $_GET['status'] ?? '' === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="approuve_final" <?= $_GET['status'] ?? '' === 'approuve_final' ? 'selected' : '' ?>>Approuvés</option>
                            <option value="refuse" <?= $_GET['status'] ?? '' === 'refuse' ? 'selected' : '' ?>>Refusés</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success'] ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error'] ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Type</th>
                                <th>Période</th>
                                <th>Durée</th>
                                <th>Date Demande</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($leaves)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-inbox display-4 d-block mb-2 text-muted"></i>
                                        Aucune demande de congé à afficher
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($leaves as $leave): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($leave['nom'] . ' ' . $leave['prenom']) ?></td>
                                        <td><?= htmlspecialchars($leave['type_name']) ?></td>
                                        <td>
                                            Du <?= date('d/m/Y', strtotime($leave['start_date'])) ?><br>
                                            au <?= date('d/m/Y', strtotime($leave['end_date'])) ?>
                                        </td>
                                        <td><?= $leave['duration'] ?> jours</td>
                                        <td><?= date('d/m/Y', strtotime($leave['created_at'])) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = [
                                                'en_attente' => 'warning',
                                                'approuve_n1' => 'info',
                                                'approuve_final' => 'success',
                                                'refuse' => 'danger',
                                                'annule' => 'secondary'
                                            ];
                                            $statusLabels = [
                                                'en_attente' => 'En attente',
                                                'approuve_n1' => 'Approuvé N1',
                                                'approuve_final' => 'Approuvé Final',
                                                'refuse' => 'Refusé',
                                                'annule' => 'Annulé'
                                            ];
                                            ?>
                                            <span class="badge bg-<?= $statusClass[$leave['status']] ?>">
                                                <?= $statusLabels[$leave['status']] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="index.php?controller=leave&action=view&id=<?= $leave['id'] ?>" 
                                                   class="btn btn-info" title="Voir les détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php if ($leave['status'] === 'en_attente'): ?>
                                                    <form action="index.php?controller=leave&action=updateStatus" 
                                                          method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?= $leave['id'] ?>">
                                                        <input type="hidden" name="status" value="approuve_final">
                                                        <button type="submit" class="btn btn-success" title="Approuver">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="index.php?controller=leave&action=updateStatus" 
                                                          method="POST" class="d-inline">
                                                        <input type="hidden" name="id" value="<?= $leave['id'] ?>">
                                                        <input type="hidden" name="status" value="refuse">
                                                        <button type="submit" class="btn btn-danger" title="Refuser">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    </form>
                                                <?php elseif ($leave['status'] === 'approuve_final'): ?>
                                                    <a href="index.php?controller=leave&action=printDecision&id=<?= $leave['id'] ?>" 
                                                       class="btn btn-primary" target="_blank" title="Imprimer la décision">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
