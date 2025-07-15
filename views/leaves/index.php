<?php require_once 'views/layout.php'; ?>
<!-- Main Content -->
<main class="app-main">
    <div class="page-content">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-calendar"></i>
                    Gestion des demandes
                </div>
                <a href="index.php?controller=leave&action=create" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> Nouveau Congé
                </a>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <input type="hidden" name="controller" value="leave">
                    <input type="hidden" name="action" value="index">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="employee_id" class="form-select">
                                <option value="">Tous les employés</option>
                                <?php if (isset($employees) && is_array($employees)): ?>
                                    <?php foreach ($employees as $employee): ?>
                                        <option value="<?= $employee['employee_id'] ?>" 
                                                <?= isset($_GET['employee_id']) && $_GET['employee_id'] == $employee['employee_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($employee['nom'] . ' ' . $employee['prenom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente" <?= isset($_GET['status']) && $_GET['status'] == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="approuve_n1" <?= isset($_GET['status']) && $_GET['status'] == 'approuve_n1' ? 'selected' : '' ?>>Approuvé N1</option>
                                <option value="approuve_final" <?= isset($_GET['status']) && $_GET['status'] == 'approuve_final' ? 'selected' : '' ?>>Approuvé Final</option>
                                <option value="refuse" <?= isset($_GET['status']) && $_GET['status'] == 'refuse' ? 'selected' : '' ?>>Refusé</option>
                                <option value="annule" <?= isset($_GET['status']) && $_GET['status'] == 'annule' ? 'selected' : '' ?>>Annulé</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </div>
                </form>

                <?php if (!isset($leaves) || empty($leaves)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Aucun congé trouvé
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Employé</th>
                                    <th>Type</th>
                                    <th>Date début</th>
                                    <th>Date fin</th>
                                    <th>Statut</th>
                                    <th>Approuvé par</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leaves as $leave): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($leave['nom'] . ' ' . $leave['prenom']) ?></td>
                                        <td><?= htmlspecialchars($leave['type_name']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($leave['start_date'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($leave['end_date'])) ?></td>
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
                                            <?= $leave['approver_nom'] ? 
                                                htmlspecialchars($leave['approver_nom'] . ' ' . $leave['approver_prenom']) : 
                                                '-' 
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="index.php?controller=leave&action=view&id=<?= $leave['id'] ?>" 
                                                   class="btn btn-info" title="Voir les détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php if ($leave['status'] === 'en_attente'): ?>
                                                    <a href="index.php?controller=leave&action=edit&id=<?= $leave['id'] ?>" 
                                                       class="btn btn-warning" title="Modifier">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($leave['status'] === 'approuve_final'): ?>
                                                    <a href="index.php?controller=leave&action=printDecision&id=<?= $leave['id'] ?>" 
                                                       class="btn btn-secondary" target="_blank" title="Imprimer la décision">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
