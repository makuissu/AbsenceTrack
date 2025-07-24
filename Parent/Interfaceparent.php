<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Parent') {
    header('Location: ../login.php');
    exit;
}

$host = 'localhost';
$db = 'absencetrack';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$loginParent = $_SESSION['user']['login'];

// Récupérer tous les enfants du parent
$stmt = $pdo->prepare("
    SELECT e.idEtudiant, e.nom, e.prenom, c.nomClasse
    FROM etudiant e
    JOIN classe c ON e.idClasse = c.idClasse
    WHERE e.loginParent = ?
");
$stmt->execute([$loginParent]);
$enfants = $stmt->fetchAll();

// Déterminer l'enfant sélectionné
$idEtudiantChoisi = isset($_POST['idEtudiant']) ? $_POST['idEtudiant'] : null;
$absences = [];

if ($idEtudiantChoisi) {
    // Récupérer les absences de l'enfant choisi
    $stmt = $pdo->prepare("
        SELECT 
            e.nom AS nomEtudiant, e.prenom AS prenomEtudiant, c.nomClasse,
            a.dateAbsence, a.statut, a.justification, m.libelle AS matiere
        FROM absence a
        JOIN etudiant e ON a.idEtudiant = e.idEtudiant
        JOIN classe c ON e.idClasse = c.idClasse
        LEFT JOIN matiere m ON a.codeMatiere = m.codeMatiere
        WHERE e.idEtudiant = ?
        ORDER BY a.dateAbsence DESC
    ");
    $stmt->execute([$idEtudiantChoisi]);
    $absences = $stmt->fetchAll();

    // Export Excel
    if (isset($_POST['export_excel'])) {
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=absences_enfant.xls");
        echo "\xEF\xBB\xBF";
        echo "Nom\tPrénom\tClasse\tDate\tMatière\tStatut\tJustification\n";
        foreach ($absences as $a) {
            echo "{$a['nomEtudiant']}\t{$a['prenomEtudiant']}\t{$a['nomClasse']}\t" .
                "{$a['dateAbsence']}\t" .
                ($a['matiere'] ?: '-') . "\t" .
                ($a['statut'] ?: '-') . "\t" .
                ($a['justification'] ?: '-') . "\n";
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>AbsenceTrack - Espace Parent</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7fa;
            color: #2c3e50;
            padding: 40px;
        }
        .container {
            background: white;
            max-width: 1100px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e6ed;
        }
        thead {
            background-color: #2980b9;
            color: white;
        }
        .btn, select {
            margin-top: 10px;
            padding: 10px 15px;
            font-size: 15px;
            border: none;
            border-radius: 6px;
        }
        .btn {
            background-color: #2980b9;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #216292;
        }
        select {
            background-color: #ecf0f1;
            margin-right: 10px;
        }
        .logout {
            float: right;
            color: white;
            background: #e74c3c;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        .logout:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="logout">Se déconnecter</a>
        <h2>Consulter les absences votre enfant</h2>

        <form method="POST">
            <label for="idEtudiant">Choisir le nom de l'enfant :</label>
            <select name="idEtudiant" id="idEtudiant" required onchange="this.form.submit()">
                <option value="">Sélectionner le nom de l'enfant</option>
                <?php foreach ($enfants as $enf): ?>
                    <option value="<?= $enf['idEtudiant'] ?>" <?= ($idEtudiantChoisi == $enf['idEtudiant']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($enf['prenom'] . ' ' . $enf['nom']) ?> (<?= htmlspecialchars($enf['nomClasse']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($idEtudiantChoisi): ?>
                <button type="submit" name="export_excel" class="btn"> Exporter en Excel</button>
            <?php endif; ?>
        </form>

        <?php if ($idEtudiantChoisi): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($absences) > 0): ?>
                        <?php foreach ($absences as $a): ?>
                            <tr>
                                <td><?= htmlspecialchars($a['dateAbsence']) ?></td>
                                <td><?= htmlspecialchars($a['statut'] ?: '—') ?></td>
                              
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4">Aucune absence enregistrée pour cet enfant.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
