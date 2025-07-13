<?php require_once 'views/layout.php'; ?>

<!-- Main Content -->
<main class="app-main">
    <div class="page-header">
        <div class="page-title">
            <h1>Détails du Congé</h1>
        </div>
    </div>

    <div class="page-content">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-calendar"></i>
                Détails de la Demande de Congé
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="fw-bold">Employé</label>
                        <p><?= htmlspecialchars($leave['nom'] . ' ' . $leave['prenom']) ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Type de Congé</label>
                        <p><?= htmlspecialchars($leave['type_name']) ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Durée</label>
                        <p><?= $leave['duration'] ?> jours</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Période</label>
                        <p>Du <?= date('d/m/Y', strtotime($leave['start_date'])) ?> 
                           au <?= date('d/m/Y', strtotime($leave['end_date'])) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Motif</label>
                        <p><?= nl2br(htmlspecialchars($leave['reason'])) ?></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="fw-bold">Statut</label>
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
                    <p>
                        <span class="badge bg-<?= $statusClass[$leave['status']] ?>">
                            <?= $statusLabels[$leave['status']] ?>
                        </span>
                    </p>
                </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Approuvé par</label>
                        <p>
                        <?php if ($leave['approver_nom']): ?>
                            <?= htmlspecialchars($leave['approver_nom'] . ' ' . $leave['approver_prenom']) ?>
                            <br>
                            <small class="text-muted">
                                Le <?= date('d/m/Y H:i', strtotime($leave['approved_at'])) ?>
                            </small>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </p>
                </div>
                
                <hr class="my-4">
                
                <div class="mb-3">
                <a href="index.php?controller=leave&action=index" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
                
                <?php if ($leave['status'] === 'en_attente'): ?>
                    <a href="index.php?controller=leave&action=edit&id=<?= $leave['id'] ?>" 
                       class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <form action="index.php?controller=leave&action=updateStatus" method="POST" 
                              class="d-inline">
                            <input type="hidden" name="id" value="<?= $leave['id'] ?>">
                            <input type="hidden" name="status" value="approuve_final">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check"></i> Approuver
                            </button>
                        </form>
                        
                        <form action="index.php?controller=leave&action=updateStatus" method="POST" 
                              class="d-inline">
                            <input type="hidden" name="id" value="<?= $leave['id'] ?>">
                            <input type="hidden" name="status" value="refuse">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x"></i> Rejeter
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($leave['status'] === 'approuve_final'): ?>
                    <a href="index.php?controller=leave&action=printDecision&id=<?= $leave['id'] ?>" 
                       class="btn btn-success" target="_blank">
                        <i class="bi bi-printer"></i> Imprimer la décision
                    </a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
