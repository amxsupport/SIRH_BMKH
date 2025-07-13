<div class="certificate-container" style="max-width: 800px; margin: 20px auto; padding: 40px; border: 2px solid #333;">
    <div class="text-center mb-4">
        <img src="assets/img/logo.png" alt="Logo" style="width: 100px; height: auto; margin-bottom: 20px;">
        <h4 class="mb-0">ROYAUME DU MAROC</h4>
        <h5>Ministère de la Santé et de la protection sociale</h5>
        <h5>Direction Régionale de la Santé Béni Mellal-Khénifra</h5>
    </div>

    <h2 class="text-center mb-4">ATTESTATION DE TRAVAIL</h2>

    <div class="content" style="line-height: 2;">
        <p>Le Directeur Régional de la Santé de la région Béni Mellal-Khénifra atteste que :</p>
        
        <p class="ms-4">
            M./Mme <strong><?php echo htmlspecialchars($employee->nom . ' ' . $employee->prenom); ?></strong><br>
            PPR : <strong><?php echo htmlspecialchars($employee->ppr); ?></strong><br>
            CIN : <strong><?php echo htmlspecialchars($employee->cin); ?></strong><br>
            Corps : <strong><?php echo ucfirst(htmlspecialchars($employee->corps)); ?></strong><br>
            Grade : <strong><?php echo htmlspecialchars($employee->grade); ?></strong>
        </p>

        <p>
            Est employé(e) à <?php echo htmlspecialchars($employee->nom_etablissement); ?> 
            depuis le <?php echo date('d/m/Y', strtotime($employee->date_prise_service)); ?>.
        </p>

        <p>La présente attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.</p>

        <div class="text-end mt-5">
            <p>Fait à Béni Mellal, le <?php echo date('d/m/Y'); ?></p>
            <p class="mt-4">Le Directeur</p>
        </div>
    </div>

    <!-- Back button -->
    <div class="mt-4 text-center">
        <a href="index.php?action=view&id=<?php echo $employee->employee_id; ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

<style>
@media print {
    body {
        padding: 0;
        margin: 0;
    }
    .certificate-container {
        border: none !important;
        padding: 20px !important;
    }
    .d-print-none {
        display: none !important;
    }
}
</style>
