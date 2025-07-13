<?php require_once 'views/layout.php'; ?>

<!-- Main Content -->
<main class="app-main">
    <div class="page-header">
        <div class="page-title">
            <h1>Modifier le Congé</h1>
        </div>
    </div>

    <div class="page-content">
    
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-pencil-square"></i>
            Modification de la Demande de Congé
        </div>
        <div class="card-body">
            <form action="index.php?controller=leave&action=edit&id=<?= $leave['id'] ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Employé</label>
                    <input type="text" class="form-control" 
                           value="<?= htmlspecialchars($leave['nom'] . ' ' . $leave['prenom']) ?>" 
                           readonly>
                </div>

                <div class="mb-3">
                    <label for="type_id" class="form-label">Type de Congé</label>
                    <select name="type_id" id="type_id" class="form-select" required>
                        <option value="">Sélectionner un type de congé</option>
                        <?php foreach ($leaveTypes as $type): ?>
                            <option value="<?= $type['type_id'] ?>" 
                                    data-requires-document="<?= $type['requires_document'] ?>" 
                                    data-default-days="<?= $type['default_days'] ?>"
                                    data-description="<?= htmlspecialchars($type['description']) ?>"
                                    <?= $leave['type_id'] == $type['type_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['name']) ?>
                                <?php if ($type['default_days']): ?>
                                    (<?= $type['default_days'] ?> jours)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted type-description"></small>
                    <div class="document-info mt-2 d-none alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        Ce type de congé nécessite un document justificatif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?= htmlspecialchars($leave['start_date']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?= htmlspecialchars($leave['end_date']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="reason" class="form-label">Motif</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3" required><?= htmlspecialchars($leave['reason']) ?></textarea>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check"></i> Mettre à jour
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
    const typeSelect = document.getElementById('type_id');
    const documentInfo = document.querySelector('.document-info');
    const typeDescription = document.querySelector('.type-description');

    // Initial state setup
    const selectedOption = typeSelect.options[typeSelect.selectedIndex];
    if (selectedOption) {
        const requiresDocument = selectedOption.dataset.requiresDocument === '1';
        const description = selectedOption.dataset.description;
        documentInfo.classList.toggle('d-none', !requiresDocument);
        typeDescription.textContent = description || '';
    }

    // Handle leave type selection
    typeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const requiresDocument = selectedOption.dataset.requiresDocument === '1';
        const defaultDays = selectedOption.dataset.defaultDays;
        const description = selectedOption.dataset.description;

        documentInfo.classList.toggle('d-none', !requiresDocument);
        typeDescription.textContent = description || '';

        if (defaultDays && startDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(start);
            end.setDate(start.getDate() + parseInt(defaultDays) - 1);
            endDate.value = end.toISOString().split('T')[0];
        }
    });

    // Handle start date changes
    startDate.addEventListener('change', function() {
        endDate.min = startDate.value;
        if (endDate.value && endDate.value < startDate.value) {
            endDate.value = startDate.value;
        }

        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const defaultDays = selectedOption.dataset.defaultDays;
        if (defaultDays) {
            const start = new Date(startDate.value);
            const end = new Date(start);
            end.setDate(start.getDate() + parseInt(defaultDays) - 1);
            endDate.value = end.toISOString().split('T')[0];
        }
    });
});
</script>
