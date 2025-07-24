<?php

session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header('Location: ../login.php');
    // exit;
}

// die;
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
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
$message = "";

// GESTIONS DES FORMULAIRES (identique aux versions précédentes, voir messages plus haut)

if ($action == 'add_user' && $_SERVER['REQUEST_METHOD'] == 'POST') {
   $login = isset($_POST['login']) ? $_POST['login'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$role = isset($_POST['role']) ? $_POST['role'] : '';

    if (!empty($login) && ($email) && !empty($password) && !empty($role)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateur (login, emailadmin, motDePasse, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(array($login, $email, $hash, $role));
        $message = "Utilisateur ajouté avec succès.";
    } else {
        $message = "Tous les champs sont requis.";
    }
}


if ($action == 'add_class' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomClasse = $_POST['class_name'];
    if (!empty($nomClasse)) {
        $stmt = $pdo->prepare("INSERT INTO classe (nomClasse) VALUES (?)");
        $stmt->execute(array($nomClasse));
        $message = "Classe ajoutée avec succès.";
    } else {
        $message = "Le nom de la classe est requis.";
    }
}

if ($action == 'add_student' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $loginParent = $_POST['login_parent'];
    $idClasse = $_POST['id_classe'];

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($login) && !empty($password) && !empty($loginParent) && !empty($idClasse)) {
        // Insertion dans la table etudiant
        $stmt = $pdo->prepare("INSERT INTO etudiant (nom, prenom, email, loginParent, idClasse, login) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(array($nom, $prenom, $email, $loginParent, $idClasse, $login));

        // Insertion dans la table utilisateur
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt2 = $pdo->prepare("INSERT INTO utilisateur (login, motDePasse, role) VALUES (?, ?, 'Etudiant')");
        $stmt2->execute(array($login, $hash));

        $message = "Étudiant ajouté avec succès.";
    } else {
        $message = "Tous les champs sont requis.";
    }
}


if ($action == 'get_stats' && isset($_GET['class_id'])) {
    header('Content-Type: application/json');
    $idClasse = $_GET['class_id'];
    $stmt = $pdo->prepare("
        SELECT CONCAT(e.nom, ' ', e.prenom) AS etudiant, COUNT(a.idAbsence) AS count
        FROM absence a
        JOIN etudiant e ON a.idEtudiant = e.idEtudiant
        WHERE e.idClasse = ?
        GROUP BY e.idEtudiant
    ");
    $stmt->execute(array($idClasse));
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($action == 'export_absences' && isset($_GET['class_id'])) {
    $idClasse = $_GET['class_id'];
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="absences_classe_' . $idClasse . '.csv"');
    echo "\xEF\xBB\xBF";
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Nom', 'Prénom', 'Date', 'Statut'), ';');
    $stmt = $pdo->prepare("
        SELECT e.nom, e.prenom, a.dateAbsence, a.statut
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




$users = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider'])) {
    $idAbsence = key($_POST['valider']);

    // Appliquer la mise à jour
    $stmt = $pdo->prepare("UPDATE absence SET statut = 'Absence Justifié', justificationValidee = 1 WHERE idAbsence = ?");
    $stmt->execute(array($idAbsence));

    // Récupérer l'idClasse via une requête (car il n’est pas dans $_GET lors du POST)
    $stmt2 = $pdo->prepare("SELECT e.idClasse FROM absence a JOIN etudiant e ON a.idEtudiant = e.idEtudiant WHERE a.idAbsence = ?");
    $stmt2->execute(array($idAbsence));
    $result = $stmt2->fetch();

    if ($result) {
        $idClasse = $result['idClasse'];
        header("Location: ?action=absences_by_class&class_id=" . urlencode($idClasse));
        exit;
    }
}



?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>AbsenceTrack - Admin</title>
   

    <style>
/* Reset + Base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', 'Roboto', sans-serif;
  background: #f4f7fa;
  color: #2c3e50;
  min-height: 100vh;
}

/* Header */
header {
  background: #2980b9;
  color: #fff;
  padding: 18px 30px;
  text-align: center;
  font-size: 24px;
  font-weight: bold;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
/* Nouveau style pour le contenu du header */
.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between; /* texte à droite, logo à gauche */
}

/* Tu peux inverser en row-reverse si tu veux logo à droite */
header h1 {
    font-size: 50pxpx;
    font-weight: 600;
    margin-left: 30px;
}

/* Logo */
.logo {
    height: 50px;
    width: auto;
}


/* Sidebar Navigation */
nav {
  background: #ecf4fc;
  padding: 20px;
  width: 240px;
  min-height: calc(100vh - 60px);
  float: left;
  border-right: 1px solid #dce3ec;
}

nav a {
  display: block;
  color: #2c3e50;
  font-weight: 500;
  padding: 12px 16px;
  margin-bottom: 10px;
  border-radius: 6px;
  text-decoration: none;
  transition: all 0.2s;
}

nav a:hover, nav a.active {
  background: #d0e6f9;
  color: #1a2937;
}

/* Container */
.container {
  margin-left: 260px;
  padding: 40px;
  background: #f4f7fa;
  min-height: calc(100vh - 60px);
}

/* Forms */
form label {
  font-weight: bold;
  margin-top: 15px;
  display: block;
}

form input,
form select,
form textarea {
  width: 100%;
  padding: 10px 12px;
  margin-top: 8px;
  margin-bottom: 18px;
  border: 1px solid #c8d6e5;
  border-radius: 6px;
  font-size: 15px;
  background-color: #f9fbfd;
  transition: border-color 0.3s ease;
}

form input:focus,
form select:focus,
form textarea:focus {
  border-color: #2980b9;
  outline: none;
}

/* Buttons */
button, .btn {
  padding: 10px 20px;
  background-color: #2980b9;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 15px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.3s;
}

button:hover, .btn:hover {
  background-color: #216292;
}

/* Tables */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background-color: white;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  border-radius: 8px;
  overflow: hidden;
}

table th, table td {
  padding: 14px;
  text-align: left;
  border-bottom: 1px solid #e0e6ed;
}

table thead {
  background-color: #2980b9;
  color: white;
}

table tr:hover {
  background-color: #f0f6fb;
}

/* Messages */
.message {
  padding: 14px;
  margin-bottom: 20px;
  border-left: 5px solid #2980b9;
  background: #d9ecf9;
  color: #1e3e5e;
  border-radius: 6px;
}

.message.error {
  border-left-color: #d9534f;
  background: #f8d7da;
  color: #842029;
}

/* Responsive */
@media (max-width: 768px) {
  nav {
    width: 100%;
    float: none;
    border-right: none;
    border-bottom: 1px solid #dce3ec;
  }

  .container {
    margin-left: 0;
    padding: 20px;
  }

  table th, table td {
    font-size: 13px;
  }
}
#absenceChart {
  max-width: 800px;
  max-height: 500px;
  margin: 30px auto;
  display: block;
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
</style>
</head>
<body>

<header>
  <div class="header-content">
    <img src="logo.jpg" alt="Logo" class="logo">
    <h1>AbsenceTrack - Interface Administrateur</h1>
    <a class="logout" href="logout.php">Se déconnecter</a>
  </div>
</header>


<nav>
    <a href="?action=dashboard">Tableau de bord</a>
    <a href="?action=add_user_form">Ajouter un utilisateur</a>
    <a href="?action=add_student_form">Ajouter un étudiant</a>
    <a href="?action=add_class_form">Ajouter une classe</a>
    <a href="?action=view_users">Voir les utilisateurs</a>
    <a href="?action=absences_by_class">Absences par classe</a>
    <a href="?action=envoyerMessage">Envoyer un email à un parent</a>



</nav>

<div class="container">

<?php if (!empty($message)): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if ($action == 'dashboard'): ?>
    <h2>Statistiques des absences</h2>
    <form method="GET">
        <input type="hidden" name="action" value="dashboard">
        <label>Classe :</label>
        <select name="class_id" required>
            <option value=""> Sélectionner une classe</option>
            <?php
            $classes = $pdo->query("SELECT idClasse, nomClasse FROM classe");
            while ($classe = $classes->fetch()):
                $selected = (isset($_GET['class_id']) && $_GET['class_id'] == $classe['idClasse']) ? 'selected' : '';
                echo "<option value='".$classe['idClasse']."' $selected>".htmlspecialchars($classe['nomClasse'])."</option>";
            endwhile;
            ?>
        </select>
        <label>Type de graphique :</label>
        <select name="chart_type" required>
            <option value="pie" <?php if (isset($_GET['chart_type']) && $_GET['chart_type'] == 'pie') echo 'selected'; ?>>Camembert</option>
            <option value="bar" <?php if (isset($_GET['chart_type']) && $_GET['chart_type'] == 'bar') echo 'selected'; ?>>Histogramme</option>
        </select>
        <button type="submit">Afficher</button>
    </form>

    <?php if (!empty($_GET['class_id']) && !empty($_GET['chart_type'])): ?>
        <canvas id="absenceChart" width="3200" height="2400"></canvas>


        

        <script>
            fetch("?action=get_stats&class_id=<?php echo $_GET['class_id']; ?>")
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.etudiant);
                    const counts = data.map(item => item.count);
                    new Chart(document.getElementById("absenceChart"), {
                        type: "<?php echo $_GET['chart_type']; ?>",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Nombre d'absences",
                                data: counts,
                                backgroundColor: ['#e74c3c','#3498db','#2ecc71','#f1c40f','#9b59b6','#1abc9c']
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: <?php echo $_GET['chart_type'] == 'bar' ? '{ y: { beginAtZero: true } }' : '{}'; ?>
                        }
                    });
                });
        </script>
    <?php endif; ?>
<?php endif; ?>

<!-- FORMULAIRES RESTANTS CI-DESSOUS (utilisateur, classe, étudiant, utilisateurs) -->
<?php if ($action == 'add_user_form'): ?>
    <h2>Ajouter un utilisateur</h2>
    <form method="POST" action="?action=add_user">
        <label>Login</label>
        <input type="text" name="login" required>
        <label>Mot de passe</label>
        <input type="password" name="password" required>
        <label>Addresse Mail</label>
        <input type="email" name="email" required>
        <label>Rôle</label>
        <select name="role" required>
            <option value="Admin">Admin</option>
            <option value="Enseignant">Enseignant</option>
            <option value="Parent">Parent</option>
        </select>
        <button type="submit">Ajouter</button>
    </form>
<?php endif; ?>

<?php if ($action == 'add_class_form'): ?>
    <h2>Ajouter une classe</h2>
    <form method="POST" action="?action=add_class">
        <label>Nom de la classe</label>
        <input type="text" name="class_name" required>
        <button type="submit">Ajouter</button>
    </form>
<?php endif; ?>

<?php if ($action == 'add_student_form'): ?>
    <h2>Ajouter un étudiant</h2>
    <form method="POST" action="?action=add_student">
        <label>Nom</label>
        <input type="text" name="nom" required>

        <label>Prénom</label>
        <input type="text" name="prenom" required>

        <label>Email du parent</label>
        <input type="email" name="email" required>

        <label>Login</label>
        <input type="text" name="login" required>

        <label>Mot de passe</label>
        <input type="password" name="password" required>

        <label>Login du parent</label>
        <select name="login_parent" required>
            <option value=""> Sélectionner un parent</option>
            <?php
            $parents = $pdo->query("SELECT login FROM utilisateur WHERE role = 'Parent'");
            while ($parent = $parents->fetch()) {
                echo "<option value='" . htmlspecialchars($parent['login']) . "'>" . htmlspecialchars($parent['login']) . "</option>";
            }
            ?>
        </select>

        <label>Classe</label>
        <select name="id_classe" required>
            <?php
            $stmt = $pdo->query("SELECT idClasse, nomClasse FROM classe");
            while ($row = $stmt->fetch()) {
                echo "<option value='" . htmlspecialchars($row['idClasse']) . "'>" . htmlspecialchars($row['nomClasse']) . "</option>";
            }
            ?>
        </select>

        <button type="submit">Ajouter</button>
    </form>
<?php endif; ?>

<?php if ($action == 'view_users'): ?>
    <h2>Liste des utilisateurs</h2>
    <table>
        <thead><tr><th>Login</th><th>Rôle</th></tr></thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['login']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
 <?php if ($action == 'absences_by_class'): ?>
        <h2>Absences par classe</h2>
        <form method="GET" action="">
            <input type="hidden" name="action" value="absences_by_class">
            <label>Choisir une classe :</label>
            <select name="class_id" onchange="this.form.submit()" required>
                <option value="">Sélectionnez une classe</option>
                <?php
                $stmt = $pdo->query("SELECT idClasse, nomClasse FROM classe");
                while ($classe = $stmt->fetch()) {
                    $selected = (isset($_GET['class_id']) && $_GET['class_id'] == $classe['idClasse']) ? 'selected' : '';
                    echo "<option value='" . $classe['idClasse'] . "' $selected>" . htmlspecialchars($classe['nomClasse']) . "</option>";
                }
                ?>
            </select>
        </form>
        <?php
if (isset($_GET['class_id']) && $_GET['class_id'] !== ''):
    $idClasse = $_GET['class_id'];

    // Récupérer la période (si présente)
    $dateDebut = isset($_GET['date_debut']) ? $_GET['date_debut'] : '';
    $dateFin = isset($_GET['date_fin']) ? $_GET['date_fin'] : '';

    // Formulaire de filtrage par période
    ?>
    <form method="get" style="margin-bottom: 15px;">
    <input type="hidden" name="action" value="absences_by_class">
    <input type="hidden" name="class_id" value="<?= htmlspecialchars($idClasse) ?>">

    <label for="date_debut">Du :</label>
    <input type="date" name="date_debut" value="<?= htmlspecialchars($dateDebut) ?>">

    <label for="date_fin">Au :</label>
    <input type="date" name="date_fin" value="<?= htmlspecialchars($dateFin) ?>">

        <?php
        $matieres = $pdo->query("SELECT codeMatiere, libelle FROM matiere");
        while ($matiere = $matieres->fetch()) {
            $selected = (isset($_GET['code_matiere']) && $_GET['code_matiere'] == $matiere['codeMatiere']) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($matiere['codeMatiere']) . "' $selected>" . htmlspecialchars($matiere['libelle']) . "</option>";
        }
        ?>
    </select>

    <button type="submit">Filtrer</button>
</form>


    <a href="?action=export_absences&class_id=<?= $idClasse ?>&date_debut=<?= urlencode($dateDebut) ?>&date_fin=<?= urlencode($dateFin) ?>">
        <button style="margin-top:10px;">⬇️ Exporter en CSV</button>
    </a>

    <?php
    // Construction de la requête
   $query = "
    SELECT e.nom, e.prenom, a.dateAbsence, a.statut, m.libelle, a.justification, a.justificationValidee, a.idAbsence
    FROM absence a
    JOIN etudiant e ON a.idEtudiant = e.idEtudiant
    LEFT JOIN matiere m ON a.codeMatiere = m.codeMatiere
    WHERE e.idClasse = ?
";

$params = [$idClasse];

// Filtrage par date
if (!empty($dateDebut) && !empty($dateFin)) {
    $query .= " AND a.dateAbsence BETWEEN ? AND ?";
    $params[] = $dateDebut;
    $params[] = $dateFin;
}

// Filtrage par matière
$codeMatiere = isset($_GET['code_matiere']) ? $_GET['code_matiere'] : '';
if (!empty($codeMatiere)) {
    $query .= " AND a.codeMatiere = ?";
    $params[] = $codeMatiere;
}

$query .= " ORDER BY a.dateAbsence DESC";


    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $absences = $stmt->fetchAll();
    ?>

   <?php if (count($absences) > 0): ?>
    <form method="post">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Matière</th>
                    <th>Justification</th>
                    <th>Valider</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absences as $abs): ?>
                    <tr>
                        <td><?= htmlspecialchars($abs['nom']) ?></td>
                        <td><?= htmlspecialchars($abs['prenom']) ?></td>
                        <td><?= htmlspecialchars($abs['dateAbsence']) ?></td>
                        <td style="color:
                        <?= ($abs['statut'] == 'Absence justifiée') ? 'green' :
                        (($abs['statut'] == 'Absent') ? 'red' :
                        (($abs['statut'] == 'Présent') ? 'blue' : 'black')) ?>;">
                        <?= htmlspecialchars($abs['statut']) ?></td>

                        <td><?= htmlspecialchars($abs['libelle']) ?></td>
                        <td><?= nl2br(htmlspecialchars($abs['justification'])) ?></td>
                        <td>
                            <?php if ($abs['justification'] && !$abs['justificationValidee']): ?>
                                <button type="submit" name="valider[<?= $abs['idAbsence'] ?>]">Valider</button>
                            <?php elseif ($abs['justificationValidee']): ?>
                                Déjà validée
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
        <p>Aucune absence trouvée pour cette classe sur la période sélectionnée.</p>
    <?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php if ($action == 'envoyerMessage'): ?>
<h2>Envoyer un email</h2>
<form id="envoyerMessage" method="POST" name="envoyerMessage">

    <label>Adresse email du parent :</label>
    <select name="email_etudiant" onchange="document.getElementById('to_email').value=this.value">
        <option value="">Sélectionner dans la liste</option>
        <?php
        $stmt = $pdo->query("SELECT DISTINCT email, nom, prenom FROM etudiant WHERE email IS NOT NULL AND email != ''");
        while ($row = $stmt->fetch()) {
            $label = htmlspecialchars($row['nom'] . ' ' . $row['prenom'] . ' (' . $row['email'] . ')');
            echo "<option value='" . htmlspecialchars($row['email']) . "'>$label</option>";
        }
        ?>
    </select>

    <label>Ou saisissez une adresse manuellement :</label>
    <input type="email" name="to_email" id="to_email" placeholder="exemple@domaine.com" required>

    <label>Message :</label>
    <textarea name="message" rows="5" required></textarea>
    
   <button type="submit">Envoyer</button>


</form>
<?php endif; ?>


</div>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="https://cdn.emailjs.com/sdk/3.2.0/email.min.js"></script> -->
     <script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>

     <script>
    // Initialise EmailJS avec ton public key
    // (function(){
    //   emailjs.init("aFCq4eGQZUEIkJyOZ"); // Exemple : "sOe9E1aZ5HxYf"
    // })();

    // function envoyerMessage() {
    //   const destinataire = document.getElementById("email").value;
    //   const nom = document.getElementById("nom").value;
    //   const code = Math.floor(100000 + Math.random() * 900000);

    //   const params = {
    //     to_name: nom,
    //     code: code,
    //     reply_to: destinataire
    //   };
    emailjs.init("aFCq4eGQZUEIkJyOZ");

    document.getElementById("envoyerMessage").addEventListener("submit", function(e){
        e.preventDefault();

     
        emailjs.sendForm("service_10ne3tj", "template_fiffa2i", this)
  .then(function(response) {
    console.log("SUCCESS!", response.status, response.text);
    alert("Email envoyé avec succès !");
  }, function(error) {
    console.error("FAILED...", error); // 👈 important pour diagnostiquer
    alert("Erreur d'envoi : " + error.text);
  });

    });
  </script>
</body>
</html>
