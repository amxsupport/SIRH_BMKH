<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Rapports et Exports</h4>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <!-- Employee List Reports -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Liste des Employés</h5>
                    </div>
                    <div class="card-body">
                        <p>Exporter la liste complète des employés avec leurs informations détaillées.</p>
                        <div class="d-grid gap-2">
                            <a href="index.php?action=exportEmployeeList&format=xlsx" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Exporter en XLSX
                            </a>
                            <a href="index.php?action=exportEmployeeList&format=pdf" class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf"></i> Exporter en PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Reports -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Statistiques</h5>
                    </div>
                    <div class="card-body">
                        <p>Exporter les statistiques (répartition par corps, province, etc.)</p>
                        <div class="d-grid gap-2">
                            <a href="index.php?action=exportStats&format=xlsx" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Exporter en XLSX
                            </a>
                            <a href="index.php?action=exportStats&format=pdf" class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf"></i> Exporter en PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Reports -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Rapport Personnalisé</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="index.php">
                            <input type="hidden" name="action" value="exportCustomReport">
                            
                            <div class="row g-3 mb-3">
                                <!-- Filters -->
                                <div class="col-md-3">
                                    <label class="form-label">Corps</label>
                                    <select name="corps" class="form-select">
                                        <option value="">Tous</option>
                                        <option value="medecin">Médecin</option>
                                        <option value="infirmier">Infirmier</option>
                                        <option value="administratif">Administratif</option>
                                        <option value="technicien">Technicien</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Province</label>
                                    <select name="province" class="form-select">
                                        <option value="">Toutes</option>
                                        <option value="Beni Mellal">Béni Mellal</option>
                                        <option value="Khenifra">Khénifra</option>
                                        <option value="Khouribga">Khouribga</option>
                                        <option value="Fquih Ben Salah">Fquih Ben Salah</option>
                                        <option value="Azilal">Azilal</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label">Établissement</label>
                                    <input type="text" name="etablissement" class="form-control" placeholder="Nom de l'établissement">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Format</label>
                                    <select name="format" class="form-select" required>
                                        <option value="xlsx">XLSX</option>
                                        <option value="pdf">PDF</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Colonnes à inclure</label>
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" value="ppr" checked>
                                                <label class="form-check-label">PPR</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" value="cin" checked>
                                                <label class="form-check-label">CIN</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" value="nom_complet" checked>
                                                <label class="form-check-label">Nom Complet</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" value="date_naissance">
                                                <label class="form-check-label">Date de Naissance</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" value="corps" checked>
                                                <label class="form-check-label">Corps</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" value="grade" checked>
                                                <label class="form-check-label">Grade</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" value="etablissement" checked>
                                                <label class="form-check-label">Établissement</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" value="province" checked>
                                                <label class="form-check-label">Province</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-download"></i> Générer le Rapport
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
