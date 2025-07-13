<?php
require_once(__DIR__ . '/../models/Intern.php');

class InternController {
    private $intern;
    private $employee;

    public function __construct() {
        $this->intern = new Intern();
        $this->employee = new Employee();
    }

    public function dashboard() {
        // Get all required data for dashboard
        $GLOBALS['interns'] = $this->intern->getAll();
        $GLOBALS['stats'] = [
            'total_interns' => $this->intern->getTotalCount(),
            'active_interns' => $this->intern->getActiveCount(),
            'by_status' => $this->intern->getStatsByStatus(),
            'by_etablissement' => $this->intern->getStatsByEtablissementEducation(),
            'by_service' => $this->intern->getStatsByService()
        ];
    }

    public function index() {
        // Get search parameters
        $searchParams = [
            'nom' => isset($_GET['nom']) ? trim($_GET['nom']) : '',
            'prenom' => isset($_GET['prenom']) ? trim($_GET['prenom']) : '',
            'cin' => isset($_GET['cin']) ? trim($_GET['cin']) : '',
            'status' => isset($_GET['status']) ? trim($_GET['status']) : ''
        ];

        // Make variables available globally
        $GLOBALS['interns'] = $this->intern->search($searchParams);
        error_log("Controller fetched " . count($GLOBALS['interns']) . " interns with search params: " . json_encode($searchParams));
    }

    public function create() {
        error_log("Create action called - Method: " . $_SERVER['REQUEST_METHOD']);
        
        // Load supervisors for the form
        $GLOBALS['superviseurs'] = $this->employee->getAllForSupervisors();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->intern->create($_POST);
                $_SESSION['success'] = "Le stagiaire a été créé avec succès";
                header('Location: index.php?action=interns');
                exit;
            } catch (Exception $e) {
                $GLOBALS['error'] = $e->getMessage();
                error_log("Error creating intern: " . $e->getMessage());
            }
        }
    }

    public function view($id) {
        $GLOBALS['intern'] = $this->intern->getById($id);
        if (!$GLOBALS['intern']) {
            throw new Exception("Stagiaire non trouvé");
        }
        error_log("Viewing intern ID: " . $id);
    }

    public function update($id) {
        $GLOBALS['intern'] = $this->intern->getById($id);
        if (!$GLOBALS['intern']) {
            throw new Exception("Stagiaire non trouvé");
        }

        // Load supervisors for the form
        $GLOBALS['superviseurs'] = $this->employee->getAllForSupervisors();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->intern->update($id, $_POST);
                $_SESSION['success'] = "Le stagiaire a été mis à jour avec succès";
                header('Location: index.php?action=viewIntern&id=' . $id);
                exit;
            } catch (Exception $e) {
                $GLOBALS['error'] = $e->getMessage();
            }
        }
        error_log("Updating intern ID: " . $id);
    }

    public function delete($id) {
        try {
            $this->intern->delete($id);
            $_SESSION['success'] = "Le stagiaire a été supprimé avec succès";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: index.php?action=interns');
        exit;
    }

    public function statistics() {
        $GLOBALS['stats'] = [
            'total_interns' => $this->intern->getTotalCount(),
            'active_interns' => $this->intern->getActiveCount(),
            'by_status' => $this->intern->getStatsByStatus(),
            'by_etablissement' => $this->intern->getStatsByEtablissementEducation(),
            'by_service' => $this->intern->getStatsByService()
        ];
        error_log("Statistics generated: " . json_encode($GLOBALS['stats']));
    }

    public function exportInternList($format = 'xlsx') {
        try {
            if ($format === 'xlsx') {
                $this->intern->exportToExcel();
            } else {
                $this->intern->exportToPDF();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?action=internReports');
            exit;
        }
    }

    public function authorization($id) {
        $intern = $this->intern->getById($id);
        if (!$intern) {
            throw new Exception("Stagiaire non trouvé");
        }

        require_once(__DIR__ . '/../lib/tcpdf/config/tcpdf_config.php');
        require_once(__DIR__ . '/../lib/tcpdf/tcpdf.php');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('SIRH BMKH');
        $pdf->SetAuthor('Direction Régionale de la Santé');
        $pdf->SetTitle('Autorisation de Stage');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(20, 20, 20);
        $pdf->SetAutoPageBreak(TRUE, 25);
        $pdf->SetFont('dejavusans', '', 11);
        $pdf->AddPage();

        // Logo
        $logo_path = __DIR__ . '/../assets/images/logo.png';
        $default_logo = __DIR__ . '/../lib/tcpdf/images/logo.png';
        
        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, 90, 10, 30);
        } elseif (file_exists($default_logo)) {
            $pdf->Image($default_logo, 90, 10, 30);
        }
        $pdf->Ln(35);

        $html = '
        <div style="text-align: center;">
            <h3 style="font-size: 14pt;">ROYAUME DU MAROC</h3>
            <h4 style="font-size: 12pt;">Ministère de la Santé</h4>
            <h4 style="font-size: 12pt;">Direction Régionale de la Santé Béni Mellal-Khénifra</h4>
            <h2 style="font-size: 16pt; margin-top: 20pt;">AUTORISATION DE STAGE</h2>
        </div>
        <div style="margin-top: 30pt; line-height: 1.5;">
            <p>Suite à la demande de stage présentée par :</p>
            <table cellpadding="5" style="margin-left: 20pt;">
                <tr>
                    <td width="30%">M./Mme</td>
                    <td width="70%"><b>' . $intern->nom . ' ' . $intern->prenom . '</b></td>
                </tr>
                <tr>
                    <td>CIN</td>
                    <td><b>' . $intern->cin . '</b></td>
                </tr>
                <tr>
                    <td>Établissement</td>
                    <td><b>' . $intern->etablissement_education . '</b></td>
                </tr>
                <tr>
                    <td>Spécialité</td>
                    <td><b>' . $intern->specialite . '</b></td>
                </tr>
            </table>
            <p>La Direction Régionale de la Santé de Béni Mellal-Khénifra autorise l\'intéressé(e) à effectuer un stage à <b>' . 
            $intern->nom_etablissement . '</b> au sein du <b>' . $intern->service_etablissement . '</b>.</p>
            <p>Période du stage : du <b>' . date('d/m/Y', strtotime($intern->date_debut)) . '</b> au <b>' . 
            date('d/m/Y', strtotime($intern->date_fin)) . '</b>.</p>
            <p style="text-align: right; margin-top: 40pt;">
                Fait à Béni Mellal, le ' . date('d/m/Y') . '<br><br>
                Le Directeur
            </p>
        </div>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('autorisation_stage_' . str_replace(' ', '_', $intern->nom . '_' . $intern->prenom) . '.pdf', 'D');
        exit;
    }

    public function certificate($id) {
        $intern = $this->intern->getById($id);
        if (!$intern) {
            throw new Exception("Stagiaire non trouvé");
        }

        require_once(__DIR__ . '/../lib/tcpdf/config/tcpdf_config.php');
        require_once(__DIR__ . '/../lib/tcpdf/tcpdf.php');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('SIRH BMKH');
        $pdf->SetAuthor('Direction Régionale de la Santé');
        $pdf->SetTitle('Certificat de Stage');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(20, 20, 20);
        $pdf->SetAutoPageBreak(TRUE, 25);
        $pdf->SetFont('dejavusans', '', 11);
        $pdf->AddPage();

        // Logo
        $logo_path = __DIR__ . '/../assets/images/logo.png';
        $default_logo = __DIR__ . '/../lib/tcpdf/images/logo.png';
        
        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, 90, 10, 30);
        } elseif (file_exists($default_logo)) {
            $pdf->Image($default_logo, 90, 10, 30);
        }
        $pdf->Ln(35);

        $html = '
        <div style="text-align: center;">
            <h3 style="font-size: 14pt;">ROYAUME DU MAROC</h3>
            <h4 style="font-size: 12pt;">Ministère de la Santé</h4>
            <h4 style="font-size: 12pt;">Direction Régionale de la Santé Béni Mellal-Khénifra</h4>
            <h2 style="font-size: 16pt; margin-top: 20pt;">CERTIFICAT DE STAGE</h2>
        </div>
        <div style="margin-top: 30pt; line-height: 1.5;">
            <p>Le Directeur Régional de la Santé de la région Béni Mellal-Khénifra certifie que :</p>
            <table cellpadding="5" style="margin-left: 20pt;">
                <tr>
                    <td width="30%">M./Mme</td>
                    <td width="70%"><b>' . $intern->nom . ' ' . $intern->prenom . '</b></td>
                </tr>
                <tr>
                    <td>CIN</td>
                    <td><b>' . $intern->cin . '</b></td>
                </tr>
                <tr>
                    <td>Établissement</td>
                    <td><b>' . $intern->etablissement_education . '</b></td>
                </tr>
                <tr>
                    <td>Spécialité</td>
                    <td><b>' . $intern->specialite . '</b></td>
                </tr>
            </table>
            <p>A effectué un stage de formation à <b>' . $intern->nom_etablissement . '</b> au sein du <b>' . 
            $intern->service_etablissement . '</b> du <b>' . date('d/m/Y', strtotime($intern->date_debut)) . 
            '</b> au <b>' . date('d/m/Y', strtotime($intern->date_fin)) . '</b>.</p>
            <p>Durant ce stage, l\'intéressé(e) a fait preuve d\'assiduité et de sérieux dans l\'accomplissement 
            des tâches qui lui ont été confiées.</p>
            <p>Ce certificat est délivré à l\'intéressé(e) pour servir et valoir ce que de droit.</p>
            <p style="text-align: right; margin-top: 40pt;">
                Fait à Béni Mellal, le ' . date('d/m/Y') . '<br><br>
                Le Directeur
            </p>
        </div>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('certificat_stage_' . str_replace(' ', '_', $intern->nom . '_' . $intern->prenom) . '.pdf', 'D');
        exit;
    }

    public function attestation($id) {
        $intern = $this->intern->getById($id);
        if (!$intern) {
            throw new Exception("Stagiaire non trouvé");
        }

        require_once(__DIR__ . '/../lib/tcpdf/config/tcpdf_config.php');
        require_once(__DIR__ . '/../lib/tcpdf/tcpdf.php');

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('SIRH BMKH');
        $pdf->SetAuthor('Direction Régionale de la Santé');
        $pdf->SetTitle('Attestation de Stage');

        // Remove header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Set margins
        $pdf->SetMargins(20, 20, 20);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // Set font
        $pdf->SetFont('dejavusans', '', 11);

        // Add a page
        $pdf->AddPage();

        // Logo
        $logo_path = __DIR__ . '/../assets/images/logo.png';
        $default_logo = __DIR__ . '/../lib/tcpdf/images/logo.png';
        
        if (file_exists($logo_path)) {
            $pdf->Image($logo_path, 90, 10, 30);
        } elseif (file_exists($default_logo)) {
            $pdf->Image($default_logo, 90, 10, 30);
        }
        $pdf->Ln(35);

        // Generate HTML content
        $html = '
        <div style="text-align: center;">
            <h3 style="font-size: 14pt;">ROYAUME DU MAROC</h3>
            <h4 style="font-size: 12pt;">Ministère de la Santé</h4>
            <h4 style="font-size: 12pt;">Direction Régionale de la Santé Béni Mellal-Khénifra</h4>
            <h2 style="font-size: 16pt; margin-top: 20pt;">ATTESTATION DE STAGE</h2>
        </div>
        <div style="margin-top: 30pt; line-height: 1.5;">
            <p>Le Directeur Régional de la Santé de la région Béni Mellal-Khénifra atteste que :</p>
            <table cellpadding="5" style="margin-left: 20pt;">
                <tr>
                    <td width="30%">M./Mme</td>
                    <td width="70%"><b>' . $intern->nom . ' ' . $intern->prenom . '</b></td>
                </tr>
                <tr>
                    <td>CIN</td>
                    <td><b>' . $intern->cin . '</b></td>
                </tr>
                <tr>
                    <td>Établissement</td>
                    <td><b>' . $intern->etablissement_education . '</b></td>
                </tr>
                <tr>
                    <td>Spécialité</td>
                    <td><b>' . $intern->specialite . '</b></td>
                </tr>
            </table>
            <p>A effectué un stage à <b>' . $intern->nom_etablissement . '</b> du <b>' . 
            date('d/m/Y', strtotime($intern->date_debut)) . '</b> au <b>' . 
            date('d/m/Y', strtotime($intern->date_fin)) . '</b>.</p>
            <p>Service : <b>' . $intern->service_etablissement . '</b></p>
            <p>La présente attestation est délivrée à l\'intéressé(e) pour servir et valoir ce que de droit.</p>
            <p style="text-align: right; margin-top: 40pt;">
                Fait à Béni Mellal, le ' . date('d/m/Y') . '<br><br>
                Le Directeur
            </p>
        </div>';

        // Print content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $pdf->Output('attestation_stage_' . str_replace(' ', '_', $intern->nom . '_' . $intern->prenom) . '.pdf', 'D');
        exit;
    }
}
