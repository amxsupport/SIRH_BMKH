<?php
// Get stats from GLOBALS if not available directly
if (!isset($stats) && isset($GLOBALS['stats'])) {
    $stats = $GLOBALS['stats'];
}

// Initialize default values
if (!isset($stats)) {
    $stats = [
        'total_employees' => 0,
        'by_corps' => [],
        'by_province' => [],
        'by_milieu' => []
    ];
    error_log("Warning: Stats not available in statistics view");
}
?>
<div class="row">
    <!-- Total Employees Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card mb-4">
            <div class="card-body text-center">
                <h5 class="card-title">Total des Employés</h5>
                <p class="display-4"><?php echo isset($stats['total_employees']) ? $stats['total_employees'] : '0'; ?></p>
            </div>
        </div>
    </div>

    <!-- By Corps Distribution -->
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card mb-4">
            <div class="card-body">
                <h5 class="card-title text-center mb-3">Distribution par Corps</h5>
                <ul class="list-group">
                    <?php if (!empty($stats['by_corps'])): ?>
                        <?php foreach ($stats['by_corps'] as $corps): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo ucfirst($corps['corps']); ?>
                            <span class="badge bg-primary rounded-pill"><?php echo $corps['count']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item">Aucune donnée disponible</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- By Milieu Distribution -->
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card mb-4">
            <div class="card-body">
                <h5 class="card-title text-center mb-3">Distribution par Milieu</h5>
                <ul class="list-group">
                    <?php if (!empty($stats['by_milieu'])): ?>
                        <?php foreach ($stats['by_milieu'] as $milieu): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo $milieu['milieu_etablissement']; ?>
                            <span class="badge bg-primary rounded-pill"><?php echo $milieu['count']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item">Aucune donnée disponible</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- By Province Distribution -->
    <div class="col-md-6 col-lg-3">
        <div class="card stats-card mb-4">
            <div class="card-body">
                <h5 class="card-title text-center mb-3">Top 5 Provinces</h5>
                <ul class="list-group">
                    <?php 
                    if (!empty($stats['by_province'])) {
                        $provinces = array_slice($stats['by_province'], 0, 5);
                        foreach ($provinces as $province): 
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo $province['province_etablissement']; ?>
                            <span class="badge bg-primary rounded-pill"><?php echo $province['count']; ?></span>
                        </li>
                        <?php endforeach; 
                    } else { ?>
                        <li class="list-group-item">Aucune donnée disponible</li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <!-- Corps Distribution Chart -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="corpsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Province Distribution Chart -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="provinceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Milieu Distribution Chart -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="milieuChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Statistics Data -->
<div id="statisticsData" data-stats='<?php echo json_encode($stats); ?>' style="display: none;"></div>
