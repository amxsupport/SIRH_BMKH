<?php require_once 'views/layout.php'; ?>

<!-- Main Content -->
<main class="app-main">
    <div class="page-header">
        <div class="page-title">
            <h1>Nouveau Congé</h1>
        </div>
    </div>

    <div class="page-content">
    
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-calendar-plus"></i>
                Demande de Congé
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error'] ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
            
                <form action="index.php?controller=leave&action=create" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="employee_id" class="form-label">Employé</label>
                    <select name="employee_id" id="employee_id" class="form-select" required 
                            value="<?= $_POST['employee_id'] ?? '' ?>">
                        <option value="">Sélectionner un employé</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['employee_id'] ?>">
                                <?= htmlspecialchars($employee['nom'] . ' ' . $employee['prenom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="type_id" class="form-label">Type de Congé</label>
                    <select name="type_id" id="type_id" class="form-select" required>
                        <option value="">Sélectionner un type de congé</option>
                        <?php foreach ($leaveTypes as $type): ?>
                            <option value="<?= $type['type_id'] ?>" 
                                    data-requires-document="<?= $type['requires_document'] ?>" 
                                    data-default-days="<?= $type['default_days'] ?>"
                                    data-description="<?= htmlspecialchars($type['description']) ?>">
                                <?= htmlspecialchars($type['name']) ?>
                                <?php 
                                $remainingDays = isset($_GET['employee_id']) ? $leaveModel->getRemainingDays($_GET['employee_id'], $type['type_id']) : null;
                                if ($type['default_days']): ?>
                                    (<?= $type['default_days'] ?> jours
                                    <?php if ($remainingDays !== null): ?>
                                        - Reste: <?= $remainingDays ?> jours
                                    <?php endif; ?>)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted type-description"></small>
                    <div class="document-info mt-2 d-none">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Ce type de congé nécessite un document justificatif
                        </div>
                        <div class="mt-2">
                            <input type="file" class="form-control" name="document" id="document">
                            <small class="text-muted">Formats acceptés: PDF, JPG, PNG (Max: 5MB)</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required
                                   value="<?= $_POST['start_date'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="duration" class="form-label">Nombre de jours</label>
                            <input type="number" class="form-control" id="duration" name="duration" min="1" required
                                   value="<?= $_POST['duration'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required readonly
                                   value="<?= $_POST['end_date'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Motif</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3" required><?= $_POST['reason'] ?? '' ?></textarea>
                </div>

                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle"></i> Informations sur les types de congés :</h6>
                    <ul class="mb-0">
                        <li>Congé annuel : 30 jours ouvrables par an</li>
                        <li>Congé de maternité : 14 semaines (98 jours)</li>
                        <li>Congé de paternité : 3 jours</li>
                        <li>Congé exceptionnel :
                            <ul>
                                <li>Mariage : 5 jours</li>
                                <li>Décès : 3 jours</li>
                                <li>Naissance : 3 jours</li>
                            </ul>
                        </li>
                        <li>Congé de pèlerinage : 30 jours (une fois dans la carrière)</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check"></i> Soumettre
                    </button>
                    <a href="index.php?controller=leave&action=index" class="btn btn-secondary">
                        <i class="bi bi-x"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const duration = document.getElementById('duration');
    const typeSelect = document.getElementById('type_id');
    const employeeSelect = document.getElementById('employee_id');
    const documentInfo = document.querySelector('.document-info');
    const typeDescription = document.querySelector('.type-description');

    // Check if a date is a weekend
    function isWeekend(date) {
        const day = date.getDay();
        return day === 0 || day === 6; // 0 = Sunday, 6 = Saturday
    }

    // Calculate end date with weekends and holidays excluded
    function calculateEndDate() {
        if (startDate.value && duration.value) {
            const start = new Date(startDate.value);
            let end = new Date(start);
            let daysToAdd = parseInt(duration.value);
            let daysAdded = 0;
            
            while (daysAdded < daysToAdd) {
                end.setDate(end.getDate() + 1);
                if (!isWeekend(end)) {
                    daysAdded++;
                }
            }

            // Adjust if end date falls on weekend
            while (isWeekend(end)) {
                end.setDate(end.getDate() + 1);
            }

            endDate.value = end.toISOString().split('T')[0];
        }
    }

    // Show working days count between dates
    function updateDurationInfo() {
        if (startDate.value && endDate.value) {
            const startDay = new Date(startDate.value);
            const endDay = new Date(endDate.value);
            let workingDays = 0;
            
            for (let d = new Date(startDay); d <= endDay; d.setDate(d.getDate() + 1)) {
                if (!isWeekend(d)) {
                    workingDays++;
                }
            }
            
            document.getElementById('duration').value = workingDays;
        }
    }

    // Add event listeners for auto-calculation
    startDate.addEventListener('change', function() {
        const today = new Date().toISOString().split('T')[0];
        if (startDate.value < today) {
            startDate.value = today;
        }
        calculateEndDate();
    });

    duration.addEventListener('change', calculateEndDate);
    duration.addEventListener('input', calculateEndDate);
    endDate.addEventListener('change', updateDurationInfo);

    // Update remaining days when employee or type changes
    function updateRemainingDays() {
        const employeeId = employeeSelect.value;
        const typeId = typeSelect.value;
        
        if (employeeId && typeId) {
            fetch(`index.php?controller=leave&action=getRemainingDays&employee_id=${employeeId}&type_id=${typeId}`)
                .then(response => response.json())
                .then(data => {
                    const options = typeSelect.querySelectorAll('option');
                    options.forEach(option => {
                        if (option.value === typeId) {
                            const defaultDays = option.dataset.defaultDays;
                            if (defaultDays) {
                                option.textContent = `${option.textContent.split('(')[0]} (${defaultDays} jours - Reste: ${data.remaining} jours)`;
                            }
                        }
                    });
                });
        }
    }

    employeeSelect.addEventListener('change', updateRemainingDays);
    typeSelect.addEventListener('change', updateRemainingDays);

    // Handle leave type selection
    typeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const requiresDocument = selectedOption.dataset.requiresDocument === '1';
        const defaultDays = selectedOption.dataset.defaultDays;
        const description = selectedOption.dataset.description;

        // Show/hide document requirement info
        documentInfo.classList.toggle('d-none', !requiresDocument);
        
        // Update description
        typeDescription.textContent = description || '';

        // Update end date if default days is set and start date is selected
        if (defaultDays && startDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(start);
            end.setDate(start.getDate() + parseInt(defaultDays) - 1);
            endDate.value = end.toISOString().split('T')[0];
        }
    });

    // Handle start date changes
    startDate.addEventListener('change', function() {
        // Set minimum start date to today
        const today = new Date().toISOString().split('T')[0];
        if (startDate.value < today) {
            startDate.value = today;
        }
        calculateEndDate();
    });

    // Set min date to today for both fields
    const today = new Date().toISOString().split('T')[0];
    startDate.min = today;
    endDate.min = today;
});
</script>
