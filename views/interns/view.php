<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Détails du Stagiaire</h4>
        <div class="action-buttons">
            <?php if ($intern->status === 'termine'): ?>
            <a href="index.php?action=internAttestation&id=<?php echo $intern->intern_id; ?>" class="btn btn-success">
                <i class="bi bi-file-pdf"></i> Télécharger l'Attestation de Stage (PDF)
            </a>
            <?php endif; ?>
            <a href="index.php?action=updateIntern&id=<?php echo $intern->intern_id; ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <button onclick="confirmInternDelete(<?php echo $intern->intern_id; ?>, '<?php echo htmlspecialchars($intern->nom . ' ' . $intern->prenom); ?>')" 
                    class="btn btn-danger">
                <i class="bi bi-trash"></i> Supprimer
            </button>
            <a href="index.php?action=interns" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Informations Personnelles -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations Personnelles</h5>
            </div>
            <div class="card-body">
                <dl class="row intern-details">
                    <dt class="col-sm-3">CIN</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->cin); ?></dd>

                    <dt class="col-sm-3">Nom</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->nom); ?></dd>

                    <dt class="col-sm-3">Prénom</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->prenom); ?></dd>

                    <dt class="col-sm-3">Sexe</dt>
                    <dd class="col-sm-3"><?php echo $intern->sex === 'M' ? 'Masculin' : 'Féminin'; ?></dd>

                    <dt class="col-sm-3">Date de Naissance</dt>
                    <dd class="col-sm-3"><?php echo date('d/m/Y', strtotime($intern->date_naissance)); ?></dd>

                    <dt class="col-sm-3">Téléphone</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->phone ?: 'Non renseigné'); ?></dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->email ?: 'Non renseigné'); ?></dd>

                    <dt class="col-sm-3">Adresse</dt>
                    <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($intern->address ?: 'Non renseignée')); ?></dd>
                </dl>
            </div>
        </div>

        <!-- Informations sur la Formation -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations sur la Formation</h5>
            </div>
            <div class="card-body">
                <dl class="row intern-details">
                    <dt class="col-sm-3">Établissement</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->etablissement_education); ?></dd>

                    <dt class="col-sm-3">Niveau d'Éducation</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->niveau_education); ?></dd>

                    <dt class="col-sm-3">Spécialité</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->specialite); ?></dd>
                </dl>
            </div>
        </div>

        <!-- Informations sur le Stage -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informations sur le Stage</h5>
            </div>
            <div class="card-body">
                <dl class="row intern-details">
                    <dt class="col-sm-3">Date de Début</dt>
                    <dd class="col-sm-3"><?php echo date('d/m/Y', strtotime($intern->date_debut)); ?></dd>

                    <dt class="col-sm-3">Date de Fin</dt>
                    <dd class="col-sm-3"><?php echo date('d/m/Y', strtotime($intern->date_fin)); ?></dd>

                    <?php
                    $debut = new DateTime($intern->date_debut);
                    $fin = new DateTime($intern->date_fin);
                    $duree = $debut->diff($fin);
                    ?>
                    <dt class="col-sm-3">Durée</dt>
                    <dd class="col-sm-3">
                        <?php 
                        if ($duree->m > 0) {
                            echo $duree->m . ' mois et ' . $duree->d . ' jours';
                        } else {
                            echo $duree->d . ' jours';
                        }
                        ?>
                    </dd>

                    <dt class="col-sm-3">Province</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->province_etablissement); ?></dd>

                    <dt class="col-sm-3">Établissement d'Accueil</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->nom_etablissement); ?></dd>

                    <dt class="col-sm-3">Service</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($intern->service_etablissement); ?></dd>

                    <dt class="col-sm-3">Superviseur</dt>
                    <dd class="col-sm-3">
                        <?php 
                        echo isset($intern->superviseur_nom) ? 
                             htmlspecialchars($intern->superviseur_nom . ' ' . $intern->superviseur_prenom) : 
                             'Non assigné';
                        ?>
                    </dd>

                    <dt class="col-sm-3">Statut</dt>
                    <dd class="col-sm-3">
                        <span class="badge bg-<?php 
                            echo $intern->status === 'en_cours' ? 'primary' : 
                                ($intern->status === 'termine' ? 'success' : 'danger'); 
                        ?>">
                            <?php echo ucfirst($intern->status); ?>
                        </span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
function confirmInternDelete(id, name) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le stagiaire ${name} ?`)) {
        window.location.href = `index.php?action=deleteIntern&id=${id}`;
    }
}
</script>
