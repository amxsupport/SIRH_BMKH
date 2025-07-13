<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Ajouter un Employé</h4>
    </div>
    <div class="card-body">
        <form id="employeeForm" method="POST" action="index.php?action=create" class="needs-validation" novalidate>
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
                                <input type="text" class="form-control" id="ppr" name="ppr" required>
                                <div class="invalid-feedback">Le PPR est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cin" class="required">CIN</label>
                                <input type="text" class="form-control" id="cin" name="cin" required>
                                <div class="invalid-feedback">Le CIN est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sex" class="required">Sexe</label>
                                <select class="form-control" id="sex" name="sex" required>
                                    <option value="">Choisir...</option>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                                <div class="invalid-feedback">Le sexe est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nom" class="required">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                                <div class="invalid-feedback">Le nom est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prenom" class="required">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                                <div class="invalid-feedback">Le prénom est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_naissance" class="required">Date de Naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                                <div class="invalid-feedback">La date de naissance est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="situation_familiale" class="required">Situation Familiale</label>
                                <select class="form-control" id="situation_familiale" name="situation_familiale" required>
                                    <option value="">Choisir...</option>
                                    <option value="Célibataire">Célibataire</option>
                                    <option value="Marié(e)">Marié(e)</option>
                                    <option value="Divorcé(e)">Divorcé(e)</option>
                                    <option value="Veuf(ve)">Veuf(ve)</option>
                                </select>
                                <div class="invalid-feedback">La situation familiale est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="phone">Téléphone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address">Adresse</label>
                                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
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
                                    <option value="medical">Médical</option>
                                    <option value="paramedical">Paramédical</option>
                                    <option value="administratif">Administratif</option>
                                </select>
                                <div class="invalid-feedback">Le corps est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="grade" class="required">Grade</label>
                                <input type="text" class="form-control" id="grade" name="grade" required>
                                <div class="invalid-feedback">Le grade est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="specialite">Spécialité</label>
                                <input type="text" class="form-control" id="specialite" name="specialite">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_recrutement" class="required">Date de Recrutement</label>
                                <input type="date" class="form-control" id="date_recrutement" name="date_recrutement" required>
                                <div class="invalid-feedback">La date de recrutement est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_prise_service" class="required">Date de Prise de Service</label>
                                <input type="date" class="form-control" id="date_prise_service" name="date_prise_service" required>
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
                                <input type="text" class="form-control" id="province_etablissement" name="province_etablissement" required>
                                <div class="invalid-feedback">La province est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="milieu_etablissement" class="required">Milieu</label>
                                <select class="form-control" id="milieu_etablissement" name="milieu_etablissement" required>
                                    <option value="">Choisir...</option>
                                    <option value="Urbain">Urbain</option>
                                    <option value="Rural">Rural</option>
                                </select>
                                <div class="invalid-feedback">Le milieu est obligatoire</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="categorie_etablissement" class="required">Catégorie</label>
                                <input type="text" class="form-control" id="categorie_etablissement" name="categorie_etablissement" required>
                                <div class="invalid-feedback">La catégorie est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nom_etablissement" class="required">Nom de l'Établissement</label>
                                <input type="text" class="form-control" id="nom_etablissement" name="nom_etablissement" required>
                                <div class="invalid-feedback">Le nom de l'établissement est obligatoire</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="service_etablissement">Service</label>
                                <input type="text" class="form-control" id="service_etablissement" name="service_etablissement">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="index.php?action=index" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
