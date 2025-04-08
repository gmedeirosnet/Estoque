#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    CREATE USER estoque WITH PASSWORD 'suasenha';
    CREATE DATABASE estoque;
    GRANT ALL PRIVILEGES ON DATABASE estoque TO estoque;
    \c estoque
    GRANT ALL ON SCHEMA public TO estoque;
EOSQL
