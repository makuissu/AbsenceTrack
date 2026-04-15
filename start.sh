#!/bin/bash
php -r "
\$host = getenv('MYSQLHOST');
\$user = getenv('MYSQLUSER');
\$pass = getenv('MYSQLPASSWORD');
\$pdo = new PDO('mysql:host=' . \$host, \$user, \$pass);
\$pdo->exec('CREATE DATABASE IF NOT EXISTS absencetrack');
\$pdo = new PDO('mysql:host=' . \$host . ';dbname=absencetrack', \$user, \$pass);
\$sql = file_get_contents('absencetrack(1).sql');
\$pdo->exec(\$sql);
"
php -S 0.0.0.0:8080 -t . router.php
