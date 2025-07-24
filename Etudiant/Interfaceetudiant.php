<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Etudiant') {
    header('Location: ../login.php');
    // exit;
}

// Connexion à la base de données
$host = '127.0.0.1';
$db = 'absencetrack';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des infos étudiant
$stmt = $pdo->prepare("
    SELECT e.*, u.emailadmin 
    FROM utilisateur u
    JOIN etudiant e ON u.idUtilisateur = e.idUtilisateur
    WHERE u.login = ?
");
$stmt->execute(array($_SESSION['user']['login']));
$etudiant = $stmt->fetch();
if (!$etudiant) die("Étudiant non trouvé.");

// Traitement justification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['justifier'])) {
    $idAbsence = $_POST['justifier'];
    $justifications = isset($_POST['justifications']) ? $_POST['justifications'] : [];

    if (!empty($justifications[$idAbsence])) {
        $justif = trim($justifications[$idAbsence]);

        $update = $pdo->prepare("UPDATE absence SET justification = ?, statut = 'Justifié' WHERE idAbsence = ? AND idEtudiant = ?");
        $update->execute([$justif, $idAbsence, $etudiant['idEtudiant']]);

        // Recharge la page pour voir les changements
       $queryParams = http_build_query(array(
    'debut' => isset($_GET['debut']) ? $_GET['debut'] : '',
    'fin' => isset($_GET['fin']) ? $_GET['fin'] : ''
));

        header("Location: ?$queryParams");
        exit;
    }
}

// Filtrage
$debut = isset($_GET['debut']) ? $_GET['debut'] : '';
$fin = isset($_GET['fin']) ? $_GET['fin'] : '';
$params = array($etudiant['idEtudiant']);
$query = "SELECT * FROM absence WHERE idEtudiant = ?";

if ($debut && $fin) {
    $query .= " AND dateAbsence BETWEEN ? AND ?";
    $params[] = $debut;
    $params[] = $fin;
}

$query .= " ORDER BY dateAbsence DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$absences = $stmt->fetchAll();

// Récupérer la classe
$stmtClasse = $pdo->prepare("SELECT nomClasse FROM classe WHERE idClasse = ?");
$stmtClasse->execute(array($etudiant['idClasse']));
$classe = $stmtClasse->fetch();

// Export CSV
if (isset($_GET['export']) && $absences) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=mes_absences.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Date', 'Statut', 'Justification', 'Matière'));
    foreach ($absences as $a) {
        fputcsv($output, array($a['dateAbsence'], $a['statut'], $a['justification'], $a['Matiere']));
    }
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>AbsenceTrack - Étudiant</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8; margin: 0; padding: 0;
        }
        header {
            background: #2980b9; color: white;
            padding: 20px; text-align: center;
        }
        .container {
            max-width: 900px; margin: 30px auto;
            background: #fff; padding: 30px;
            border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logout {
            float: right; color: white;
            background: #e74c3c;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            margin-top: -50px;
        }
        .logout:hover { background: #c0392b; }
        h1 { margin-top: 0; }
        .info { margin: 10px 0; }
        .filter-form {
            display: flex; flex-wrap: wrap;
            gap: 15px; align-items: center;
            margin: 20px 0;
        }
        .filter-form input[type="date"],
        .filter-form button, .filter-form a {
            padding: 8px 12px; border-radius: 4px;
            font-size: 14px;
        }
        .filter-form button,
        .filter-form a {
            background: #2980b9;
            color: white; border: none;
            text-decoration: none;
            cursor: pointer;
        }
        .filter-form button:hover,
        .filter-form a:hover {
            background: #3498db;
        }
        table {
            width: 100%; border-collapse: collapse;
            margin-top: 15px;
            background: white;
        }
        th, td {
            padding: 12px; text-align: left;
            border-bottom: 1px solid #ccc;
        }
        th {
            background: #3498db;
            color: white;
        }
        tr:hover { background-color: #f9f9f9; }
        .no-absence {
            margin-top: 20px; font-style: italic;
            color: #888;
        }
        input[type="text"] {
            width: 100%; padding: 5px;
        }
        button[name="justifier"] {
            background: #27ae60;
        }
        button[name="justifier"]:hover {
            background: #2ecc71;
        }
    </style>
</head>
<body>

<header>
    <h1>AbsenceTrack - Espace Étudiant</h1>
    <a class="logout" href="logout.php">Se déconnecter</a>
</header>

<div class="container">
    <h2>Bienvenue <?php echo htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?></h2>
    <p class="info">Email : <?php echo htmlspecialchars($etudiant['email']); ?></p>
    <p class="info">Classe : <?php echo isset($classe['nomClasse']) ? htmlspecialchars($classe['nomClasse']) : 'Inconnue'; ?></p>

    <form class="filter-form" method="get" action="">
        <label for="debut">Du :</label>
        <input type="date" id="debut" name="debut" value="<?php echo htmlspecialchars($debut); ?>" required>

        <label for="fin">Au :</label>
        <input type="date" id="fin" name="fin" value="<?php echo htmlspecialchars($fin); ?>" required>

        <button type="submit">Filtrer</button>

        <?php if ($debut && $fin && count($absences) > 0): ?>
            <a href="?debut=<?php echo $debut; ?>&fin=<?php echo $fin; ?>&export=1">Exporter en Excel</a>
        <?php endif; ?>
    </form>

    <h3>Mes absences</h3>

    <?php if (count($absences) > 0): ?>
        <form method="post" action="">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Matière</th>
                        <th>Justification</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($absences as $a): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($a['dateAbsence']); ?></td>
                            <td><?php echo htmlspecialchars($a['statut']); ?></td>
                            <td><?php echo htmlspecialchars($a['Matiere']); ?></td>
                            <td>
                                <?php if ($a['statut'] === 'Absent' && empty($a['justification'])): ?>
                                    <input type="text" name="justifications[<?php echo $a['idAbsence']; ?>]" placeholder="Entrez la raison">
                                <?php else: ?>
                                    <?php echo htmlspecialchars($a['justification']); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($a['statut'] === 'Absent' && empty($a['justification'])): ?>
                                    <button type="submit" name="justifier" value="<?php echo $a['idAbsence']; ?>">Justifier</button>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php else: ?>
        <p class="no-absence">Aucune absence trouvée pour cette période.</p>
    <?php endif; ?>
</div>

</body>
</html>
