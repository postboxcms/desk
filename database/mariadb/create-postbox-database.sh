#!/usr/bin/env bash

DB_NAME="${MYSQL_DATABASE:-postbox}"

/usr/bin/mariadb --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS ${DB_NAME};
    GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '$MYSQL_USER'@'%';
EOSQL
