<?php
class LeaveController {
    private $leaveModel;
    private $employeeModel;

    public function __construct() {
        if (!defined('SIRH_BMKH')) {
            define('SIRH_BMKH', true);
        }
        $this->leaveModel = new Leave();
        $this->employeeModel = new Employee();
    }

    public function approve() {
        try {
            $status = $_GET['status'] ?? 'en_attente';
            $leaves = $this->leaveModel->getAll(['status' => $status]);
            
            if (!is_array($leaves)) {
                $leaves = [];
            }

            $_SESSION['success'] = $status === 'en_attente' ? 
                "Demandes en attente de validation" : 
                "Demandes avec statut : " . ucfirst(str_replace('_', ' ', $status));
                
            require 'views/leaves/approve.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors du chargement des demandes: " . $e->getMessage();
            $leaves = [];
            require 'views/leaves/approve.php';
        }
    }

    public function index() {
        try {
            $filters = [
                'employee_id' => $_GET['employee_id'] ?? null,
                'status' => $_GET['status'] ?? null
            ];
            
            $leaves = $this->leaveModel->getAll($filters);
            $employees = $this->employeeModel->getAll();

            if (!is_array($leaves)) {
                $leaves = [];
            }
            
            require 'views/leaves/index.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors du chargement des congés: " . $e->getMessage();
            $leaves = [];
            $employees = [];
            require 'views/leaves/index.php';
        }
    }

    private function validateDocument($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Check file size
        if ($file['size'] > $maxSize) {
            throw new Exception("Le fichier est trop volumineux. Taille maximale: 5MB");
        }

        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception("Type de fichier non autorisé. Types acceptés: PDF, JPG, PNG");
        }

        return true;
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate document if required
                if (isset($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $this->validateDocument($_FILES['document']);
                }

                // Check remaining days for this type of leave
                $remainingDays = $this->leaveModel->getRemainingDays(
                    $_POST['employee_id'], 
                    $_POST['type_id']
                );

                if ($remainingDays !== null) {
                    $requestedDuration = $this->leaveModel->calculateDuration(
                        $_POST['start_date'],
                        $_POST['end_date']
                    );
                    
                    if ($requestedDuration > $remainingDays) {
                        throw new Exception("Jours de congé insuffisants. Il vous reste " . $remainingDays . " jours.");
                    }
                }

                // Calculate end_date based on start_date and duration
                $start_date = $_POST['start_date'];
                $duration = (int)$_POST['duration'];
                $end_date = date('Y-m-d', strtotime($start_date . ' + ' . ($duration - 1) . ' days'));

                $data = [
                    'employee_id' => $_POST['employee_id'],
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'type_id' => $_POST['type_id'],
                    'reason' => $_POST['reason'],
                    'duration' => $duration
                ];

                // Add document if uploaded
                if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
                    $data['document'] = $_FILES['document'];
                }

                if ($this->leaveModel->create($data)) {
                    header('Location: index.php?controller=leave&action=index');
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        $employees = $this->employeeModel->getAll();
        $leaveTypes = $this->leaveModel->getLeaveTypes();
        $leaveModel = $this->leaveModel; // Pass leaveModel to view for remaining days calculation
        require 'views/leaves/create.php';
    }

    public function edit($id) {
        $leave = $this->leaveModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate document if required
                if (isset($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $this->validateDocument($_FILES['document']);
                }
                
                $data = [
                    'start_date' => $_POST['start_date'],
                    'end_date' => $_POST['end_date'],
                    'type_id' => $_POST['type_id'],
                    'reason' => $_POST['reason']
                ];

                // Add document if uploaded
                if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
                    $data['document'] = $_FILES['document'];
                }

                if ($this->leaveModel->update($id, $data)) {
                    header('Location: index.php?controller=leave&action=index');
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        $leaveTypes = $this->leaveModel->getLeaveTypes();
        require 'views/leaves/edit.php';
    }

    public function view($id) {
        $leave = $this->leaveModel->getById($id);
        require 'views/leaves/view.php';
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (!isset($_POST['id']) || !isset($_POST['status'])) {
                    throw new Exception("Paramètres manquants");
                }

                $id = $_POST['id'];
                $status = $_POST['status'];
                
                if (!isset($_SESSION['user_id'])) {
                    throw new Exception("Vous devez être connecté pour effectuer cette action");
                }
                
                $approver_id = $_SESSION['user_id'];
                
                if ($this->leaveModel->updateStatus($id, $status, $approver_id)) {
                    $_SESSION['success'] = "Le statut du congé a été mis à jour avec succès";
                    header('Location: index.php?controller=leave&action=approve');
                    exit;
                } else {
                    throw new Exception("Erreur lors de la mise à jour du statut");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: index.php?controller=leave&action=approve');
                exit;
            }
        }
    }

    public function getRemainingDays() {
        if (!isset($_GET['employee_id']) || !isset($_GET['type_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
            return;
        }

        $remaining = $this->leaveModel->getRemainingDays(
            $_GET['employee_id'],
            $_GET['type_id']
        );

        header('Content-Type: application/json');
        echo json_encode(['remaining' => $remaining ?? 0]);
    }

    public function statistics() {
        try {
            $year = $_GET['year'] ?? date('Y');
            $month = $_GET['month'] ?? null;
            
            $stats = [
                'total_leaves' => 0,
                'approved_leaves' => 0,
                'pending_leaves' => 0,
                'rejected_leaves' => 0,
                'by_type' => [],
                'monthly' => []
            ];

            // Get leave statistics
            $filters = ['year' => $year];
            if ($month) {
                $filters['month'] = $month;
            }

            $leaves = $this->leaveModel->getAll($filters);
            
            // Calculate statistics
            foreach ($leaves as $leave) {
                $stats['total_leaves']++;
                
                switch ($leave['status']) {
                    case 'approuve_final':
                        $stats['approved_leaves']++;
                        break;
                    case 'en_attente':
                        $stats['pending_leaves']++;
                        break;
                    case 'refuse':
                        $stats['rejected_leaves']++;
                        break;
                }
                
                // Group by type
                if (!isset($stats['by_type'][$leave['type_id']])) {
                    $stats['by_type'][$leave['type_id']] = [
                        'name' => $leave['type_name'],
                        'total' => 0,
                        'approved' => 0,
                        'pending' => 0,
                        'rejected' => 0,
                        'total_duration' => 0
                    ];
                }
                
                $stats['by_type'][$leave['type_id']]['total']++;
                $stats['by_type'][$leave['type_id']]['total_duration'] += $leave['duration'];
                
                switch ($leave['status']) {
                    case 'approuve_final':
                        $stats['by_type'][$leave['type_id']]['approved']++;
                        break;
                    case 'en_attente':
                        $stats['by_type'][$leave['type_id']]['pending']++;
                        break;
                    case 'refuse':
                        $stats['by_type'][$leave['type_id']]['rejected']++;
                        break;
                }
            }
            
            // Calculate average duration
            foreach ($stats['by_type'] as &$type) {
                $type['avg_duration'] = $type['total'] > 0 ? 
                    $type['total_duration'] / $type['total'] : 0;
            }
            
            // Monthly distribution
            $monthNames = [
                1 => 'Janvier', 2 => 'Février', 3 => 'Mars',
                4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
                7 => 'Juillet', 8 => 'Août', 9 => 'Septembre',
                10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
            ];
            
            foreach ($leaves as $leave) {
                $leaveMonth = (int)date('n', strtotime($leave['start_date']));
                if (!isset($stats['monthly'][$monthNames[$leaveMonth]])) {
                    $stats['monthly'][$monthNames[$leaveMonth]] = 0;
                }
                $stats['monthly'][$monthNames[$leaveMonth]]++;
            }
            
            require 'views/leaves/statistics.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors du chargement des statistiques: " . $e->getMessage();
            header('Location: index.php?controller=leave&action=index');
            exit;
        }
    }

    public function balance() {
        $employees = $this->employeeModel->getAll();
        $leaveTypes = $this->leaveModel->getLeaveTypes();
        $balances = [];

        // If employee_id is provided, calculate balance only for that employee
        if (isset($_GET['employee_id'])) {
            $employee_id = $_GET['employee_id'];
            $balances[$employee_id] = [];
            
            foreach ($leaveTypes as $type) {
                if ($type['default_days']) {
                    $remaining = $this->leaveModel->getRemainingDays(
                        $employee_id,
                        $type['type_id']
                    );
                    $used = $type['default_days'] - $remaining;
                    
                    $balances[$employee_id][] = [
                        'type_name' => $type['name'],
                        'total' => $type['default_days'],
                        'used' => $used,
                        'remaining' => $remaining
                    ];
                }
            }
        }
        
        require 'views/leaves/balance.php';
    }

    public function printDecision($id) {
        $leave = $this->leaveModel->getById($id);
        
        if (!$leave || $leave['status'] !== 'approuve_final') {
            throw new Exception("Congé non trouvé ou non approuvé");
        }
        
        require 'views/leaves/decision_pdf.php';
    }

    public function delete($id) {
        if ($this->leaveModel->delete($id)) {
            header('Location: index.php?controller=leave&action=index');
            exit;
        }
    }
}
