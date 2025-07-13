<?php
// Script pour générer des données de test aléatoires pour la région Béni Mellal-Khénifra

$db_config = require_once '../config/database.php';

try {
    $db = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8",
        $db_config['username'],
        $db_config['password']
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Données de base
$noms = ['Alami', 'Benjelloun', 'Tazi', 'Idrissi', 'Fassi', 'Chraibi', 'Bennani', 'Rachidi', 'Ouazzani', 
         'El Amrani', 'Saidi', 'Lahlou', 'Berrada', 'Alaoui', 'Lahlimi', 'Mansouri', 'Tahiri', 'Haddad',
         'Ziani', 'El Mokri', 'Zouaoui', 'Drissi', 'Filali', 'Hassani', 'Karimi'];

$prenoms_h = ['Mohammed', 'Ahmed', 'Youssef', 'Hassan', 'Karim', 'Rachid', 'Omar', 'Hamza', 'Amine',
              'Mehdi', 'Samir', 'Khalid', 'Ibrahim', 'Nabil', 'Adil', 'Jamal', 'Tarik', 'Mourad'];

$prenoms_f = ['Fatima', 'Amina', 'Nadia', 'Samira', 'Khadija', 'Leila', 'Asmaa', 'Sanaa', 'Zineb',
              'Meryem', 'Hanane', 'Imane', 'Loubna', 'Houda', 'Naima', 'Karima', 'Sofia', 'Lamia'];

$provinces = ['Béni Mellal', 'Khénifra', 'Khouribga', 'Azilal', 'Fquih Ben Salah'];

$etablissements = [
    'Hôpital' => ['Hôpital Régional', 'Hôpital Provincial', 'Centre Hospitalier'],
    'Centre de Santé' => ['CS Niveau 1', 'CS Niveau 2', 'CS Urbain', 'CS Rural'],
    'Dispensaire' => ['Dispensaire Rural', 'Dispensaire Urbain'],
    'Direction' => ['Direction Régionale', 'Direction Provinciale']
];

$services_medicaux = ['Cardiologie', 'Pédiatrie', 'Chirurgie', 'Médecine Interne', 'Gynécologie', 
                     'Urgences', 'Radiologie', 'ORL', 'Ophtalmologie', 'Traumatologie'];

$services_paramedicaux = ['Soins Infirmiers', 'Radiologie', 'Laboratoire', 'Kinésithérapie', 
                         'Maternité', 'Consultation', 'Urgences', 'Vaccination'];

$services_administratifs = ['Administration', 'Ressources Humaines', 'Comptabilité', 'Services Techniques', 
                          'Secrétariat', 'Archives', 'Informatique', 'Logistique'];

$grades_medicaux = ['Médecin spécialiste', 'Médecin généraliste', 'Chirurgien', 'Chef de service'];
$grades_paramedicaux = ['Infirmier', 'Sage-femme', 'Technicien de santé', 'Assistant médical'];
$grades_administratifs = ['Administrateur', 'Secrétaire', 'Technicien', 'Agent administratif'];

function generateRandomDate($startYear, $endYear) {
    $start = strtotime("1-1-$startYear");
    $end = strtotime("12-31-$endYear");
    return date('Y-m-d', mt_rand($start, $end));
}

function generatePhone() {
    return '06' . mt_rand(10000000, 99999999);
}

// Générer les données
$data = [];
$used_pprs = [];
$used_cins = [];

// 30 médecins
for ($i = 1; $i <= 30; $i++) {
    $sex = mt_rand(0, 1) ? 'M' : 'F';
    $nom = $noms[array_rand($noms)];
    $prenom = $sex === 'M' ? $prenoms_h[array_rand($prenoms_h)] : $prenoms_f[array_rand($prenoms_f)];
    
    do {
        $ppr = 'M' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    } while (in_array($ppr, $used_pprs));
    $used_pprs[] = $ppr;

    do {
        $cin = 'QB' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    } while (in_array($cin, $used_cins));
    $used_cins[] = $cin;

    $province = $provinces[array_rand($provinces)];
    $date_naissance = generateRandomDate(1965, 1990);
    $date_recrutement = generateRandomDate(2000, 2020);
    $date_prise_service = date('Y-m-d', strtotime($date_recrutement . ' +15 days'));

    $stmt = $db->prepare('INSERT INTO employees (
        ppr, cin, nom, prenom, sex, date_naissance, situation_familiale,
        phone, address, corps, grade, specialite, date_recrutement,
        date_prise_service, province_etablissement, milieu_etablissement,
        categorie_etablissement, nom_etablissement, service_etablissement
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $stmt->execute([
        $ppr, $cin, $nom, $prenom, $sex, $date_naissance,
        mt_rand(0, 1) ? 'Marié' . ($sex === 'F' ? 'e' : '') : 'Célibataire',
        generatePhone(),
        $province,
        'medical',
        $grades_medicaux[array_rand($grades_medicaux)],
        $services_medicaux[array_rand($services_medicaux)],
        $date_recrutement,
        $date_prise_service,
        $province,
        mt_rand(0, 1) ? 'Urbain' : 'Rural',
        'Hôpital',
        $etablissements['Hôpital'][array_rand($etablissements['Hôpital'])],
        $services_medicaux[array_rand($services_medicaux)]
    ]);
}

// 40 paramédicaux
for ($i = 1; $i <= 40; $i++) {
    $sex = mt_rand(0, 1) ? 'M' : 'F';
    $nom = $noms[array_rand($noms)];
    $prenom = $sex === 'M' ? $prenoms_h[array_rand($prenoms_h)] : $prenoms_f[array_rand($prenoms_f)];
    
    do {
        $ppr = 'P' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    } while (in_array($ppr, $used_pprs));
    $used_pprs[] = $ppr;

    do {
        $cin = 'QB' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    } while (in_array($cin, $used_cins));
    $used_cins[] = $cin;

    $province = $provinces[array_rand($provinces)];
    $date_naissance = generateRandomDate(1970, 1995);
    $date_recrutement = generateRandomDate(2000, 2022);
    $date_prise_service = date('Y-m-d', strtotime($date_recrutement . ' +15 days'));

    $stmt = $db->prepare('INSERT INTO employees (
        ppr, cin, nom, prenom, sex, date_naissance, situation_familiale,
        phone, address, corps, grade, specialite, date_recrutement,
        date_prise_service, province_etablissement, milieu_etablissement,
        categorie_etablissement, nom_etablissement, service_etablissement
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $categorie = mt_rand(0, 1) ? 'Centre de Santé' : 'Dispensaire';

    $stmt->execute([
        $ppr, $cin, $nom, $prenom, $sex, $date_naissance,
        mt_rand(0, 1) ? 'Marié' . ($sex === 'F' ? 'e' : '') : 'Célibataire',
        generatePhone(),
        $province,
        'paramedical',
        $grades_paramedicaux[array_rand($grades_paramedicaux)],
        $services_paramedicaux[array_rand($services_paramedicaux)],
        $date_recrutement,
        $date_prise_service,
        $province,
        mt_rand(0, 1) ? 'Urbain' : 'Rural',
        $categorie,
        $etablissements[$categorie][array_rand($etablissements[$categorie])],
        $services_paramedicaux[array_rand($services_paramedicaux)]
    ]);
}

// 30 administratifs
for ($i = 1; $i <= 30; $i++) {
    $sex = mt_rand(0, 1) ? 'M' : 'F';
    $nom = $noms[array_rand($noms)];
    $prenom = $sex === 'M' ? $prenoms_h[array_rand($prenoms_h)] : $prenoms_f[array_rand($prenoms_f)];
    
    do {
        $ppr = 'A' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    } while (in_array($ppr, $used_pprs));
    $used_pprs[] = $ppr;

    do {
        $cin = 'QB' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    } while (in_array($cin, $used_cins));
    $used_cins[] = $cin;

    $province = $provinces[array_rand($provinces)];
    $date_naissance = generateRandomDate(1970, 1995);
    $date_recrutement = generateRandomDate(2000, 2022);
    $date_prise_service = date('Y-m-d', strtotime($date_recrutement . ' +15 days'));

    $stmt = $db->prepare('INSERT INTO employees (
        ppr, cin, nom, prenom, sex, date_naissance, situation_familiale,
        phone, address, corps, grade, specialite, date_recrutement,
        date_prise_service, province_etablissement, milieu_etablissement,
        categorie_etablissement, nom_etablissement, service_etablissement
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $stmt->execute([
        $ppr, $cin, $nom, $prenom, $sex, $date_naissance,
        mt_rand(0, 1) ? 'Marié' . ($sex === 'F' ? 'e' : '') : 'Célibataire',
        generatePhone(),
        $province,
        'administratif',
        $grades_administratifs[array_rand($grades_administratifs)],
        $services_administratifs[array_rand($services_administratifs)],
        $date_recrutement,
        $date_prise_service,
        $province,
        'Urbain',
        'Direction',
        $etablissements['Direction'][array_rand($etablissements['Direction'])],
        $services_administratifs[array_rand($services_administratifs)]
    ]);
}

echo "100 enregistrements ont été générés avec succès.\n";
