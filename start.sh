#!/bin/bash

echo "DEBUG: MYSQLHOST=$MYSQLHOST"
echo "DEBUG: MYSQLUSER=$MYSQLUSER"
echo "DEBUG: MYSQLDATABASE=$MYSQLDATABASE"
echo "DEBUG: MYSQLPORT=$MYSQLPORT"

php -r "
\$host   = getenv('MYSQLHOST');
\$user   = getenv('MYSQLUSER');
\$pass   = getenv('MYSQLPASSWORD');
\$dbname = getenv('MYSQLDATABASE');  // 'railway' sur Railway
\$port   = getenv('MYSQLPORT') ?: '3306';

echo \"PHP: Connecting to host=\$host, port=\$port, db=\$dbname\n\";

// Attendre que MySQL soit prêt
\$pdo = null;
for (\$i = 0; \$i < 30; \$i++) {
    try {
        \$pdo = new PDO(\"mysql:host=\$host;port=\$port;dbname=\$dbname\", \$user, \$pass);
        echo \"Connecté à MySQL !\n\";
        break;
    } catch (PDOException \$e) {
        echo \"Tentative \$i : \" . \$e->getMessage() . \"\n\";
        sleep(1);
    }
}

if (!\$pdo) { die(\"Impossible de se connecter à MySQL après 30 tentatives.\n\"); }

// Vérifier si les tables existent déjà
\$stmt = \$pdo->query(\"SHOW TABLES LIKE 'utilisateur'\");
if (\$stmt->rowCount() === 0) {
    echo \"Import du schéma SQL...\n\";
    \$sql = file_get_contents('/app/absencetrack(1).sql');
    \$pdo->exec(\$sql);
    echo \"Base de données importée avec succès !\n\";
} else {
    echo \"Tables déjà présentes, import ignoré.\n\";
}
"

php -S 0.0.0.0:8080 -t . router.php
