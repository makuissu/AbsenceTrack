#!/bin/bash
mysql -h "$MYSQLHOST" -u "$MYSQLUSER" -p"$MYSQLPASSWORD" "$MYSQLDATABASE" < absencetrack\(1\).sql
