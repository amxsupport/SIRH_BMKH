<?php
class Leave {
    private $db;

    public function getLeaveTypes() {
        $sql = "SELECT type_id, name, description, default_days, requires_document 
                FROM leave_types 
                WHERE type_id IN (
                    SELECT MIN(type_id) 
                    FROM leave_types 
                    GROUP BY name
                )
                ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function isHoliday($date) {
        // Jours fériés fixes
        $fixedHolidays = [
            '01-01' => "Nouvel An",
            '01-11' => "Manifeste de l'independance",
            '05-01' => "Fete du Travail",
            '07-30' => "Fete du Trone",
            '08-14' => "Allegeance Oued Eddahab",
            '08-20' => "Revolution du Roi et du Peuple",
            '08-21' => "Fete de la Jeunesse",
            '11-06' => "Marche Verte",
            '11-18' => "Fete de l'Independance"
        ];

        // Vérifier les jours fériés fixes
        if (array_key_exists($date->format('m-d'), $fixedHolidays)) {
            return true;
        }

        // Jours fériés islamiques pour l'année en cours
        $islamicHolidays = $this->getIslamicHolidays($date->format('Y'));
        $currentDate = $date->format('Y-m-d');
        
        return in_array($currentDate, $islamicHolidays);
    }

    private function getIslamicHolidays($year) {
        // Ces dates doivent être mises à jour chaque année
        // car elles suivent le calendrier lunaire
        $holidays = [
            // 2025 dates
            '2025-01-01' => "Jour de l'an hegirien",
            '2025-04-10' => "Aid Al Fitr",
            '2025-04-11' => "Aid Al Fitr",
            '2025-06-17' => "Aid Al Adha",
            '2025-06-18' => "Aid Al Adha",
            '2025-09-30' => "Al Mawlid",
            // 2024 dates
            '2024-03-21' => "Aid Al Fitr",
            '2024-03-22' => "Aid Al Fitr",
            '2024-06-28' => "Aid Al Adha",
            '2024-06-29' => "Aid Al Adha",
            '2024-09-16' => "Jour de l'an hegirien",
            '2024-10-12' => "Al Mawlid"
        ];

        // Retourner uniquement les dates pour l'année demandée
        return array_keys(array_filter($holidays, function($date) use ($year) {
            return substr($date, 0, 4) === $year;
        }));
    }

    public function calculateDuration($start_date, $end_date) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $duration = 0;
        
        for ($date = clone $start; $date <= $end; $date->modify('+1 day')) {
            $dayOfWeek = $date->format('N');
            
            // Skip weekends (6 = Saturday, 7 = Sunday) and holidays
            if ($dayOfWeek < 6 && !$this->isHoliday($date)) {
                $duration++;
            }
        }
        
        return $duration;
    }

    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=sirh_bmkh;charset=utf8",
                "root",
                "",
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    public function getRemainingDays($employee_id, $type_id) {
        // Get leave type info
        $sql = "SELECT default_days FROM leave_types WHERE type_id = :type_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':type_id' => $type_id]);
        $type = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$type['default_days']) {
            return null; // No limit for this type
        }

        // Calculate used days this year
        $sql = "SELECT SUM(duration) as used_days 
                FROM leaves 
                WHERE employee_id = :employee_id 
                AND type_id = :type_id 
                AND YEAR(start_date) = YEAR(CURRENT_DATE)
                AND status IN ('approuve_n1', 'approuve_final')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':employee_id' => $employee_id,
            ':type_id' => $type_id
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $type['default_days'] - ($result['used_days'] ?? 0);
    }

    public function checkTeamAvailability($start_date, $end_date, $department_id) {
        $sql = "SELECT e.nom, e.prenom, l.start_date, l.end_date 
                FROM leaves l
                JOIN employees e ON l.employee_id = e.employee_id
                WHERE e.department_id = :department_id
                AND l.status IN ('approuve_n1', 'approuve_final')
                AND ((l.start_date BETWEEN :start_date AND :end_date)
                     OR (l.end_date BETWEEN :start_date AND :end_date))";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':department_id' => $department_id,
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $duration = $this->calculateDuration($data['start_date'], $data['end_date']);
        
        // Handle file upload if provided
        $document_path = null;
        if (isset($data['document']) && $data['document']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/documents/';
            $filename = uniqid() . '_' . basename($data['document']['name']);
            $document_path = $upload_dir . $filename;
            
            if (!move_uploaded_file($data['document']['tmp_name'], $document_path)) {
                throw new Exception("Erreur lors de l'upload du document");
            }
        }
        
        $sql = "INSERT INTO leaves (employee_id, type_id, start_date, end_date, duration, reason, status, document_path) 
                VALUES (:employee_id, :type_id, :start_date, :end_date, :duration, :reason, 'en_attente', :document_path)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':employee_id' => $data['employee_id'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':type_id' => $data['type_id'],
            ':duration' => $duration,
            ':reason' => $data['reason'],
            ':document_path' => $document_path
        ]);
    }

    public function getAll($filters = []) {
        $sql = "SELECT l.*, e.nom, e.prenom, l.leave_id as id,
                t.name as type_name, t.description as type_description,
                a.nom as approver_nom, a.prenom as approver_prenom
                FROM leaves l
                LEFT JOIN leave_types t ON l.type_id = t.type_id
                JOIN employees e ON l.employee_id = e.employee_id
                LEFT JOIN employees a ON l.approved_by = a.employee_id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['employee_id'])) {
            $sql .= " AND l.employee_id = :employee_id";
            $params[':employee_id'] = $filters['employee_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND l.status = :status";
            $params[':status'] = $filters['status'];
        }
        
        $sql .= " ORDER BY l.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT l.*, l.leave_id as id, e.nom, e.prenom,
                t.name as type_name, t.description as type_description, t.type_id,
                a.nom as approver_nom, a.prenom as approver_prenom
                FROM leaves l
                LEFT JOIN leave_types t ON l.type_id = t.type_id
                JOIN employees e ON l.employee_id = e.employee_id
                LEFT JOIN employees a ON l.approved_by = a.employee_id
                WHERE l.leave_id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $duration = $this->calculateDuration($data['start_date'], $data['end_date']);
        
        $sql = "UPDATE leaves SET 
                start_date = :start_date,
                end_date = :end_date,
                type_id = :type_id,
                duration = :duration,
                reason = :reason
                WHERE leave_id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':type_id' => $data['type_id'],
            ':duration' => $duration,
            ':reason' => $data['reason']
        ]);
    }

    public function updateStatus($id, $status, $approver_id) {
        $sql = "UPDATE leaves SET 
                status = :status,
                approved_by = :approved_by,
                approved_at = NOW()
                WHERE leave_id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status,
            ':approved_by' => $approver_id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM leaves WHERE leave_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
