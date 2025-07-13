<?php
require_once 'lib/tcpdf/tcpdf.php';

// Extend TCPDF
class MYPDF extends TCPDF {
    public function Header() {
        // Add logo
        $logoPath = __DIR__ . '/../../assets/img/logo.png';
        if(file_exists($logoPath)) {
            $this->Image($logoPath, 90, 10, 30);
            $this->Ln(35); // Add space after logo
        }
        
        $this->SetFont('dejavusans', 'B', 12);
        $this->Cell(0, 10, 'ROYAUME DU MAROC', 0, 1, 'C');
        $this->Cell(0, 10, 'Ministère de la Santé', 0, 1, 'C');
        $this->Cell(0, 10, 'Direction Régionale de la Santé', 0, 1, 'C');
        $this->Cell(0, 10, 'Béni Mellal-Khénifra', 0, 1, 'C');
        $this->Ln(10);
    }
}

// Create new PDF document
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8');

// Set document information
$pdf->SetCreator('SIRH BMKH');
$pdf->SetAuthor('Direction Régionale de la Santé');
$pdf->SetTitle('Décision de Congé');

// Set margins
$pdf->SetMargins(20, 20, 20);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('dejavusans', 'B', 16);

// Title
$pdf->Cell(0, 10, 'DÉCISION DE CONGÉ', 0, 1, 'C');
$pdf->Ln(10);

// Content
$pdf->SetFont('dejavusans', '', 12);

$startDate = date('d/m/Y', strtotime($leave['start_date']));
$endDate = date('d/m/Y', strtotime($leave['end_date']));
$duration = $leave['duration'];
$approvalDate = date('d/m/Y', strtotime($leave['approved_at']));

$content = <<<EOD
Le Directeur Régional de la Santé de Béni Mellal-Khénifra,

Vu le Dahir n° 1-58-008 du 4 chaâbane 1377 (24 février 1958) portant statut général de la fonction publique,

DÉCIDE

ARTICLE PREMIER. - Un congé de {$duration} jours est accordé à :

Nom et Prénom : {$leave['nom']} {$leave['prenom']}
Type de congé : {$leave['type_name']}
Période : Du {$startDate} au {$endDate}
Motif : {$leave['reason']}

ARTICLE 2. - Cette décision prend effet à compter du {$startDate}.

                                                    Fait à Béni Mellal, le {$approvalDate}
                                                    Le Directeur Régional de la Santé
EOD;

$pdf->MultiCell(0, 10, $content, 0, 'L');

// Output PDF
$pdf->Output('decision_conge.pdf', 'I');
?>
