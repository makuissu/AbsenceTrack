<?php
session_start();

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

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    var_dump($_POST);

    $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $motDePasse = isset($_POST['motDePasse']) ? $_POST['motDePasse'] : '';

    if ($login !== '' && $motDePasse !== '') {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();
        
        var_dump($user);

        if ($user && password_verify($motDePasse, $user['motDePasse'])) {
            // Authentification réussie
            $_SESSION['user'] = [
                'login' => $user['login'],
                'role' => $user['role']
            ];

            var_dump($_SESSION);
            // die;

            if ($user['role'] === 'Admin') {
                header('Location: Admin/admin.php');
                exit;
            } elseif ($user['role'] === 'Enseignant') {
                header('Location: Enseignant/Enseignant.php');
                exit;
            } elseif ($user['role'] === 'Etudiant') {
                header('Location: Etudiant/Interfaceetudiant.php');
                exit;
            }elseif ($user['role'] === 'Parent') {
                header('Location: Parent/Interfaceparent.php');
                exit;
            } else {
                $error = "Rôle inconnu.";
            }
        } else {
            $error = "Login ou mot de passe incorrect.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Connexion AbsenceTrack</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 40px; }
        form { background: white; padding: 20px; max-width: 400px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { display: block; margin-top: 15px; }
        input { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        button { margin-top: 20px; width: 100%; padding: 10px; background: #2980b9; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #3498db; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Connexion</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <label for="login">Login :</label>
        <input type="text" id="login" name="login" required>

        <label for="motDePasse">Mot de passe :</label>
        <input type="password" id="motDePasse" name="motDePasse" required>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
