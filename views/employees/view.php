<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Détails de l'Employé</h4>
        <div class="action-buttons">
            <a href="index.php?action=attestation&id=<?php echo $employee->employee_id; ?>" class="btn btn-success">
                <i class="bi bi-file-pdf"></i> Télécharger l'Attestation de Travail (PDF)
            </a>
            <a href="index.php?action=update&id=<?php echo $employee->employee_id; ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <button onclick="confirmDelete(<?php echo $employee->employee_id; ?>, '<?php echo htmlspecialchars($employee->nom . ' ' . $employee->prenom); ?>')" 
                    class="btn btn-danger">
                <i class="bi bi-trash"></i> Supprimer
            </button>
            <a href="index.php?action=index" class="btn btn-secondary">
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
                <dl class="row employee-details">
                    <dt class="col-sm-3">PPR</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->ppr); ?></dd>
                    
                    <dt class="col-sm-3">CIN</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->cin); ?></dd>

                    <dt class="col-sm-3">Nom</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->nom); ?></dd>
                    
                    <dt class="col-sm-3">Prénom</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->prenom); ?></dd>

                    <dt class="col-sm-3">Sexe</dt>
                    <dd class="col-sm-3"><?php echo $employee->sex === 'M' ? 'Masculin' : 'Féminin'; ?></dd>

                    <dt class="col-sm-3">Date de Naissance</dt>
                    <dd class="col-sm-3"><?php echo date('d/m/Y', strtotime($employee->date_naissance)); ?></dd>

                    <dt class="col-sm-3">Situation Familiale</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->situation_familiale); ?></dd>

                    <dt class="col-sm-3">Téléphone</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->phone ?: 'Non renseigné'); ?></dd>

                    <dt class="col-sm-3">Adresse</dt>
                    <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($employee->address ?: 'Non renseignée')); ?></dd>
                </dl>
            </div>
        </div>

        <!-- Informations Professionnelles -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations Professionnelles</h5>
            </div>
            <div class="card-body">
                <dl class="row employee-details">
                    <dt class="col-sm-3">Corps</dt>
                    <dd class="col-sm-3"><?php echo ucfirst(htmlspecialchars($employee->corps)); ?></dd>

                    <dt class="col-sm-3">Grade</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->grade); ?></dd>

                    <dt class="col-sm-3">Spécialité</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->specialite ?: 'Non renseignée'); ?></dd>

                    <dt class="col-sm-3">Date de Recrutement</dt>
                    <dd class="col-sm-3"><?php echo date('d/m/Y', strtotime($employee->date_recrutement)); ?></dd>

                    <dt class="col-sm-3">Date de Prise de Service</dt>
                    <dd class="col-sm-3"><?php echo date('d/m/Y', strtotime($employee->date_prise_service)); ?></dd>

                    <?php
                    $today = new DateTime();
                    $recruitment = new DateTime($employee->date_recrutement);
                    $interval = $recruitment->diff($today);
                    ?>
                    <dt class="col-sm-3">Ancienneté</dt>
                    <dd class="col-sm-9">
                        <?php echo $interval->y . ' ans et ' . $interval->m . ' mois'; ?>
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Informations sur l'Établissement -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informations sur l'Établissement</h5>
            </div>
            <div class="card-body">
                <dl class="row employee-details">
                    <dt class="col-sm-3">Province</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->province_etablissement); ?></dd>

                    <dt class="col-sm-3">Milieu</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->milieu_etablissement); ?></dd>

                    <dt class="col-sm-3">Catégorie</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->categorie_etablissement); ?></dd>

                    <dt class="col-sm-3">Nom de l'Établissement</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->nom_etablissement); ?></dd>

                    <dt class="col-sm-3">Service</dt>
                    <dd class="col-sm-3"><?php echo htmlspecialchars($employee->service_etablissement ?: 'Non renseigné'); ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
