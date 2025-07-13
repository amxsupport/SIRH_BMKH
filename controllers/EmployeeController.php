<?php
require_once(__DIR__ . '/../models/Employee.php');

class EmployeeController {
    private $employee;

    public function __construct() {
        $this->employee = new Employee();
    }

    public function dashboard() {
        // Get all required data for dashboard
        $GLOBALS['employees'] = $this->employee->getAll();
        $GLOBALS['stats'] = [
            'total_employees' => $this->employee->getTotalCount(),
            'by_corps' => $this->employee->getStatsByCorps(),
            'by_province' => $this->employee->getStatsByProvince(),
            'by_milieu' => $this->employee->getStatsByMilieu(),
            'infrastructure' => $this->employee->getInfrastructureStats(),
            'gender' => $this->employee->getGenderStats(),
            'family_status' => $this->employee->getFamilyStatusStats()
        ];
        $GLOBALS['pendingNotifications'] = $this->employee->getPendingRetirementCount();
        
        // Get age pyramid data directly
        $GLOBALS['stats']['age_pyramid'] = $this->employee->getAgeDistribution();
    }

    public function index() {
        // Get search parameters
        $searchParams = [
            'nom' => isset($_GET['nom']) ? trim($_GET['nom']) : '',
            'prenom' => isset($_GET['prenom']) ? trim($_GET['prenom']) : '',
            'cin' => isset($_GET['cin']) ? trim($_GET['cin']) : '',
            'ppr' => isset($_GET['ppr']) ? trim($_GET['ppr']) : ''
        ];

        // Make variables available globally
        $GLOBALS['employees'] = $this->employee->search($searchParams);
        $GLOBALS['pendingNotifications'] = $this->employee->getPendingRetirementCount();
        
        error_log("Controller fetched " . count($GLOBALS['employees']) . " employees with search params: " . json_encode($searchParams));
    }

    public function create() {
        error_log("Create action called - Method: " . $_SERVER['REQUEST_METHOD']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->employee->create($_POST);
                $_SESSION['success'] = "L'employé a été créé avec succès";
                header('Location: index.php?action=index');
                exit;
            } catch (Exception $e) {
                $GLOBALS['error'] = $e->getMessage();
                error_log("Error creating employee: " . $e->getMessage());
            }
        }
        
        $GLOBALS['pendingNotifications'] = $this->employee->getPendingRetirementCount();
        error_log("Pending notifications count: " . $GLOBALS['pendingNotifications']);
    }

    public function view($id) {
        $GLOBALS['employee'] = $this->employee->getById($id);
        if (!$GLOBALS['employee']) {
            throw new Exception("Employé non trouvé");
        }
        $GLOBALS['pendingNotifications'] = $this->employee->getPendingRetirementCount();
        
        error_log("Viewing employee ID: " . $id);
    }

    public function update($id) {
        $GLOBALS['employee'] = $this->employee->getById($id);
        if (!$GLOBALS['employee']) {
            throw new Exception("Employé non trouvé");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->employee->update($id, $_POST);
                $_SESSION['success'] = "L'employé a été mis à jour avec succès";
                header('Location: index.php?action=view&id=' . $id);
                exit;
            } catch (Exception $e) {
                $GLOBALS['error'] = $e->getMessage();
            }
        }

        $GLOBALS['pendingNotifications'] = $this->employee->getPendingRetirementCount();
        
        error_log("Updating employee ID: " . $id);
    }

    public function delete($id) {
        try {
            $this->employee->delete($id);
            $_SESSION['success'] = "L'employé a été supprimé avec succès";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: index.php?action=index');
        exit;
    }

    public function statistics() {
        $GLOBALS['stats'] = [
            'total_employees' => $this->employee->getTotalCount(),
            'by_corps' => $this->employee->getStatsByCorps(),
            'by_province' => $this->employee->getStatsByProvince(),
            'by_milieu' => $this->employee->getStatsByMilieu()
        ];
        $GLOBALS['pendingNotifications'] = $this->employee->getPendingRetirementCount();
        
        error_log("Statistics generated: " . json_encode($GLOBALS['stats']));
    }

    public function checkRetirements() {
        $GLOBALS['notifications'] = $this->employee->getRetirementNotifications();
        $GLOBALS['pendingNotifications'] = count($GLOBALS['notifications']);
        
        error_log("Retirement notifications: " . count($GLOBALS['notifications']));
    }

    public function markNotificationAsRead($id) {
        try {
            $this->employee->markRetirementNotificationAsRead($id);
            $_SESSION['success'] = "Notification marquée comme lue";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: index.php?action=checkRetirements');
        exit;
    }

    public function attestation($id) {
        $employee = $this->employee->getById($id);
        if (!$employee) {
            throw new Exception("Employé non trouvé");
        }

        require_once(__DIR__ . '/../lib/tcpdf/config/tcpdf_config.php');
        require_once(__DIR__ . '/../lib/tcpdf/tcpdf.php');

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('SIRH BMKH');
        $pdf->SetAuthor('Direction Régionale de la Santé');
        $pdf->SetTitle('Attestation de Travail');

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

            // Logo path
            $logo_path = __DIR__ . '/../assets/images/logo.png';
            $default_logo = __DIR__ . '/../lib/tcpdf/images/logo.png';
            
            // Try to use custom logo, fallback to default if not available
            if (file_exists($logo_path)) {
                $pdf->Image($logo_path, 90, 10, 30);
            } elseif (file_exists($default_logo)) {
                $pdf->Image($default_logo, 90, 10, 30);
            $pdf->Ln(35);
        }

        // Generate HTML content
        $html = '
        <div style="text-align: center;">
            <h3 style="font-size: 14pt;">ROYAUME DU MAROC</h3>
            <h4 style="font-size: 12pt;">Ministère de la Santé</h4>
            <h4 style="font-size: 12pt;">Direction Régionale de la Santé Béni Mellal-Khénifra</h4>
            <h2 style="font-size: 16pt; margin-top: 20pt;">ATTESTATION DE TRAVAIL</h2>
        </div>
        <div style="margin-top: 30pt; line-height: 1.5;">
            <p>Le Directeur Régional de la Santé de la région Béni Mellal-Khénifra atteste que :</p>
            <table cellpadding="5" style="margin-left: 20pt;">
                <tr>
                    <td width="30%">M./Mme</td>
                    <td width="70%"><b>' . $employee->nom . ' ' . $employee->prenom . '</b></td>
                </tr>
                <tr>
                    <td>PPR</td>
                    <td><b>' . $employee->ppr . '</b></td>
                </tr>
                <tr>
                    <td>CIN</td>
                    <td><b>' . $employee->cin . '</b></td>
                </tr>
                <tr>
                    <td>Corps</td>
                    <td><b>' . ucfirst($employee->corps) . '</b></td>
                </tr>
                <tr>
                    <td>Grade</td>
                    <td><b>' . $employee->grade . '</b></td>
                </tr>
            </table>
            <p>Est employé(e) à <b>' . $employee->nom_etablissement . '</b> depuis le <b>' . 
            date('d/m/Y', strtotime($employee->date_prise_service)) . '</b>.</p>
            <p>La présente attestation est délivrée à l\'intéressé(e) pour servir et valoir ce que de droit.</p>
            <p style="text-align: right; margin-top: 40pt;">
                Fait à Béni Mellal, le ' . date('d/m/Y') . '<br><br>
                Le Directeur
            </p>
        </div>';

        // Print content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $pdf->Output('attestation_' . $employee->ppr . '.pdf', 'D');
        exit;
    }

    public function reports() {
        $GLOBALS['pendingNotifications'] = $this->employee->getPendingRetirementCount();
    }

    public function exportEmployeeList($format = 'xlsx') {
        try {
            if ($format === 'xlsx') {
                $this->employee->exportToExcel();
            } else {
                $this->employee->exportToPDF();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?action=reports');
            exit;
        }
    }

    public function exportStats($format = 'xlsx') {
        try {
            if ($format === 'xlsx') {
                $this->employee->exportStatsToExcel();
            } else {
                $this->employee->exportStatsToPDF();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?action=reports');
            exit;
        }
    }

    public function exportCustomReport() {
        try {
            $filters = [
                'corps' => isset($_GET['corps']) ? $_GET['corps'] : '',
                'province' => isset($_GET['province']) ? $_GET['province'] : '',
                'etablissement' => isset($_GET['etablissement']) ? $_GET['etablissement'] : ''
            ];
            
            $columns = isset($_GET['columns']) ? $_GET['columns'] : ['ppr', 'cin', 'nom_complet'];
            $format = isset($_GET['format']) ? $_GET['format'] : 'xlsx';

            if ($format === 'xlsx') {
                $this->employee->exportCustomReportToExcel($filters, $columns);
            } else {
                $this->employee->exportCustomReportToPDF($filters, $columns);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?action=reports');
            exit;
        }
    }

    public function getFilteredAgeDistribution($corps) {
        try {
            $data = $this->employee->getAgeDistribution($corps);
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
