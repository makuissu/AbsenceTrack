#!/bin/bash
echo "DEBUG: MYSQLHOST=$MYSQLHOST"
echo "DEBUG: MYSQLUSER=$MYSQLUSER"
echo "DEBUG: MYSQLPASSWORD=$MYSQLPASSWORD"

php -r "
\$host = getenv('MYSQLHOST');
\$user = getenv('MYSQLUSER');
\$pass = getenv('MYSQLPASSWORD');

echo \"PHP: Connecting to host=\$host, user=\$user\n\";

// Wait for MySQL to be ready
\$attempts = 0;
while (\$attempts < 30) {
    try {
        \$pdo = new PDO('mysql:host=' . \$host, \$user, \$pass);
        echo \"Connected!\n\";
        break;
    } catch (PDOException \$e) {
        \$attempts++;
        echo \"Attempt \$attempts: \" . \$e->getMessage() . \"\n\";
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
