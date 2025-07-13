<?php require_once 'views/layout.php'; ?>

<!-- Main Content -->
<main class="app-main">
    <div class="page-header">
        <div class="page-title">
            <h1>Solde des Congés</h1>
        </div>
    </div>

    <div class="page-content">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-calculator"></i>
                État des Soldes de Congés par Employé
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error'] ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form method="GET" class="mb-4">
                    <input type="hidden" name="controller" value="leave">
                    <input type="hidden" name="action" value="balance">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label for="employee_id" class="form-label">Sélectionner un employé</label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">Sélectionner un employé</option>
                                <?php foreach ($employees as $employee): ?>
                                    <option value="<?= $employee['employee_id'] ?>" 
                                            <?= isset($_GET['employee_id']) && $_GET['employee_id'] == $employee['employee_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($employee['nom'] . ' ' . $employee['prenom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search"></i> Afficher le solde
                            </button>
                        </div>
                    </div>
                </form>

                <?php if (!isset($_GET['employee_id'])): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-search display-1 text-muted mb-3"></i>
                        <p class="text-muted">Veuillez sélectionner un employé pour afficher son solde de congés</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $selectedEmployee = array_filter($employees, function($emp) {
                        return $emp['employee_id'] == $_GET['employee_id'];
                    });
                    $selectedEmployee = reset($selectedEmployee);
                    ?>
                    <div class="mb-4">
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">
                            <?= htmlspecialchars($selectedEmployee['nom'] . ' ' . $selectedEmployee['prenom']) ?>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Type de Congé</th>
                                        <th>Total</th>
                                        <th>Utilisé</th>
                                        <th>Reste</th>
                                        <th>État</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($balances[$selectedEmployee['employee_id']] as $balance): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($balance['type_name']) ?></td>
                                            <td><?= $balance['total'] ?> jours</td>
                                            <td><?= $balance['used'] ?> jours</td>
                                            <td><?= $balance['remaining'] ?> jours</td>
                                            <td style="width: 30%">
                                                <?php 
                                                $percentage = ($balance['remaining'] / $balance['total']) * 100;
                                                $progressClass = $percentage > 50 ? 'success' : ($percentage > 25 ? 'warning' : 'danger');
                                                ?>
                                                <div class="progress">
                                                    <div class="progress-bar bg-<?= $progressClass ?>"
                                                         role="progressbar"
                                                         style="width: <?= $percentage ?>%"
                                                         aria-valuenow="<?= $percentage ?>"
                                                         aria-valuemin="0"
                                                         aria-valuemax="100">
                                                        <?= round($percentage) ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['employee_id']) && empty($balances[$_GET['employee_id']])): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Aucun solde de congé disponible pour cet employé
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-info-circle"></i>
                Informations sur les Congés
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Types de Congés Standards :</h6>
                        <ul class="mb-3">
                            <li>Congé annuel : 30 jours ouvrables</li>
                            <li>Congé de maternité : 14 semaines (98 jours)</li>
                            <li>Congé de paternité : 3 jours</li>
                            <li>Congé de pèlerinage : 30 jours (une fois dans la carrière)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Congés Exceptionnels :</h6>
                        <ul class="mb-0">
                            <li>Mariage : 5 jours</li>
                            <li>Décès d'un proche : 3 jours</li>
                            <li>Naissance : 3 jours</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
