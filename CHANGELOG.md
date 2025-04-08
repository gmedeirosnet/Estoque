# Changelog

Todas as alterações notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Versionamento Semântico](https://semver.org/lang/pt-BR/spec/v2.0.0.html).

## [0.3.0] - 2023-05-01

### Adicionado
- Atualização para PHP 8.2 estável
- Adicionado arquivo de configuração php.ini customizado
- Adicionado .dockerignore para otimizar builds
- Documentação atualizada com requisitos PHP 8.2

### Alterado
- Docker Compose configurado para usar a imagem oficial php:8.2-apache
- Corrigidos os comandos de inicialização do container PHP
- Configuração de portas e ambiente para melhor compatibilidade

## [0.2.0] - 2023-04-15

### Adicionado
- Movido o arquivo principal `index.php` para o diretório `src`
- Criação de arquivos `test_connection.php` e `setup_database.php` para auxiliar na configuração
- Implementação de um relatório de estoque atual com saldos por produto e local
- Arquivo ADR.md formatado para documentar decisões arquiteturais
- Redirecionador na raiz do projeto para o novo local do index.php

### Alterado
- Reorganização da estrutura de diretórios para usar `src` como diretório padrão
- Atualizados todos os caminhos relativos para navegação entre arquivos
- Corrigidos os caminhos de inclusão de arquivos em todos os formulários
- Movidos os arquivos de cadastro do diretório `config/cadastros` para `src/cadastros`
- Estrutura modular mais clara com separação de responsabilidades

### Corrigido
- Caminhos incorretos para voltar à página principal
- Problemas de referência aos arquivos de configuração
- Consulta SQL do relatório de estoque para cálculo correto dos saldos

## [0.1.0] - 2023-04-01

### Adicionado
- Configuração inicial do banco de dados PostgreSQL
- Implementação do arquivo de conexão com o banco (`db.php`)
- Script SQL para criação das tabelas do sistema
- Formulários para cadastro de pessoas, produtos, grupos e lugares
- Formulário para registro de movimentações de estoque (entradas e saídas)
- Relatório de movimentações
- Estrutura básica do projeto com separação em diretórios funcionais
