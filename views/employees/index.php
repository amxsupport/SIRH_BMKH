<?php
// Debug logging
error_log("View accessing employees variable: " . (isset($employees) ? "yes" : "no"));
error_log("View accessing GLOBALS employees: " . (isset($GLOBALS['employees']) ? "yes" : "no"));

// Get employees from GLOBALS if not available directly
if (!isset($employees) && isset($GLOBALS['employees'])) {
    $employees = $GLOBALS['employees'];
}
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Liste des Employés</h4>
        <div>
            <button class="btn btn-success" onclick="exportToExcel()">
                <i class="bi bi-file-earmark-excel"></i> Exporter
            </button>
            <a href="index.php?action=create" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Nouveau
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="GET" action="index.php" class="mb-4">
            <input type="hidden" name="action" value="index">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="nom" class="form-control" placeholder="Nom" 
                           value="<?php echo isset($_GET['nom']) ? htmlspecialchars($_GET['nom']) : ''; ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="prenom" class="form-control" placeholder="Prénom"
                           value="<?php echo isset($_GET['prenom']) ? htmlspecialchars($_GET['prenom']) : ''; ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="cin" class="form-control" placeholder="CIN"
                           value="<?php echo isset($_GET['cin']) ? htmlspecialchars($_GET['cin']) : ''; ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" name="ppr" class="form-control" placeholder="PPR"
                           value="<?php echo isset($_GET['ppr']) ? htmlspecialchars($_GET['ppr']) : ''; ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Rechercher
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>PPR</th>
                        <th>CIN</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Corps</th>
                        <th>Établissement</th>
                        <th>Province</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($employees)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Aucun employé trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($employees as $emp): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($emp['ppr']); ?></td>
                        <td><?php echo htmlspecialchars($emp['cin']); ?></td>
                        <td><?php echo htmlspecialchars($emp['nom']); ?></td>
                        <td><?php echo htmlspecialchars($emp['prenom']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($emp['corps'])); ?></td>
                        <td><?php echo htmlspecialchars($emp['nom_etablissement']); ?></td>
                        <td><?php echo htmlspecialchars($emp['province_etablissement']); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="index.php?action=view&id=<?php echo $emp['employee_id']; ?>" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="index.php?action=update&id=<?php echo $emp['employee_id']; ?>" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="confirmDelete(<?php echo $emp['employee_id']; ?>, '<?php echo htmlspecialchars($emp['nom'] . ' ' . $emp['prenom']); ?>')" 
                                        class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
