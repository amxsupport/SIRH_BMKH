<?php
// Get interns from GLOBALS if not available directly
if (!isset($interns) && isset($GLOBALS['interns'])) {
    $interns = $GLOBALS['interns'];
}
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Liste des Stagiaires</h4>
        <div>
            <button class="btn btn-success" onclick="exportInternList()">
                <i class="bi bi-file-earmark-excel"></i> Exporter
            </button>
            <a href="index.php?action=createIntern" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Nouveau
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search Form -->
        <form method="GET" action="index.php" class="mb-4">
            <input type="hidden" name="action" value="interns">
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
                    <select name="status" class="form-select">
                        <option value="">Statut</option>
                        <option value="en_cours" <?php echo (isset($_GET['status']) && $_GET['status'] === 'en_cours') ? 'selected' : ''; ?>>En cours</option>
                        <option value="termine" <?php echo (isset($_GET['status']) && $_GET['status'] === 'termine') ? 'selected' : ''; ?>>Terminé</option>
                        <option value="abandonne" <?php echo (isset($_GET['status']) && $_GET['status'] === 'abandonne') ? 'selected' : ''; ?>>Abandonné</option>
                    </select>
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
                        <th>CIN</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Établissement</th>
                        <th>Service</th>
                        <th>Période</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($interns)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Aucun stagiaire trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($interns as $intern): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($intern['cin']); ?></td>
                        <td><?php echo htmlspecialchars($intern['nom']); ?></td>
                        <td><?php echo htmlspecialchars($intern['prenom']); ?></td>
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
                                <!-- View Button -->
                                <a href="index.php?action=viewIntern&id=<?php echo $intern['intern_id']; ?>" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                <!-- Edit Button -->
                                <a href="index.php?action=updateIntern&id=<?php echo $intern['intern_id']; ?>" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                <!-- Authorization Button -->
                                <a href="index.php?action=internAuthorization&id=<?php echo $intern['intern_id']; ?>" 
                                   class="btn btn-sm btn-primary" title="Autorisation de Stage">
                                    <i class="bi bi-file-check"></i>
                                </a>

                                <!-- Certificate Button -->
                                <a href="index.php?action=internCertificate&id=<?php echo $intern['intern_id']; ?>" 
                                   class="btn btn-sm btn-success" title="Certificat de Stage">
                                    <i class="bi bi-award"></i>
                                </a>

                                <?php if ($intern['status'] === 'termine'): ?>
                                <!-- Attestation Button -->
                                <a href="index.php?action=internAttestation&id=<?php echo $intern['intern_id']; ?>" 
                                   class="btn btn-sm btn-secondary" title="Attestation">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                                <?php endif; ?>

                                <!-- Delete Button -->
                                <button onclick="confirmInternDelete(<?php echo $intern['intern_id']; ?>, '<?php echo htmlspecialchars($intern['nom'] . ' ' . $intern['prenom']); ?>')" 
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

<script>
function confirmInternDelete(id, name) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le stagiaire ${name} ?`)) {
        window.location.href = `index.php?action=deleteIntern&id=${id}`;
    }
}

function exportInternList() {
    window.location.href = 'index.php?action=exportInternList&format=xlsx';
}
</script>
