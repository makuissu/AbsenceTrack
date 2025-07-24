<?php
// stats.php
header('Content-Type: application/json');

// Simulez des données selon la période (vous brancherez sur MySQL plus tard)
$students = [
    [ 'id' => 1, 'nom' => "N'DONGO", 'prenom' => "Sophie", 'classe' => "GI3A" ],
    [ 'id' => 2, 'nom' => "OUEDRAOGO", 'prenom' => "Marc", 'classe' => "GI3A" ],
    [ 'id' => 3, 'nom' => "KAMGA", 'prenom' => "Chantal", 'classe' => "GI3B" ],
    [ 'id' => 4, 'nom' => "MBOU", 'prenom' => "Brice", 'classe' => "GI3B" ]
];

// Absences simulées (en vrai, SELECT ... WHERE dateAbsence BETWEEN ...)
$absences = [
    [ 'idEtudiant' => 1, 'dateAbsence' => "2025-04-23" ],
    [ 'idEtudiant' => 1, 'dateAbsence' => "2025-04-30" ],
    [ 'idEtudiant' => 2, 'dateAbsence' => "2025-04-28" ],
    [ 'idEtudiant' => 2, 'dateAbsence' => "2025-05-02" ],
    [ 'idEtudiant' => 3, 'dateAbsence' => "2025-05-01" ],
    [ 'idEtudiant' => 3, 'dateAbsence' => "2025-05-08" ],
    [ 'idEtudiant' => 4, 'dateAbsence' => "2025-04-25" ]
];

$start = $_GET['start'] ?? "2025-04-22";
$end   = $_GET['end']   ?? date('Y-m-d');

// Filtrer absences
$filtered = array_filter($absences, function($a) use ($start, $end) {
    return $a['dateAbsence'] >= $start && $a['dateAbsence'] <= $end;
});

// --- Camembert par étudiant
$countByStudent = [];
foreach ($students as $stu) $countByStudent[$stu['id']] = 0;
foreach ($filtered as $ab) $countByStudent[$ab['idEtudiant']]++;
$pieLabels = [];
$pieValues = [];
foreach ($students as $stu) {
    if ($countByStudent[$stu['id']] > 0) {
        $pieLabels[] = $stu['prenom'] . ' ' . $stu['nom'];
        $pieValues[] = $countByStudent[$stu['id']];
    }
}

// --- Histogramme par semaine
// Utilise DateTime pour grouper par semaine
$barLabels = [];
$barValues = [];
$weeks = [];
foreach ($filtered as $ab) {
    $dt = new DateTime($ab['dateAbsence']);
    $yearweek = $dt->format("W/Y");
    if (!isset($weeks[$yearweek])) $weeks[$yearweek] = 0;
    $weeks[$yearweek]++;
}
ksort($weeks);
foreach ($weeks as $wk=>$val) {
    $barLabels[] = "Semaine $wk";
    $barValues[] = $val;
}

// --- Tableau par étudiant
$tableData = [];
foreach ($students as $stu) {
    if ($countByStudent[$stu['id']] > 0) {
        $tableData[] = [
            'etudiant' => $stu['prenom'] . ' ' . $stu['nom'],
            'classe'   => $stu['classe'],
            'absences' => $countByStudent[$stu['id']]
        ];
    }
}

echo json_encode([
    'pie'   => [ 'labels' => $pieLabels, 'values' => $pieValues ],
    'bar'   => [ 'labels' => $barLabels, 'values' => $barValues ],
    'table' => $tableData
]);