<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Modifier le Stagiaire</h4>
    </div>
    <div class="card-body">
        <form id="internForm" method="POST" action="index.php?action=updateIntern&id=<?php echo $intern->intern_id; ?>" class="needs-validation" novalidate>
            <!-- Informations Personnelles -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cin" class="required">CIN</label>
                                <input type="text" class="form-control" id="cin" name="cin" value="<?php echo htmlspecialchars($intern->cin); ?>" required>
                                <div class="invalid-feedback">Le CIN est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sex" class="required">Sexe</label>
                                <select class="form-control" id="sex" name="sex" required>
                                    <option value="">Choisir...</option>
                                    <option value="M" <?php echo $intern->sex === 'M' ? 'selected' : ''; ?>>Masculin</option>
                                    <option value="F" <?php echo $intern->sex === 'F' ? 'selected' : ''; ?>>Féminin</option>
                                </select>
                                <div class="invalid-feedback">Le sexe est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_naissance" class="required">Date de Naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?php echo $intern->date_naissance; ?>" required>
                                <div class="invalid-feedback">La date de naissance est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nom" class="required">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($intern->nom); ?>" required>
                                <div class="invalid-feedback">Le nom est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prenom" class="required">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($intern->prenom); ?>" required>
                                <div class="invalid-feedback">Le prénom est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($intern->phone); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($intern->email); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address">Adresse</label>
                                <textarea class="form-control" id="address" name="address" rows="1"><?php echo htmlspecialchars($intern->address); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations sur la Formation -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations sur la Formation</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="etablissement_education" class="required">Établissement d'Éducation</label>
                                <input type="text" class="form-control" id="etablissement_education" name="etablissement_education" value="<?php echo htmlspecialchars($intern->etablissement_education); ?>" required>
                                <div class="invalid-feedback">L'établissement d'éducation est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="niveau_education" class="required">Niveau d'Éducation</label>
                                <input type="text" class="form-control" id="niveau_education" name="niveau_education" value="<?php echo htmlspecialchars($intern->niveau_education); ?>" required>
                                <div class="invalid-feedback">Le niveau d'éducation est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="specialite" class="required">Spécialité</label>
                                <input type="text" class="form-control" id="specialite" name="specialite" value="<?php echo htmlspecialchars($intern->specialite); ?>" required>
                                <div class="invalid-feedback">La spécialité est obligatoire</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations sur le Stage -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations sur le Stage</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_debut" class="required">Date de Début</label>
                                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?php echo $intern->date_debut; ?>" required>
                                <div class="invalid-feedback">La date de début est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_fin" class="required">Date de Fin</label>
                                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?php echo $intern->date_fin; ?>" required>
                                <div class="invalid-feedback">La date de fin est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="province_etablissement" class="required">Province</label>
                                <input type="text" class="form-control" id="province_etablissement" name="province_etablissement" value="<?php echo htmlspecialchars($intern->province_etablissement); ?>" required>
                                <div class="invalid-feedback">La province est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nom_etablissement" class="required">Établissement d'Accueil</label>
                                <input type="text" class="form-control" id="nom_etablissement" name="nom_etablissement" value="<?php echo htmlspecialchars($intern->nom_etablissement); ?>" required>
                                <div class="invalid-feedback">L'établissement d'accueil est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="service_etablissement" class="required">Service</label>
                                <input type="text" class="form-control" id="service_etablissement" name="service_etablissement" value="<?php echo htmlspecialchars($intern->service_etablissement); ?>" required>
                                <div class="invalid-feedback">Le service est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="superviseur_id">Superviseur</label>
                                <select class="form-select" id="superviseur_id" name="superviseur_id">
                                    <option value="">Sélectionner un superviseur...</option>
                                    <?php
                                    if (isset($GLOBALS['superviseurs'])) {
                                        foreach ($GLOBALS['superviseurs'] as $superviseur) {
                                            $selected = ($superviseur['employee_id'] == $intern->superviseur_id) ? 'selected' : '';
                                            echo '<option value="' . $superviseur['employee_id'] . '" ' . $selected . '>' . 
                                                 htmlspecialchars($superviseur['nom'] . ' ' . $superviseur['prenom']) . 
                                                 '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="required">Statut</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="en_cours" <?php echo $intern->status === 'en_cours' ? 'selected' : ''; ?>>En cours</option>
                                    <option value="termine" <?php echo $intern->status === 'termine' ? 'selected' : ''; ?>>Terminé</option>
                                    <option value="abandonne" <?php echo $intern->status === 'abandonne' ? 'selected' : ''; ?>>Abandonné</option>
                                </select>
                                <div class="invalid-feedback">Le statut est obligatoire</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="index.php?action=viewIntern&id=<?php echo $intern->intern_id; ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    var form = document.getElementById('internForm');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Date validation
    var dateDebut = document.getElementById('date_debut');
    var dateFin = document.getElementById('date_fin');

    function validateDates() {
        if (dateDebut.value && dateFin.value) {
            if (dateDebut.value > dateFin.value) {
                dateFin.setCustomValidity('La date de fin doit être postérieure à la date de début');
            } else {
                dateFin.setCustomValidity('');
            }
        }
    }

    dateDebut.addEventListener('change', validateDates);
    dateFin.addEventListener('change', validateDates);
});
</script>
