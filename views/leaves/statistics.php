<?php require_once 'views/layout.php'; ?>

<main class="app-main">
    <div class="page-header">
        <div class="page-title">
            <h1>Statistiques des Congés</h1>
        </div>
    </div>

    <div class="page-content">
        <!-- Filtres -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-funnel"></i>
                Filtres
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="controller" value="leave">
                    <input type="hidden" name="action" value="statistics">
                    
                    <div class="col-md-4">
                        <label for="year" class="form-label">Année</label>
                        <select name="year" id="year" class="form-select">
                            <?php 
                            $currentYear = date('Y');
                            for ($y = $currentYear; $y >= $currentYear - 5; $y--): 
                            ?>
                                <option value="<?= $y ?>" <?= isset($_GET['year']) && $_GET['year'] == $y ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="month" class="form-label">Mois</label>
                        <select name="month" id="month" class="form-select">
                            <option value="">Tous les mois</option>
                            <?php 
                            $months = [
                                1 => 'Janvier', 2 => 'Février', 3 => 'Mars',
                                4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
                                7 => 'Juillet', 8 => 'Août', 9 => 'Septembre',
                                10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                            ];
                            foreach ($months as $num => $name): 
                            ?>
                                <option value="<?= $num ?>" <?= isset($_GET['month']) && $_GET['month'] == $num ? 'selected' : '' ?>>
                                    <?= $name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">
                            <i class="bi bi-search"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cartes récapitulatives -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Congés</h5>
                        <h2 class="mb-0"><?= $stats['total_leaves'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Congés Approuvés</h5>
                        <h2 class="mb-0"><?= $stats['approved_leaves'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title">En Attente</h5>
                        <h2 class="mb-0"><?= $stats['pending_leaves'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Congés Refusés</h5>
                        <h2 class="mb-0"><?= $stats['rejected_leaves'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-pie-chart"></i>
                        Distribution par Type de Congé
                    </div>
                    <div class="card-body">
                        <canvas id="leaveTypesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-bar-chart"></i>
                        Distribution Mensuelle
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau détaillé -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-table"></i>
                Statistiques Détaillées par Type de Congé
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Type de Congé</th>
                                <th>Total</th>
                                <th>Approuvés</th>
                                <th>En Attente</th>
                                <th>Refusés</th>
                                <th>Durée Moyenne (jours)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['by_type'] ?? [] as $type): ?>
                            <tr>
                                <td><?= htmlspecialchars($type['name']) ?></td>
                                <td><?= $type['total'] ?></td>
                                <td><?= $type['approved'] ?></td>
                                <td><?= $type['pending'] ?></td>
                                <td><?= $type['rejected'] ?></td>
                                <td><?= number_format($type['avg_duration'], 1) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des types de congés
    new Chart(document.getElementById('leaveTypesChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($stats['by_type'] ?? [], 'name')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($stats['by_type'] ?? [], 'total')) ?>,
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                    '#858796', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique de distribution mensuelle
    new Chart(document.getElementById('monthlyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($stats['monthly'] ?? [])) ?>,
            datasets: [{
                label: 'Nombre de Congés',
                data: <?= json_encode(array_values($stats['monthly'] ?? [])) ?>,
                backgroundColor: '#4e73df'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
