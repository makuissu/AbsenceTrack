<?php
// Page d'accueil sans traitement PHP pour l'instant
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Accueil - AbsenceTrack IME</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    :root {
      --ime-blue: #2980b9;
      --ime-red: #EF4135;
      --white: #ffffff;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
    }

    header {
      background-color: var(--ime-blue);
      padding: 20px 0;
      color: white;
    }

    .logo-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 30px;
    }

    .logo-bar img {
      height: 60px;
    }

    .hero {
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('st.jpg') center/cover no-repeat;
      color: white;
      text-align: center;
      padding: 100px 20px;
    }

    .hero h1 {
      font-size: 48px;
      font-weight: bold;
    }

    .hero p {
      font-size: 20px;
      margin-top: 10px;
    }

    .btn-login {
      margin-top: 30px;
      padding: 12px 30px;
      font-size: 18px;
      font-weight: bold;
      background-color: var(--ime-red);
      color: white;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .btn-login:hover {
      background-color: #c0392b;
    }

    .features {
      padding: 60px 20px;
    }

    .features h2 {
      color: var(--ime-blue);
      margin-bottom: 40px;
    }

    .card-feature {
      border: 2px solid var(--ime-blue);
      border-radius: 12px;
      background-color: #fff;
      padding: 25px;
      transition: transform 0.3s;
      height: 100%;
    }

    .card-feature:hover {
      transform: translateY(-5px);
    }

    footer {
      background-color: var(--ime-blue);
      color: white;
      padding: 25px 0;
      text-align: center;
    }

    footer a {
      color: white;
      margin: 0 10px;
      font-size: 20px;
    }
  </style>
</head>
<body>

  <!-- En-tête avec logos -->
  <header>
    <div class="logo-bar">
      <img src="logo.jpg" alt="Logo IME" />
      <img src="logoabs.png" alt="Logo AbsenceTrack" />
    </div>
  </header>

  <!-- Section principale -->
  <section class="hero">
    <h1>Bienvenue sur AbsenceTrack</h1>
    <p>Suivi des présences en temps réel - Institut de Management et d’Entrepreneuriat</p>
    <a href="login.php" class="btn-login">
      <i class="fas fa-sign-in-alt me-2"></i> Se connecter
    </a>
  </section>

  <!-- Fonctionnalités -->
  <section class="features container text-center">
    <h2>Fonctionnalités principales</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card-feature h-100">
          <i class="fas fa-users fs-2 ime-text-primary mb-3"></i>
          <h5>Gestion des rôles</h5>
          <ul class="list-unstyled mt-2">
            <li>Administrateurs</li>
            <li>Enseignants</li>
            <li>Étudiants</li>
            <li>Parents</li>
          </ul>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card-feature h-100">
          <i class="fas fa-chart-bar fs-2 ime-text-primary mb-3"></i>
          <h5>Suivi & statistiques</h5>
          <ul class="list-unstyled mt-2">
            <li>Historique complet</li>
            <li>Export CSV</li>
            <li>Graphiques</li>
          </ul>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card-feature h-100">
          <i class="fas fa-envelope fs-2 ime-text-primary mb-3"></i>
          <h5>Notifications</h5>
          <ul class="list-unstyled mt-2">
            <li>Alertes email</li>
            <li>Notifications parents</li>
            <li>Retards signalés</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Pied de page -->
  <footer>
    <p>&copy; 2025 Institut de Management et d'Entrepreneuriat</p>
    <p>Système AbsenceTrack | Version 1.0</p>
    <div>
      <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
      <a href="https://tiktok.com" target="_blank"><i class="fab fa-tiktok"></i></a>
    </div>
  </footer>

</body>
</html>
