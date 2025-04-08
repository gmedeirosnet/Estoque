# ESTOQUE

## DESCRIÇÃO

A seguir, apresento um exemplo de como desenvolver um sistema de estoque simples usando PHP 8.2 e PostgreSQL. Essa solução exemplifica os seguintes módulos:

    - Cadastro de Pessoas

    - Cadastro de Produtos e Grupos de Produtos

    - Cadastro de Lugares de Estocagem

    - Movimentações (entradas e saídas) de Produtos

    - Geração de Relatórios de Movimentações (incluindo as pessoas que realizaram as operações)

A ideia é estruturar o sistema em camadas, separando a configuração e a conexão com o banco, as operações CRUD para cada módulo e a geração do relatório. Essa explicação detalha tanto a modelagem relacional (com o script para criação das tabelas) quanto alguns exemplos de código PHP para manipulação dos dados.

## Requisitos

- PHP 8.2 ou superior
- PostgreSQL 15
- Docker e Docker Compose (para ambiente de desenvolvimento)

## Estrutura de Arquivos e Organização do projeto

```
estoque/
├── config/
│   └── db.php
├── cadastros/
│   ├── pessoa.php
│   ├── grupo.php
│   ├── produto.php
│   ├── lugar.php
│   └── movimento.php
├── relatorios/
│   └── relatorio_movimentos.php
└── index.php
```

## Como executar com Docker

1. Certifique-se de ter o Docker e o Docker Compose instalados
2. Execute `docker-compose up -d` na raiz do projeto
3. Acesse http://localhost:8080 no seu navegador

## Considerações finais

### Validações e Segurança:
Além dos exemplos apresentados, é recomendável implementar validações (tanto do lado do cliente quanto do servidor) e mecanismos de segurança, como a prevenção de SQL Injection (usando prepared statements, conforme mostrado), sanitização de dados e, se necessário, autenticação de usuários.

### Modularização e Frameworks:
Para sistemas mais robustos, considere utilizar um framework PHP (como Laravel ou Symfony) que já possui uma estrutura para organização de arquivos, rotas, modelos e controle de segurança.

### Interface e Usabilidade:
A interface apresentada é simples (HTML puro). É possível integrar bibliotecas de front-end ou frameworks CSS para melhorar a experiência do usuário.

Este exemplo cobre os principais pontos solicitados para um sistema de estoque com cadastro de pessoas, produtos (e seus grupos), lugares de estocagem, movimentações e geração de relatórios. Você pode expandir essa base para incluir funcionalidades adicionais, como edição e exclusão dos cadastros, autenticação de usuários, entre outros aprimoramentos conforme a necessidade do seu projeto.