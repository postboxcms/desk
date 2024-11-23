#!/usr/bin/env bash

/usr/bin/mariadb --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS postbox;
    GRANT ALL PRIVILEGES ON \`postbox%\`.* TO '$MYSQL_USER'@'%';
EOSQL
