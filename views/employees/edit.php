<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Modifier l'Employé</h4>
    </div>
    <div class="card-body">
        <form id="employeeForm" method="POST" action="index.php?action=update&id=<?php echo $employee->employee_id; ?>" class="needs-validation" novalidate>
            <!-- Informations Personnelles -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ppr" class="required">PPR</label>
                                <input type="text" class="form-control" id="ppr" name="ppr" value="<?php echo htmlspecialchars($employee->ppr); ?>" required>
                                <div class="invalid-feedback">Le PPR est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cin" class="required">CIN</label>
                                <input type="text" class="form-control" id="cin" name="cin" value="<?php echo htmlspecialchars($employee->cin); ?>" required>
                                <div class="invalid-feedback">Le CIN est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sex" class="required">Sexe</label>
                                <select class="form-control" id="sex" name="sex" required>
                                    <option value="">Choisir...</option>
                                    <option value="M" <?php echo $employee->sex === 'M' ? 'selected' : ''; ?>>Masculin</option>
                                    <option value="F" <?php echo $employee->sex === 'F' ? 'selected' : ''; ?>>Féminin</option>
                                </select>
                                <div class="invalid-feedback">Le sexe est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nom" class="required">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($employee->nom); ?>" required>
                                <div class="invalid-feedback">Le nom est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prenom" class="required">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($employee->prenom); ?>" required>
                                <div class="invalid-feedback">Le prénom est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_naissance" class="required">Date de Naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?php echo $employee->date_naissance; ?>" required>
                                <div class="invalid-feedback">La date de naissance est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="situation_familiale" class="required">Situation Familiale</label>
                                <select class="form-control" id="situation_familiale" name="situation_familiale" required>
                                    <option value="">Choisir...</option>
                                    <?php 
                                    $situations = ['Célibataire', 'Marié(e)', 'Divorcé(e)', 'Veuf(ve)'];
                                    foreach ($situations as $situation):
                                    ?>
                                    <option value="<?php echo $situation; ?>" <?php echo $employee->situation_familiale === $situation ? 'selected' : ''; ?>>
                                        <?php echo $situation; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">La situation familiale est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($employee->phone); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address">Adresse</label>
                                <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($employee->address); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations Professionnelles -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations Professionnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="corps" class="required">Corps</label>
                                <select class="form-control" id="corps" name="corps" required>
                                    <option value="">Choisir...</option>
                                    <option value="medical" <?php echo $employee->corps === 'medical' ? 'selected' : ''; ?>>Médical</option>
                                    <option value="paramedical" <?php echo $employee->corps === 'paramedical' ? 'selected' : ''; ?>>Paramédical</option>
                                    <option value="administratif" <?php echo $employee->corps === 'administratif' ? 'selected' : ''; ?>>Administratif</option>
                                </select>
                                <div class="invalid-feedback">Le corps est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="grade" class="required">Grade</label>
                                <input type="text" class="form-control" id="grade" name="grade" value="<?php echo htmlspecialchars($employee->grade); ?>" required>
                                <div class="invalid-feedback">Le grade est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="specialite">Spécialité</label>
                                <input type="text" class="form-control" id="specialite" name="specialite" value="<?php echo htmlspecialchars($employee->specialite); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_recrutement" class="required">Date de Recrutement</label>
                                <input type="date" class="form-control" id="date_recrutement" name="date_recrutement" value="<?php echo $employee->date_recrutement; ?>" required>
                                <div class="invalid-feedback">La date de recrutement est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_prise_service" class="required">Date de Prise de Service</label>
                                <input type="date" class="form-control" id="date_prise_service" name="date_prise_service" value="<?php echo $employee->date_prise_service; ?>" required>
                                <div class="invalid-feedback">La date de prise de service est obligatoire</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations sur l'Établissement -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informations sur l'Établissement</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="province_etablissement" class="required">Province</label>
                                <input type="text" class="form-control" id="province_etablissement" name="province_etablissement" value="<?php echo htmlspecialchars($employee->province_etablissement); ?>" required>
                                <div class="invalid-feedback">La province est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="milieu_etablissement" class="required">Milieu</label>
                                <select class="form-control" id="milieu_etablissement" name="milieu_etablissement" required>
                                    <option value="">Choisir...</option>
                                    <option value="Urbain" <?php echo $employee->milieu_etablissement === 'Urbain' ? 'selected' : ''; ?>>Urbain</option>
                                    <option value="Rural" <?php echo $employee->milieu_etablissement === 'Rural' ? 'selected' : ''; ?>>Rural</option>
                                </select>
                                <div class="invalid-feedback">Le milieu est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="categorie_etablissement" class="required">Catégorie</label>
                                <input type="text" class="form-control" id="categorie_etablissement" name="categorie_etablissement" value="<?php echo htmlspecialchars($employee->categorie_etablissement); ?>" required>
                                <div class="invalid-feedback">La catégorie est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nom_etablissement" class="required">Nom de l'Établissement</label>
                                <input type="text" class="form-control" id="nom_etablissement" name="nom_etablissement" value="<?php echo htmlspecialchars($employee->nom_etablissement); ?>" required>
                                <div class="invalid-feedback">Le nom de l'établissement est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="service_etablissement">Service</label>
                                <input type="text" class="form-control" id="service_etablissement" name="service_etablissement" value="<?php echo htmlspecialchars($employee->service_etablissement); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="index.php?action=view&id=<?php echo $employee->employee_id; ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
