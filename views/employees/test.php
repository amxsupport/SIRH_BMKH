<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Employés</h5>
                <div class="d-flex align-items-center">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="ms-3">
                        <h2 class="mb-0">245</h2>
                        <p class="text-secondary mb-0">Employés actifs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Test -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Test des Formulaires</h5>
            </div>
            <div class="card-body">
                <form class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Champ requis</label>
                                <input type="text" class="form-control" required>
                                <div class="invalid-feedback">Ce champ est requis</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Sélection</label>
                                <select class="form-select">
                                    <option>Option 1</option>
                                    <option>Option 2</option>
                                    <option>Option 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Tester
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Test -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Test des Tableaux</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Test User</td>
                                <td><span class="badge bg-success">Actif</span></td>
                                <td>
                                    <button class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Test -->
    <div class="col-12 mt-4">
        <div class="alert alert-info">
            <div class="alert-icon">
                <i class="bi bi-info-circle"></i>
            </div>
            <div class="alert-content">
                Ceci est un message d'information pour tester le style des alertes.
            </div>
        </div>
    </div>

    <!-- Chart Test -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Test des Graphiques</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-container">
                            <canvas id="testChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('testChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Médical', 'Paramédical', 'Administratif'],
            datasets: [{
                data: [30, 45, 25],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b']
            }]
        },
        options: generateChartOptions({
            title: 'Test de Graphique'
        })
    });
});
</script>
