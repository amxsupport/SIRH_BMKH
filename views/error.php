<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-exclamation-triangle"></i> Erreur
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger mb-4">
                        <?php echo $error; ?>
                    </div>
                    
                    <div class="text-center">
                        <a href="javascript:history.back()" class="btn btn-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                        <a href="index.php" class="btn btn-primary">
                            <i class="bi bi-house"></i> Accueil
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted">
                    Si le problème persiste, veuillez contacter l'administrateur système.
                </p>
            </div>
        </div>
    </div>
</div>
