
<div class="row">
    <!-- Total Employees Card -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Total des Employés</h6>
                        <h2 class="mt-2 mb-0"><?php echo $stats['total_employees']; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribution by Corps -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Corps Médical</h6>
                        <?php
                        $medical_count = 0;
                        foreach ($stats['by_corps'] as $corps) {
                            if ($corps['corps'] === 'medical') {
                                $medical_count = $corps['count'];
                                break;
                            }
                        }
                        ?>
                        <h2 class="mt-2 mb-0"><?php echo $medical_count; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Retirement Notifications -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Notifications Retraite</h6>
                        <h2 class="mt-2 mb-0"><?php echo $pendingNotifications; ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-bell"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Provinces Count -->
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">Provinces</h6>
                        <h2 class="mt-2 mb-0"><?php echo count($stats['by_province']); ?></h2>
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Sections -->

<div class="row">
    <!-- STATISTIQUE INFRASTRUCTURES PUBLIQUES -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">STATISTIQUE INFRASTRUCTURES PUBLIQUES</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Milieu</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['infrastructure'] as $infra): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($infra['categorie_etablissement']); ?></td>
                                <td><?php echo htmlspecialchars($infra['milieu_etablissement']); ?></td>
                                <td><?php echo $infra['count']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div style="height: 300px;">
                    <canvas id="infrastructureChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTIQUE RESSOURCES HUMAINES -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">STATISTIQUE RESSOURCES HUMAINES</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="corpsDistribution"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Corps</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['by_corps'] as $corps): ?>
                            <tr>
                                <td><?php echo ucfirst(htmlspecialchars($corps['corps'])); ?></td>
                                <td><?php echo $corps['count']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTIQUE RÉPARTITION PAR GENRE -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">STATISTIQUE RÉPARTITION PAR GENRE</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="genderDistribution"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Genre</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['gender'] as $gender): ?>
                            <tr>
                                <td><?php echo $gender['sex'] === 'M' ? 'Masculin' : 'Féminin'; ?></td>
                                <td><?php echo $gender['count']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- STATISTIQUE SITUATION FAMILIALE -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">STATISTIQUE SITUATION FAMILIALE</h5>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="familyStatusDistribution"></canvas>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Situation</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['family_status'] as $status): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($status['situation_familiale']); ?></td>
                                <td><?php echo $status['count']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Age Pyramid Section -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">PYRAMIDE DES ÂGES</h5>
                <div class="filters">
                    <select id="pyramidFilter" class="form-select form-select-sm bg-light">
                        <option value="all">Tous les employés</option>
                        <?php foreach ($stats['by_corps'] as $corps): ?>
                            <option value="<?php echo htmlspecialchars($corps['corps']); ?>">
                                <?php echo ucfirst(htmlspecialchars($corps['corps'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 400px;">
                    <canvas id="agePyramid"></canvas>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Groupe d'âge</th>
                                <th>Hommes</th>
                                <th>Femmes</th>
                                <th>Total</th>
                                <th>Pourcentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalEmployees = $stats['total_employees'];
                            foreach ($stats['age_pyramid'] as $group => $data): 
                                $total = $data['total'];
                                $percentage = round(($total / $totalEmployees) * 100, 1);
                            ?>
                            <tr>
                                <td><?php echo $group; ?></td>
                                <td><?php echo $data['M'] ?? 0; ?></td>
                                <td><?php echo $data['F'] ?? 0; ?></td>
                                <td><?php echo $total; ?></td>
                                <td><?php echo $percentage; ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize Charts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Age Pyramid Chart
    const ageData = <?php echo json_encode($stats['age_pyramid']); ?>;
    const ageGroups = Object.keys(ageData).sort((a, b) => {
        // Custom sorting for age groups
        const ageOrder = {
            '< 20': 1, '21-25': 2, '26-30': 3, '31-35': 4,
            '36-40': 5, '41-55': 6, '56-60': 7, '61-63': 8
        };
        return ageOrder[a] - ageOrder[b];
    });
    const maleData = ageGroups.map(group => -(ageData[group]['M'] || 0));
    const femaleData = ageGroups.map(group => ageData[group]['F'] || 0);

    // Store chart instance for later updates
    const agePyramidChart = new Chart(document.getElementById('agePyramid'), {
        type: 'bar',
        data: {
            labels: ageGroups,
            datasets: [
                {
                    label: 'Hommes',
                    data: maleData,
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Femmes',
                    data: femaleData,
                    backgroundColor: 'rgba(236, 72, 153, 0.8)',
                    borderColor: 'rgba(236, 72, 153, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribution par âge et sexe',
                    font: {
                        size: 16,
                        weight: '600'
                    },
                    padding: 20
                },
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = Math.abs(context.parsed.x);
                            return `${context.dataset.label}: ${value}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    stacked: false,
                    ticks: {
                        callback: function(value) {
                            return Math.abs(value);
                        }
                    },
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    }
                },
                y: {
                    stacked: false,
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Filter functionality
    // Function to update the age pyramid table
    function updateAgeTable(data) {
        const tbody = document.querySelector('#agePyramid').closest('.card').querySelector('tbody');
        const totalEmployees = Object.values(data).reduce((sum, group) => sum + group.total, 0);
        
        tbody.innerHTML = Object.entries(data).map(([group, counts]) => {
            const percentage = ((counts.total / totalEmployees) * 100).toFixed(1);
            return `
                <tr>
                    <td>${group}</td>
                    <td>${counts.M || 0}</td>
                    <td>${counts.F || 0}</td>
                    <td>${counts.total}</td>
                    <td>${percentage}%</td>
                </tr>
            `;
        }).join('');
    }

    // Filter event handler
    document.getElementById('pyramidFilter').addEventListener('change', function(e) {
        const selectedCorps = e.target.value;
        fetch(`index.php?action=getAgeDistribution&corps=${selectedCorps}`)
            .then(response => response.json())
            .then(data => {
                const groups = Object.keys(data);
                // Update chart data
                const newMaleData = groups.map(group => -(data[group]['M'] || 0));
                const newFemaleData = groups.map(group => data[group]['F'] || 0);
                
                agePyramidChart.data.datasets[0].data = newMaleData;
                agePyramidChart.data.datasets[1].data = newFemaleData;
                agePyramidChart.update();

                // Update table data
                updateAgeTable(data);
            })
            .catch(error => {
                console.error('Error fetching age distribution:', error);
                alert('Erreur lors de la mise à jour des données');
            });
    });
    // Common Chart Options
    Chart.defaults.color = '#64748b';
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    
    // Infrastructure Chart
    const infrastructureData = <?php echo json_encode($stats['infrastructure']); ?>;
    new Chart(document.getElementById('infrastructureChart'), {
        type: 'bar',
        data: {
            labels: infrastructureData.map(item => `${item.categorie_etablissement} - ${item.milieu_etablissement}`),
            datasets: [{
                label: 'Nombre d\'établissements',
                data: infrastructureData.map(item => item.count),
                backgroundColor: '#6366f1',
                borderRadius: 8,
                hoverBackgroundColor: '#4f46e5',
                barThickness: 20,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    bodyFont: {
                        size: 13
                    },
                    bodySpacing: 4,
                    boxPadding: 4,
                    callbacks: {
                        label: (context) => ` ${context.parsed.y} établissements`
                    }
                }
            },
            layout: {
                padding: {
                    left: 10,
                    right: 10,
                    top: 0,
                    bottom: 0
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Corps Distribution Chart
    const corpsData = <?php echo json_encode($stats['by_corps']); ?>;
    new Chart(document.getElementById('corpsDistribution'), {
        type: 'doughnut',
        data: {
            labels: corpsData.map(item => item.corps),
            datasets: [{
                data: corpsData.map(item => item.count),
                backgroundColor: [
                    'rgba(99, 102, 241, 0.9)',
                    'rgba(34, 197, 94, 0.9)',
                    'rgba(234, 179, 8, 0.9)'
                ],
                hoverBackgroundColor: [
                    'rgba(99, 102, 241, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(234, 179, 8, 1)'
                ],
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: (context) => ` ${context.parsed}: ${context.raw} employés`
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Gender Distribution Chart
    const genderData = <?php echo json_encode($stats['gender']); ?>;
    new Chart(document.getElementById('genderDistribution'), {
        type: 'doughnut',
        data: {
            labels: genderData.map(item => item.sex === 'M' ? 'Masculin' : 'Féminin'),
            datasets: [{
                data: genderData.map(item => item.count),
                backgroundColor: [
                    'rgba(99, 102, 241, 0.9)',
                    'rgba(236, 72, 153, 0.9)'
                ],
                hoverBackgroundColor: [
                    'rgba(99, 102, 241, 1)',
                    'rgba(236, 72, 153, 1)'
                ],
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: (context) => ` ${context.parsed}: ${context.raw} employés`
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Family Status Distribution Chart
    const familyData = <?php echo json_encode($stats['family_status']); ?>;
    new Chart(document.getElementById('familyStatusDistribution'), {
        type: 'doughnut',
        data: {
            labels: familyData.map(item => item.situation_familiale),
            datasets: [{
                data: familyData.map(item => item.count),
                backgroundColor: [
                    'rgba(99, 102, 241, 0.9)',
                    'rgba(34, 197, 94, 0.9)',
                    'rgba(234, 179, 8, 0.9)',
                    'rgba(239, 68, 68, 0.9)'
                ],
                hoverBackgroundColor: [
                    'rgba(99, 102, 241, 1)',
                    'rgba(34, 197, 94, 1)',
                    'rgba(234, 179, 8, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: (context) => ` ${context.parsed}: ${context.raw} employés`
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        }
    });
});
</script>
