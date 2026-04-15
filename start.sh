#!/bin/bash
mysql -h "$MYSQLHOST" -u "$MYSQLUSER" -p"$MYSQLPASSWORD" "$MYSQLDATABASE" < "absencetrack(1).sql" 2>/dev/null || true
php -S 0.0.0.0:8080 -t . router.php
