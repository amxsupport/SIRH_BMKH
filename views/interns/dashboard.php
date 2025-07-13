<div class="row">
    <!-- Total Interns Card -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total des Stagiaires</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['total_interns']; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Interns Card -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Stages en Cours</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['active_interns']; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Services Card -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Services d'Accueil</h6>
                        <?php $service_count = count($stats['by_service']); ?>
                        <h2 class="mt-2 mb-0"><?php echo $service_count; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Educational Institutions -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Établissements d'Origine</h6>
                        <?php $etab_count = count($stats['by_etablissement']); ?>
                        <h2 class="mt-2 mb-0"><?php echo $etab_count; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-bank"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Sections -->
<div class="row">
    <!-- RÉPARTITION PAR STATUT -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">RÉPARTITION PAR STATUT</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="statusDistribution"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Statut</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['by_status'] as $status): ?>
                            <tr>
                                <td><?php echo ucfirst(htmlspecialchars($status['status'])); ?></td>
                                <td><?php echo $status['count']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- RÉPARTITION PAR ÉTABLISSEMENT D'ORIGINE -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">RÉPARTITION PAR ÉTABLISSEMENT D'ORIGINE</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="etablissementDistribution"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Établissement</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['by_etablissement'] as $etab): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($etab['etablissement_education']); ?></td>
                                <td><?php echo $etab['count']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- RÉPARTITION PAR SERVICE -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">RÉPARTITION PAR SERVICE</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="serviceDistribution"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['by_service'] as $service): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($service['service_etablissement']); ?></td>
                                <td><?php echo $service['count']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Interns -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Stagiaires Récents</h5>
        <a href="index.php?action=interns" class="btn btn-primary btn-sm">Voir Tout</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom Complet</th>
                        <th>Établissement</th>
                        <th>Service</th>
                        <th>Période</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($interns, 0, 5) as $intern): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($intern['nom'] . ' ' . $intern['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($intern['etablissement_education']); ?></td>
                        <td><?php echo htmlspecialchars($intern['service_etablissement']); ?></td>
                        <td>
                            <?php 
                                echo date('d/m/Y', strtotime($intern['date_debut'])) . ' - ' . 
                                     date('d/m/Y', strtotime($intern['date_fin']));
                            ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $intern['status'] === 'en_cours' ? 'primary' : 
                                    ($intern['status'] === 'termine' ? 'success' : 'danger'); 
                            ?>">
                                <?php echo ucfirst($intern['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="index.php?action=viewIntern&id=<?php echo $intern['intern_id']; ?>" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="index.php?action=updateIntern&id=<?php echo $intern['intern_id']; ?>" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Initialize Charts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status Distribution Chart
    const statusData = <?php echo json_encode($stats['by_status']); ?>;
    new Chart(document.getElementById('statusDistribution'), {
        type: 'pie',
        data: {
            labels: statusData.map(item => item.status === 'en_cours' ? 'En cours' : 
                                         (item.status === 'termine' ? 'Terminé' : 'Abandonné')),
            datasets: [{
                data: statusData.map(item => item.count),
                backgroundColor: ['#0d6efd', '#198754', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: { 
                legend: { 
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10
                    }
                }
            }
        }
    });

    // Educational Institution Distribution Chart
    const etablissementData = <?php echo json_encode(array_slice($stats['by_etablissement'], 0, 10)); ?>;
    new Chart(document.getElementById('etablissementDistribution'), {
        type: 'bar',
        data: {
            labels: etablissementData.map(item => item.etablissement_education),
            datasets: [{
                label: 'Nombre de stagiaires',
                data: etablissementData.map(item => item.count),
                backgroundColor: '#ffc107'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: { 
                legend: { 
                    display: false 
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });

    // Service Distribution Chart
    const serviceData = <?php echo json_encode($stats['by_service']); ?>;
    new Chart(document.getElementById('serviceDistribution'), {
        type: 'bar',
        data: {
            labels: serviceData.map(item => item.service_etablissement),
            datasets: [{
                label: 'Nombre de stagiaires',
                data: serviceData.map(item => item.count),
                backgroundColor: '#0dcaf0'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: { 
                legend: { 
                    display: false 
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
});
</script>
