#!/bin/bash
php -r "
\$host = getenv('MYSQLHOST');
\$user = getenv('MYSQLUSER');
\$pass = getenv('MYSQLPASSWORD');

// Wait for MySQL to be ready
\$attempts = 0;
while (\$attempts < 30) {
    try {
        \$pdo = new PDO('mysql:host=' . \$host, \$user, \$pass);
        break;
    } catch (PDOException \$e) {
        \$attempts++;
        sleep(1);
    }
}

\$pdo = new PDO('mysql:host=' . \$host, \$user, \$pass);
\$pdo->exec('DROP DATABASE IF EXISTS absencetrack');
\$pdo->exec('CREATE DATABASE absencetrack');
\$pdo = new PDO('mysql:host=' . \$host . ';dbname=absencetrack', \$user, \$pass);
\$sql = file_get_contents('absencetrack(1).sql');
\$pdo->exec(\$sql);
"
php -S 0.0.0.0:8080 -t . router.php
