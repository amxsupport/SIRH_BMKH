<?php
class Intern {
    private $db;

    public function __construct() {
        try {
            global $db_config;
            
            if (!isset($db_config)) {
                $db_config = require __DIR__ . '/../config/database.php';
            }
            
            $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8";
            $this->db = new PDO($dsn, $db_config['username'], $db_config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }

    public function getAll() {
        $query = "SELECT * FROM interns ORDER BY nom, prenom";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT i.*, e.nom as superviseur_nom, e.prenom as superviseur_prenom 
                 FROM interns i 
                 LEFT JOIN employees e ON i.superviseur_id = e.employee_id 
                 WHERE i.intern_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($data) {
        $query = "INSERT INTO interns (
            cin, nom, prenom, sex, date_naissance, phone, email, 
            etablissement_education, niveau_education, specialite,
            date_debut, date_fin, province_etablissement, nom_etablissement, 
            service_etablissement, superviseur_id, status
        ) VALUES (
            :cin, :nom, :prenom, :sex, :date_naissance, :phone, :email,
            :etablissement_education, :niveau_education, :specialite,
            :date_debut, :date_fin, :province_etablissement, :nom_etablissement,
            :service_etablissement, :superviseur_id, :status
        )";

        $stmt = $this->db->prepare($query);
        $stmt->execute($this->sanitizeData($data));
    }

    public function update($id, $data) {
        $query = "UPDATE interns SET
            cin = :cin,
            nom = :nom,
            prenom = :prenom,
            sex = :sex,
            date_naissance = :date_naissance,
            phone = :phone,
            email = :email,
            etablissement_education = :etablissement_education,
            niveau_education = :niveau_education,
            specialite = :specialite,
            date_debut = :date_debut,
            date_fin = :date_fin,
            province_etablissement = :province_etablissement,
            nom_etablissement = :nom_etablissement,
            service_etablissement = :service_etablissement,
            superviseur_id = :superviseur_id,
            status = :status
        WHERE intern_id = :id";

        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        $stmt->execute($this->sanitizeData($data));
    }

    public function delete($id) {
        $query = "DELETE FROM interns WHERE intern_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
    }

    public function search($params) {
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
        if (!empty($params['status'])) {
            $conditions[] = "status = ?";
            $values[] = $params['status'];
        }

        $query = "SELECT * FROM interns";
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        $query .= " ORDER BY nom, prenom";

        $stmt = $this->db->prepare($query);
        $stmt->execute($values);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCount() {
        $query = "SELECT COUNT(*) FROM interns";
        return $this->db->query($query)->fetchColumn();
    }

    public function getActiveCount() {
        $query = "SELECT COUNT(*) FROM interns WHERE status = 'en_cours'";
        return $this->db->query($query)->fetchColumn();
    }

    public function getStatsByStatus() {
        $query = "SELECT status, COUNT(*) as count FROM interns GROUP BY status";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatsByEtablissementEducation() {
        $query = "SELECT etablissement_education, COUNT(*) as count 
                 FROM interns GROUP BY etablissement_education
                 ORDER BY count DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatsByService() {
        $query = "SELECT service_etablissement, COUNT(*) as count 
                 FROM interns GROUP BY service_etablissement
                 ORDER BY count DESC";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function sanitizeData($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = trim($value);
        }
        return $sanitized;
    }

    public function exportToExcel() {
        $query = "SELECT * FROM interns ORDER BY nom, prenom";
        $interns = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="liste_stagiaires.xls"');
        header('Cache-Control: max-age=0');

        echo "CIN\tNom\tPrénom\tÉtablissement d'Éducation\tSpécialité\tDate Début\tDate Fin\tService\tStatut\n";
        foreach ($interns as $intern) {
            echo implode("\t", [
                $intern['cin'],
                $intern['nom'],
                $intern['prenom'],
                $intern['etablissement_education'],
                $intern['specialite'],
                date('d/m/Y', strtotime($intern['date_debut'])),
                date('d/m/Y', strtotime($intern['date_fin'])),
                $intern['service_etablissement'],
                ucfirst($intern['status'])
            ]) . "\n";
        }
        exit;
    }

    public function exportToPDF() {
        require_once(__DIR__ . '/../lib/tcpdf/tcpdf.php');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('SIRH BMKH');
        $pdf->SetAuthor('Direction Régionale de la Santé');
        $pdf->SetTitle('Liste des Stagiaires');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(TRUE, 15);
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->AddPage('L');

        $query = "SELECT * FROM interns ORDER BY nom, prenom";
        $interns = $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);

        $html = '<h2>Liste des Stagiaires</h2>
                <table border="1" cellpadding="4">
                    <tr style="background-color: #f0f0f0; font-weight: bold;">
                        <th>CIN</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Établissement</th>
                        <th>Spécialité</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Service</th>
                        <th>Statut</th>
                    </tr>';

        foreach ($interns as $intern) {
            $html .= '<tr>
                        <td>' . $intern['cin'] . '</td>
                        <td>' . $intern['nom'] . '</td>
                        <td>' . $intern['prenom'] . '</td>
                        <td>' . $intern['etablissement_education'] . '</td>
                        <td>' . $intern['specialite'] . '</td>
                        <td>' . date('d/m/Y', strtotime($intern['date_debut'])) . '</td>
                        <td>' . date('d/m/Y', strtotime($intern['date_fin'])) . '</td>
                        <td>' . $intern['service_etablissement'] . '</td>
                        <td>' . ucfirst($intern['status']) . '</td>
                     </tr>';
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('liste_stagiaires.pdf', 'D');
        exit;
    }
}
