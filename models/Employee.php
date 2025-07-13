<?php
class Employee {
    private $db;

    public function __construct() {
        try {
            global $db_config;
            
            if (!isset($db_config)) {
                $db_config = require __DIR__ . '/../config/database.php';
            }
            
            if (!is_array($db_config)) {
                throw new Exception("Database configuration could not be loaded");
            }
            
            if (!isset($db_config['host']) || !isset($db_config['dbname']) || 
                !isset($db_config['username']) || !isset($db_config['password'])) {
                throw new Exception("Invalid database configuration");
            }
            
            $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8";
            error_log("Connecting to database with DSN: " . $dsn);
            
            $this->db = new PDO($dsn, $db_config['username'], $db_config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            error_log("Database connection successful");
            
            $this->checkAndCreateNotificationColumn();
            
            $test = $this->db->query("SELECT COUNT(*) FROM employees");
            $count = $test->fetchColumn();
            error_log("Found {$count} total records in employees table");
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    private function checkAndCreateNotificationColumn() {
        try {
            $query = "SHOW COLUMNS FROM employees LIKE 'notification_read'";
            $result = $this->db->query($query);
            
            if ($result->rowCount() === 0) {
                $alterQuery = "ALTER TABLE employees ADD COLUMN notification_read TINYINT(1) DEFAULT 0";
                $this->db->exec($alterQuery);
            }
        } catch (PDOException $e) {
            if ($e->getCode() !== '42S02') {
                throw $e;
            }
        }
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM employees ORDER BY nom, prenom";
            $stmt = $this->db->query($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($result === false) {
                error_log("Error fetching employees: " . implode(" ", $this->db->errorInfo()));
                return [];
            }
            
            error_log("Found " . count($result) . " employees");
            return $result;
        } catch (PDOException $e) {
            error_log("Database error in getAll: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        $query = "SELECT * FROM employees WHERE employee_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($data) {
        $query = "INSERT INTO employees (
            ppr, cin, nom, prenom, sex, date_naissance, situation_familiale,
            phone, address, corps, grade, specialite, date_recrutement,
            date_prise_service, province_etablissement, milieu_etablissement,
            categorie_etablissement, nom_etablissement, service_etablissement
        ) VALUES (
            :ppr, :cin, :nom, :prenom, :sex, :date_naissance, :situation_familiale,
            :phone, :address, :corps, :grade, :specialite, :date_recrutement,
            :date_prise_service, :province_etablissement, :milieu_etablissement,
            :categorie_etablissement, :nom_etablissement, :service_etablissement
        )";

        $stmt = $this->db->prepare($query);
        $stmt->execute($this->sanitizeData($data));
    }

    public function update($id, $data) {
        $query = "UPDATE employees SET
            ppr = :ppr,
            cin = :cin,
            nom = :nom,
            prenom = :prenom,
            sex = :sex,
            date_naissance = :date_naissance,
            situation_familiale = :situation_familiale,
            phone = :phone,
            address = :address,
            corps = :corps,
            grade = :grade,
            specialite = :specialite,
            date_recrutement = :date_recrutement,
            date_prise_service = :date_prise_service,
            province_etablissement = :province_etablissement,
            milieu_etablissement = :milieu_etablissement,
            categorie_etablissement = :categorie_etablissement,
            nom_etablissement = :nom_etablissement,
            service_etablissement = :service_etablissement
        WHERE employee_id = :id";

        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($this->sanitizeData($data));
    }

    public function delete($id) {
        $query = "DELETE FROM employees WHERE employee_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
    }

    public function getTotalCount() {
        $query = "SELECT COUNT(*) FROM employees";
        return $this->db->query($query)->fetchColumn();
    }

    public function getStatsByCorps() {
        $query = "SELECT corps, COUNT(*) as count FROM employees GROUP BY corps";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatsByProvince() {
        $query = "SELECT province_etablissement, COUNT(*) as count 
                 FROM employees 
                 GROUP BY province_etablissement 
                 ORDER BY count DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatsByMilieu() {
        $query = "SELECT milieu_etablissement, COUNT(*) as count 
                 FROM employees 
                 GROUP BY milieu_etablissement";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInfrastructureStats() {
        $query = "SELECT 
                    categorie_etablissement, 
                    milieu_etablissement,
                    COUNT(*) as count 
                 FROM employees 
                 GROUP BY categorie_etablissement, milieu_etablissement";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGenderStats() {
        $query = "SELECT sex, COUNT(*) as count 
                 FROM employees 
                 GROUP BY sex";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFamilyStatusStats() {
        $query = "SELECT situation_familiale, COUNT(*) as count 
                 FROM employees 
                 GROUP BY situation_familiale";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRetirementNotifications() {
        try {
            $query = "SELECT *, 
                     DATE_ADD(date_naissance, INTERVAL 63 YEAR) as retirement_date
                     FROM employees 
                     WHERE TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) >= 62";
            
            $checkColumn = $this->db->query("SHOW COLUMNS FROM employees LIKE 'notification_read'");
            if ($checkColumn->rowCount() > 0) {
                $query .= " AND (notification_read = 0 OR notification_read IS NULL)";
            }
            
            return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getPendingRetirementCount() {
        try {
            $query = "SELECT COUNT(*) FROM employees 
                     WHERE TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) >= 62";
            
            $checkColumn = $this->db->query("SHOW COLUMNS FROM employees LIKE 'notification_read'");
            if ($checkColumn->rowCount() > 0) {
                $query .= " AND (notification_read = 0 OR notification_read IS NULL)";
            }
            
            return $this->db->query($query)->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function markRetirementNotificationAsRead($id) {
        try {
            $checkColumn = $this->db->query("SHOW COLUMNS FROM employees LIKE 'notification_read'");
            if ($checkColumn->rowCount() > 0) {
                $query = "UPDATE employees SET notification_read = 1 WHERE employee_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$id]);
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la mise à jour de la notification: " . $e->getMessage());
        }
    }

    public function search($params) {
        try {
            $conditions = [];
            $values = [];

            if (!empty($params['nom'])) {
                $conditions[] = "nom LIKE ?";
                $values[] = "%" . $params['nom'] . "%";
            }
            if (!empty($params['prenom'])) {
                $conditions[] = "prenom LIKE ?";
                $values[] = "%" . $params['prenom'] . "%";
            }
            if (!empty($params['cin'])) {
                $conditions[] = "cin LIKE ?";
                $values[] = "%" . $params['cin'] . "%";
            }
            if (!empty($params['ppr'])) {
                $conditions[] = "ppr LIKE ?";
                $values[] = "%" . $params['ppr'] . "%";
            }

            $query = "SELECT * FROM employees";
            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }
            $query .= " ORDER BY nom, prenom";

            $stmt = $this->db->prepare($query);
            $stmt->execute($values);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in search: " . $e->getMessage());
            return [];
        }
    }

    public function exportToExcel() {
        $query = "SELECT * FROM employees ORDER BY nom, prenom";
        $employees = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="liste_employes.xls"');
        header('Cache-Control: max-age=0');

        echo "PPR\tCIN\tNom\tPrénom\tSexe\tDate de Naissance\tCorps\tGrade\tÉtablissement\tProvince\n";
        foreach ($employees as $employee) {
            echo implode("\t", [
                $employee['ppr'],
                $employee['cin'],
                $employee['nom'],
                $employee['prenom'],
                $employee['sex'],
                $employee['date_naissance'],
                $employee['corps'],
                $employee['grade'],
                $employee['nom_etablissement'],
                $employee['province_etablissement']
            ]) . "\n";
        }
        exit;
    }

    public function exportToPDF() {
        require_once(__DIR__ . '/../lib/tcpdf/tcpdf.php');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('SIRH BMKH');
        $pdf->SetAuthor('Direction Régionale de la Santé');
        $pdf->SetTitle('Liste des Employés');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->AddPage('L');

        $query = "SELECT * FROM employees ORDER BY nom, prenom";
        $employees = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $html = '<h2>Liste des Employés</h2>
                <table border="1" cellpadding="4">
                    <tr style="background-color: #f0f0f0; font-weight: bold;">
                        <th>PPR</th>
                        <th>CIN</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Sexe</th>
                        <th>Corps</th>
                        <th>Grade</th>
                        <th>Établissement</th>
                        <th>Province</th>
                    </tr>';

        foreach ($employees as $employee) {
            $html .= '<tr>
                        <td>' . $employee['ppr'] . '</td>
                        <td>' . $employee['cin'] . '</td>
                        <td>' . $employee['nom'] . '</td>
                        <td>' . $employee['prenom'] . '</td>
                        <td>' . $employee['sex'] . '</td>
                        <td>' . $employee['corps'] . '</td>
                        <td>' . $employee['grade'] . '</td>
                        <td>' . $employee['nom_etablissement'] . '</td>
                        <td>' . $employee['province_etablissement'] . '</td>
                     </tr>';
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('liste_employes.pdf', 'D');
        exit;
    }

    public function exportStatsToExcel() {
        $stats = [
            'corps' => $this->getStatsByCorps(),
            'province' => $this->getStatsByProvince(),
            'milieu' => $this->getStatsByMilieu(),
            'gender' => $this->getGenderStats()
        ];

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="statistiques.xls"');
        header('Cache-Control: max-age=0');

        // Corps
        echo "Statistiques par Corps\n";
        echo "Corps\tNombre\n";
        foreach ($stats['corps'] as $stat) {
            echo $stat['corps'] . "\t" . $stat['count'] . "\n";
        }
        echo "\n";

        // Province
        echo "Statistiques par Province\n";
        echo "Province\tNombre\n";
        foreach ($stats['province'] as $stat) {
            echo $stat['province_etablissement'] . "\t" . $stat['count'] . "\n";
        }
        echo "\n";

        // Milieu
        echo "Statistiques par Milieu\n";
        echo "Milieu\tNombre\n";
        foreach ($stats['milieu'] as $stat) {
            echo $stat['milieu_etablissement'] . "\t" . $stat['count'] . "\n";
        }
        echo "\n";

        // Gender
        echo "Statistiques par Genre\n";
        echo "Genre\tNombre\n";
        foreach ($stats['gender'] as $stat) {
            echo ($stat['sex'] === 'M' ? 'Masculin' : 'Féminin') . "\t" . $stat['count'] . "\n";
        }
        exit;
    }

    public function exportStatsToPDF() {
        require_once(__DIR__ . '/../lib/tcpdf/tcpdf.php');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('SIRH BMKH');
        $pdf->SetAuthor('Direction Régionale de la Santé');
        $pdf->SetTitle('Statistiques des Employés');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(20, 20, 20);
        $pdf->SetAutoPageBreak(TRUE, 25);
        $pdf->SetFont('dejavusans', '', 11);
        $pdf->AddPage();

        $stats = [
            'corps' => $this->getStatsByCorps(),
            'province' => $this->getStatsByProvince(),
            'milieu' => $this->getStatsByMilieu(),
            'gender' => $this->getGenderStats()
        ];

        $html = '<h2>Statistiques des Employés</h2>';

        // Corps
        $html .= '<h3>Répartition par Corps</h3>
                 <table border="1" cellpadding="5">
                    <tr style="background-color: #f0f0f0;">
                        <th>Corps</th>
                        <th>Nombre</th>
                    </tr>';
        foreach ($stats['corps'] as $stat) {
            $html .= '<tr>
                        <td>' . ucfirst($stat['corps']) . '</td>
                        <td>' . $stat['count'] . '</td>
                     </tr>';
        }
        $html .= '</table><br><br>';

        // Province
        $html .= '<h3>Répartition par Province</h3>
                 <table border="1" cellpadding="5">
                    <tr style="background-color: #f0f0f0;">
                        <th>Province</th>
                        <th>Nombre</th>
                    </tr>';
        foreach ($stats['province'] as $stat) {
            $html .= '<tr>
                        <td>' . $stat['province_etablissement'] . '</td>
                        <td>' . $stat['count'] . '</td>
                     </tr>';
        }
        $html .= '</table><br><br>';

        // Milieu
        $html .= '<h3>Répartition par Milieu</h3>
                 <table border="1" cellpadding="5">
                    <tr style="background-color: #f0f0f0;">
                        <th>Milieu</th>
                        <th>Nombre</th>
                    </tr>';
        foreach ($stats['milieu'] as $stat) {
            $html .= '<tr>
                        <td>' . $stat['milieu_etablissement'] . '</td>
                        <td>' . $stat['count'] . '</td>
                     </tr>';
        }
        $html .= '</table><br><br>';

        // Gender
        $html .= '<h3>Répartition par Genre</h3>
                 <table border="1" cellpadding="5">
                    <tr style="background-color: #f0f0f0;">
                        <th>Genre</th>
                        <th>Nombre</th>
                    </tr>';
        foreach ($stats['gender'] as $stat) {
            $html .= '<tr>
                        <td>' . ($stat['sex'] === 'M' ? 'Masculin' : 'Féminin') . '</td>
                        <td>' . $stat['count'] . '</td>
                     </tr>';
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('statistiques.pdf', 'D');
        exit;
    }

    public function exportCustomReportToExcel($filters, $columns) {
        $conditions = [];
        $values = [];

        if (!empty($filters['corps'])) {
            $conditions[] = "corps = ?";
            $values[] = $filters['corps'];
        }
        if (!empty($filters['province'])) {
            $conditions[] = "province_etablissement = ?";
            $values[] = $filters['province'];
        }
        if (!empty($filters['etablissement'])) {
            $conditions[] = "nom_etablissement LIKE ?";
            $values[] = "%" . $filters['etablissement'] . "%";
        }

        $query = "SELECT * FROM employees";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $query .= " ORDER BY nom, prenom";

        $stmt = $this->db->prepare($query);
        $stmt->execute($values);
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="rapport_personnalise.xls"');
        header('Cache-Control: max-age=0');

        // Headers
        $headers = [];
        foreach ($columns as $column) {
            switch ($column) {
                case 'ppr': $headers[] = 'PPR'; break;
                case 'cin': $headers[] = 'CIN'; break;
                case 'nom_complet': $headers[] = 'Nom Complet'; break;
                case 'date_naissance': $headers[] = 'Date de Naissance'; break;
                case 'corps': $headers[] = 'Corps'; break;
                case 'grade': $headers[] = 'Grade'; break;
                case 'etablissement': $headers[] = 'Établissement'; break;
                case 'province': $headers[] = 'Province'; break;
            }
        }
        echo implode("\t", $headers) . "\n";

        // Data
        foreach ($employees as $employee) {
            $row = [];
            foreach ($columns as $column) {
                switch ($column) {
                    case 'ppr': 
                        $row[] = $employee['ppr'];
                        break;
                    case 'cin': 
                        $row[] = $employee['cin'];
                        break;
                    case 'nom_complet': 
                        $row[] = $employee['nom'] . ' ' . $employee['prenom'];
                        break;
                    case 'date_naissance': 
                        $row[] = $employee['date_naissance'];
                        break;
                    case 'corps': 
                        $row[] = $employee['corps'];
                        break;
                    case 'grade': 
                        $row[] = $employee['grade'];
                        break;
                    case 'etablissement': 
                        $row[] = $employee['nom_etablissement'];
                        break;
                    case 'province': 
                        $row[] = $employee['province_etablissement'];
                        break;
                }
            }
            echo implode("\t", $row) . "\n";
        }
        exit;
    }

    public function getAllForSupervisors() {
        $query = "SELECT employee_id, nom, prenom FROM employees ORDER BY nom, prenom";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exportCustomReportToPDF($filters, $columns) {
        require_once(__DIR__ . '/../lib/tcpdf/tcpdf.php');

        // Get filtered data
        $conditions = [];
        $values = [];

        if (!empty($filters['corps'])) {
            $conditions[] = "corps = ?";
            $values[] = $filters['corps'];
        }
        if (!empty($filters['province'])) {
            $conditions[] = "province_etablissement = ?";
            $values[] = $filters['province'];
        }
        if (!empty($filters['etablissement'])) {
            $conditions[] = "nom_etablissement LIKE ?";
            $values[] = "%" . $filters['etablissement'] . "%";
        }

        $query = "SELECT * FROM employees";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $query .= " ORDER BY nom, prenom";

        $stmt = $this->db->prepare($query);
        $stmt->execute($values);
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Create PDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('SIRH BMKH');
        $pdf->SetAuthor('Direction Régionale de la Santé');
        $pdf->SetTitle('Rapport Personnalisé');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->AddPage('L');

        $html = '<h2>Rapport Personnalisé</h2>';
        
        // Add filters information
        $html .= '<p><strong>Filtres appliqués:</strong><br>';
        if (!empty($filters['corps'])) $html .= 'Corps: ' . ucfirst($filters['corps']) . '<br>';
        if (!empty($filters['province'])) $html .= 'Province: ' . $filters['province'] . '<br>';
        if (!empty($filters['etablissement'])) $html .= 'Établissement: ' . $filters['etablissement'] . '<br>';
        $html .= '</p>';

        $html .= '<table border="1" cellpadding="4">';
        
        // Headers
        $html .= '<tr style="background-color: #f0f0f0; font-weight: bold;">';
        foreach ($columns as $column) {
            switch ($column) {
                case 'ppr': $html .= '<th>PPR</th>'; break;
                case 'cin': $html .= '<th>CIN</th>'; break;
                case 'nom_complet': $html .= '<th>Nom Complet</th>'; break;
                case 'date_naissance': $html .= '<th>Date de Naissance</th>'; break;
                case 'corps': $html .= '<th>Corps</th>'; break;
                case 'grade': $html .= '<th>Grade</th>'; break;
                case 'etablissement': $html .= '<th>Établissement</th>'; break;
                case 'province': $html .= '<th>Province</th>'; break;
            }
        }
        $html .= '</tr>';

        // Data
        foreach ($employees as $employee) {
            $html .= '<tr>';
            foreach ($columns as $column) {
                switch ($column) {
                    case 'ppr': 
                        $html .= '<td>' . $employee['ppr'] . '</td>';
                        break;
                    case 'cin': 
                        $html .= '<td>' . $employee['cin'] . '</td>';
                        break;
                    case 'nom_complet': 
                        $html .= '<td>' . $employee['nom'] . ' ' . $employee['prenom'] . '</td>';
                        break;
                    case 'date_naissance': 
                        $html .= '<td>' . $employee['date_naissance'] . '</td>';
                        break;
                    case 'corps': 
                        $html .= '<td>' . ucfirst($employee['corps']) . '</td>';
                        break;
                    case 'grade': 
                        $html .= '<td>' . $employee['grade'] . '</td>';
                        break;
                    case 'etablissement': 
                        $html .= '<td>' . $employee['nom_etablissement'] . '</td>';
                        break;
                    case 'province': 
                        $html .= '<td>' . $employee['province_etablissement'] . '</td>';
                        break;
                }
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('rapport_personnalise.pdf', 'D');
        exit;
    }

    public function getAgeDistribution($corps = 'all') {
        $ageGroupCase = "CASE
            WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) < 20 THEN '< 20'
            WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 21 AND 25 THEN '21-25'
            WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 26 AND 30 THEN '26-30'
            WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 31 AND 35 THEN '31-35'
            WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 36 AND 40 THEN '36-40'
            WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 41 AND 55 THEN '41-55'
            WHEN TIMESTAMPDIFF(YEAR, date_naissance, CURDATE()) BETWEEN 56 AND 60 THEN '56-60'
            ELSE '61-63'
        END";

        $query = "SELECT 
            {$ageGroupCase} AS age_group,
            sex,
            COUNT(*) as count
        FROM employees";

        if ($corps !== 'all') {
            $query .= " WHERE corps = :corps";
        }

        $query .= " GROUP BY {$ageGroupCase}, sex ORDER BY age_group";

        $stmt = $this->db->prepare($query);
        
        if ($corps !== 'all') {
            $stmt->execute(['corps' => $corps]);
        } else {
            $stmt->execute();
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize all age groups with zero counts
        $ageGroups = ['< 20', '21-25', '26-30', '31-35', '36-40', '41-55', '56-60', '61-63'];
        $formatted = [];

        foreach ($ageGroups as $group) {
            $formatted[$group] = ['M' => 0, 'F' => 0, 'total' => 0];
        }

        // Fill in actual counts
        foreach ($result as $row) {
            $formatted[$row['age_group']][$row['sex']] = $row['count'];
            $formatted[$row['age_group']]['total'] += $row['count'];
        }

        return $formatted;
    }

    private function sanitizeData($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = trim($value);
        }
        return $sanitized;
    }
}
