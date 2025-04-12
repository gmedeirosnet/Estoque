#!/bin/bash
set -e

# This script creates the database and necessary tables for the Estoque application

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    -- Ensure estoque user exists
    DO \$\$
    BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'estoque') THEN
            CREATE USER estoque WITH PASSWORD 'suasenha';
        END IF;
    END
    \$\$;

    -- Grant privileges
    GRANT ALL PRIVILEGES ON DATABASE estoque TO estoque;
    GRANT ALL PRIVILEGES ON DATABASE estoque TO admin;

    -- Switch to estoque database
    \c estoque;

    -- Grant schema privileges
    GRANT ALL ON SCHEMA public TO estoque;
    GRANT ALL ON SCHEMA public TO admin;

    -- Criação da tabela de Grupos de Pessoas
    CREATE TABLE IF NOT EXISTS grupos_pessoas (
      id SERIAL PRIMARY KEY,
      nome VARCHAR(100) NOT NULL,
      descricao TEXT
    );

    -- Inserir o grupo padrão "Usuários" se a tabela estiver vazia
    INSERT INTO grupos_pessoas (nome, descricao)
    SELECT 'Usuários', 'Grupo padrão de usuários'
    WHERE NOT EXISTS (SELECT 1 FROM grupos_pessoas);

    -- Criação da tabela de Pessoas
    CREATE TABLE IF NOT EXISTS pessoas (
      id SERIAL PRIMARY KEY,
      nome VARCHAR(100) NOT NULL,
      email VARCHAR(100),
      id_grupo_pessoa INTEGER REFERENCES grupos_pessoas(id) ON DELETE SET NULL,
      data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- Atualizar pessoas existentes para usar o grupo padrão, se necessário
    DO \$\$
    BEGIN
        IF EXISTS (
            SELECT 1 FROM pessoas
            WHERE id_grupo_pessoa IS NULL
        ) THEN
            UPDATE pessoas
            SET id_grupo_pessoa = (SELECT id FROM grupos_pessoas WHERE nome = 'Usuários')
            WHERE id_grupo_pessoa IS NULL;
        END IF;
    END
    \$\$;

    -- Criação da tabela de Fabricantes (substituindo a de Grupos)
    CREATE TABLE IF NOT EXISTS fabricantes (
      id SERIAL PRIMARY KEY,
      cnpj VARCHAR(18) UNIQUE NOT NULL,
      nome VARCHAR(100) NOT NULL,
      observacao TEXT,
      endereco VARCHAR(255),
      email VARCHAR(100),
      data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- Migrando dados da tabela grupos para fabricantes, se existirem
    DO \$\$
    BEGIN
        IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'grupos')
           AND NOT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'fabricantes') THEN

            -- Criar tabela fabricantes se não existir
            CREATE TABLE IF NOT EXISTS fabricantes (
              id SERIAL PRIMARY KEY,
              cnpj VARCHAR(18) UNIQUE NOT NULL,
              nome VARCHAR(100) NOT NULL,
              observacao TEXT,
              endereco VARCHAR(255),
              email VARCHAR(100),
              data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );

            -- Inserir dados dos grupos como fabricantes com CNPJ genérico
            INSERT INTO fabricantes (cnpj, nome, observacao)
            SELECT '00000000000000' || id, nome, descricao FROM grupos;

            -- Atualizar referências em produtos
            ALTER TABLE produtos ADD COLUMN id_fabricante INTEGER;
            UPDATE produtos SET id_fabricante = id_grupo;
        END IF;
    END
    \$\$;

    -- Criação da tabela de Produtos
    CREATE TABLE IF NOT EXISTS produtos (
      id SERIAL PRIMARY KEY,
      nome VARCHAR(100) NOT NULL,
      id_fabricante INTEGER REFERENCES fabricantes(id) ON DELETE SET NULL,
      tipo VARCHAR(50),
      volume VARCHAR(50),
      unidade_medida VARCHAR(20),
      preco NUMERIC(10,2),
      data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- Adiciona ou atualiza colunas se a tabela já existir
    DO \$\$
    BEGIN
        IF EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'produtos') THEN
            -- Renomear coluna id_grupo para id_fabricante se existir
            IF EXISTS (SELECT FROM information_schema.columns WHERE table_name = 'produtos' AND column_name = 'id_grupo')
               AND NOT EXISTS (SELECT FROM information_schema.columns WHERE table_name = 'produtos' AND column_name = 'id_fabricante') THEN
                ALTER TABLE produtos RENAME COLUMN id_grupo TO id_fabricante;
                -- Atualizar a foreign key
                ALTER TABLE produtos DROP CONSTRAINT IF EXISTS produtos_id_grupo_fkey;
                ALTER TABLE produtos ADD CONSTRAINT produtos_id_fabricante_fkey
                    FOREIGN KEY (id_fabricante) REFERENCES fabricantes(id) ON DELETE SET NULL;
            END IF;

            -- Adicionar outras colunas se não existirem
            IF NOT EXISTS (SELECT FROM information_schema.columns WHERE table_name = 'produtos' AND column_name = 'tipo') THEN
                ALTER TABLE produtos ADD COLUMN tipo VARCHAR(50);
            END IF;

            IF NOT EXISTS (SELECT FROM information_schema.columns WHERE table_name = 'produtos' AND column_name = 'volume') THEN
                ALTER TABLE produtos ADD COLUMN volume VARCHAR(50);
            END IF;

            IF NOT EXISTS (SELECT FROM information_schema.columns WHERE table_name = 'produtos' AND column_name = 'unidade_medida') THEN
                ALTER TABLE produtos ADD COLUMN unidade_medida VARCHAR(20);
            END IF;
        END IF;
    END
    \$\$;

    -- Criação da tabela de Lugares de Estoque
    CREATE TABLE IF NOT EXISTS lugares (
      id SERIAL PRIMARY KEY,
      nome VARCHAR(100) NOT NULL,
      descricao TEXT
    );

    -- Criação da tabela de Movimentações
    CREATE TABLE IF NOT EXISTS movimentos (
      id SERIAL PRIMARY KEY,
      id_produto INTEGER REFERENCES produtos(id) ON DELETE CASCADE,
      id_pessoa INTEGER REFERENCES pessoas(id) ON DELETE CASCADE,
      id_lugar INTEGER REFERENCES lugares(id) ON DELETE CASCADE,
      tipo VARCHAR(10) NOT NULL, -- 'entrada' ou 'saida'
      quantidade INTEGER NOT NULL,
      data_movimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
EOSQL

# Grant permissions after table creation
psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "estoque" <<-EOSQL
    -- Grant permissions on all tables
    GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO estoque;
    GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO estoque;
    GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO admin;
    GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO admin;

    -- Set ownership
    ALTER SCHEMA public OWNER TO admin;
EOSQL

echo "Database initialization completed successfully"
