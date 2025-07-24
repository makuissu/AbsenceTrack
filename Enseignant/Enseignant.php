<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Enseignant') {
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
    PDO::ATTR_EMULATE_PREPARES => false,
);

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$idEnseignant = 1; // Simuler un enseignant connecté
$action = isset($_GET['action']) ? $_GET['action'] : 'classes';
$message = "";

// Enregistrement des absences / présences
if ($action === 'mark_absence' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    $matiere = isset($_POST['matiere']) ? trim($_POST['matiere']) : null;
    $statuses = isset($_POST['statut']) ? $_POST['statut'] : [];

    if ($date && $matiere && !empty($statuses)) {
        // Pour chaque étudiant on enregistre le statut s'il est défini
        $stmt = $pdo->prepare("INSERT INTO absence (idEtudiant, dateAbsence, statut, Matiere) VALUES (?, ?, ?, ?)");
        foreach ($statuses as $idEtudiant => $statut) {
            // Statut doit être non vide et valide
            if (in_array($statut, ['Présent', 'Absent', 'Retard'])) {
                $stmt->execute([$idEtudiant, $date, $statut, $matiere]);
            }
        }
        $message = "Présences et absences enregistrées.";
    } else {
        $message = "Tous les champs (date, matière, statuts) sont requis.";
    }
}

// Export CSV encodé UTF-8 BOM pour Excel
if ($action === 'export_csv' && isset($_GET['class_id'])) {
    $idClasse = $_GET['class_id'];

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="absences_classe_' . $idClasse . '.csv"');

    echo "\xEF\xBB\xBF"; // BOM

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Nom', 'Prénom', 'Date', 'Statut', 'Matière'), ';');

    $stmt = $pdo->prepare("
        SELECT e.nom, e.prenom, a.dateAbsence, a.statut, a.Matiere
        FROM absence a
        JOIN etudiant e ON a.idEtudiant = e.idEtudiant
        WHERE e.idClasse = ?
        ORDER BY a.dateAbsence DESC
    ");
    $stmt->execute(array($idClasse));

    while ($row = $stmt->fetch()) {
        fputcsv($output, $row, ';');
    }

    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>AbsenceTrack - Interface Enseignant</title>
    <a class="logout" href="../logout.php">Se déconnecter</a>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; margin: 0; padding: 0; }
        header { background:#2980b9; color: white; padding: 20px; text-align: center; }
        nav { background:#2c3e50 ; padding: 10px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        .container { max-width: 1000px; margin: 30px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        input[type="date"], input[type="text"], select { padding: 7px; width: 100%; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 20px; background: #2980b9; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #3498db; }
        .message { background: #dff0d8; padding: 10px; color: #3c763d; margin-bottom: 20px; }
        h2, h3 { color: #2c3e50; }
        /* Style des radios */
        .statut-options label {
            margin-right: 15px;
            cursor: pointer;
        }
        .statut-options input[type="radio"] {
            margin-right: 5px;
            cursor: pointer;
        }
        .logout {
            float: right;
            color: white;
            background: #e74c3c;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            margin-top: -50px;
        }
        .logout:hover {
            background: #c0392b;
        }
        .class-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.class-card {
    display: block;
    background: #ecf0f1;
    color: #2c3e50;
    padding: 15px 20px;
    text-align: center;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.class-card:hover {
    background: #3498db;
    color: white;
    transform: translateY(-2px);
}

    </style>
</head>
<body>

<header>
    <h1>AbsenceTrack - Interface Enseignant</h1>
    <a class="logout" href="logout.php">Se déconnecter</a>
</header>

<nav>
    <a href="?action=classes">Mes classes</a>
    <a href="?action=absences">Mes absences</a>
</nav>

<div class="container">
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($action === 'classes'): ?>
        <h2>Liste des classes</h2>
        <div class="class-list">
    <?php
    $stmt = $pdo->query("SELECT idClasse, nomClasse FROM classe");
    while ($row = $stmt->fetch()) {
        echo "<a class='class-card' href='?action=students&class_id=" . $row['idClasse'] . "'>" . htmlspecialchars($row['nomClasse']) . "</a>";
    }
    ?>
</div>

    <?php endif; ?>

    <?php if ($action === 'students' && isset($_GET['class_id'])): ?>
        <h2>Étudiants de la classe</h2>
        <?php
        $idClasse = $_GET['class_id'];
        $stmt = $pdo->prepare("SELECT * FROM etudiant WHERE idClasse = ?");
        $stmt->execute(array($idClasse));
        $students = $stmt->fetchAll();

        if (count($students) > 0): ?>
            <form method="POST" action="?action=mark_absence">
                <label>Date :</label>
                <input type="date" name="date" required>

                <label>Matière :</label>
                <input type="text" name="matiere" placeholder="Ex: Mathématiques" required>

                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Présent</th>
                            <th>Absent</th>
                            <th>Retard</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['nom']); ?></td>
                                <td><?php echo htmlspecialchars($student['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td class="statut-options">
                                    <label><input type="radio" name="statut[<?php echo $student['idEtudiant']; ?>]" value="Présent" required></label>
                                </td>
                                <td class="statut-options">
                                    <label><input type="radio" name="statut[<?php echo $student['idEtudiant']; ?>]" value="Absent" required></label>
                                </td>
                                <td class="statut-options">
                                    <label><input type="radio" name="statut[<?php echo $student['idEtudiant']; ?>]" value="Retard" required></label>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit">Enregistrer les présences</button>
            </form>
        <?php else: ?>
            <p>Aucun étudiant trouvé.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($action === 'absences'): ?>
        <h2>Filtrer les absences par classe</h2>
        <form method="GET" action="">
            <input type="hidden" name="action" value="absences">
            <label for="class_id">Classe :</label>
            <select name="class_id" id="class_id" onchange="this.form.submit()">
                <option value="">-- Sélectionnez une classe --</option>
                <?php
                $stmt = $pdo->query("SELECT idClasse, nomClasse FROM classe");
                while ($classe = $stmt->fetch()) {
                    $selected = (isset($_GET['class_id']) && $_GET['class_id'] == $classe['idClasse']) ? 'selected' : '';
                    echo "<option value='" . $classe['idClasse'] . "' $selected>" . htmlspecialchars($classe['nomClasse']) . "</option>";
                }
                ?>
            </select>
        </form>

        <?php if (isset($_GET['class_id']) && $_GET['class_id'] !== ''): ?>
            <h3>Absences de la classe sélectionnée</h3>
            <a href="?action=export_csv&class_id=<?php echo $_GET['class_id']; ?>">
                <button>Exporter en CSV</button>
            </a>
            <?php
            $idClasse = $_GET['class_id'];
            $stmt = $pdo->prepare("
                SELECT e.nom, e.prenom, a.dateAbsence, a.statut, a.Matiere
                FROM absence a
                JOIN etudiant e ON a.idEtudiant = e.idEtudiant
                WHERE e.idClasse = ?
                ORDER BY a.dateAbsence DESC
            ");
            $stmt->execute(array($idClasse));
            $absences = $stmt->fetchAll();

            if (count($absences) > 0): ?>
                <table>
                    <thead>
                        <tr><th>Nom</th><th>Prénom</th><th>Date</th><th>Statut</th><th>Matière</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($absences as $abs): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($abs['nom']); ?></td>
                                <td><?php echo htmlspecialchars($abs['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($abs['dateAbsence']); ?></td>
                                <td><?php echo htmlspecialchars($abs['statut']); ?></td>
                                <td><?php echo htmlspecialchars($abs['Matiere']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune absence enregistrée pour cette classe.</p>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
