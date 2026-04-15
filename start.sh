#!/bin/bash

echo "DEBUG: MYSQLHOST=$MYSQLHOST"
echo "DEBUG: MYSQLUSER=$MYSQLUSER"
echo "DEBUG: MYSQLDATABASE=$MYSQLDATABASE"
echo "DEBUG: MYSQLPORT=$MYSQLPORT"

# Attendre que MySQL soit prêt
echo "Attente de MySQL..."
for i in $(seq 1 30); do
    if mysql -h "$MYSQLHOST" -P "$MYSQLPORT" -u "$MYSQLUSER" -p"$MYSQLPASSWORD" -e "SELECT 1" "$MYSQLDATABASE" > /dev/null 2>&1; then
        echo "MySQL est prêt !"
        break
    fi
    echo "Tentative $i : MySQL pas encore prêt..."
    sleep 1
done

# Vérifier si les tables existent déjà
TABLE_EXISTS=$(mysql -h "$MYSQLHOST" -P "$MYSQLPORT" -u "$MYSQLUSER" -p"$MYSQLPASSWORD" "$MYSQLDATABASE" -e "SHOW TABLES LIKE 'utilisateur';" 2>/dev/null | wc -l)

if [ "$TABLE_EXISTS" -lt "2" ]; then
    echo "Import du schéma SQL en cours..."
    mysql -h "$MYSQLHOST" -P "$MYSQLPORT" -u "$MYSQLUSER" -p"$MYSQLPASSWORD" "$MYSQLDATABASE" < "/app/absencetrack(1).sql"
    echo "Base de données importée avec succès !"
else
    echo "Tables déjà présentes, import ignoré."
fi

php -S 0.0.0.0:8080 -t . router.php
