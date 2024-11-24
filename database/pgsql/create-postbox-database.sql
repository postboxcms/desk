SELECT 'CREATE DATABASE postbox'
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'postbox')\gexec
