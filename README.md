# ESTOQUE

## DESCRIÇÃO

Sistema de estoque simples desenvolvido com PHP 8.4 e PostgreSQL 15, implementando os seguintes módulos:

- Cadastro de Pessoas
- Cadastro de Produtos e Grupos de Produtos
- Cadastro de Lugares de Estocagem
- Movimentações (entradas e saídas) de Produtos
- Geração de Relatórios de Movimentações e Estoque Atual

O sistema é estruturado em camadas, separando configuração, conexão com banco de dados, operações CRUD para cada módulo e geração de relatórios. Este projeto demonstra boas práticas de modelagem relacional e implementação PHP com foco em segurança e confiabilidade.

## Requisitos

- PHP 8.4 ou superior
- PostgreSQL 15
- Docker e Docker Compose (para ambiente de desenvolvimento)

## Estrutura de Arquivos e Organização do projeto

```
Estoque/
├── src/                      # Código-fonte da aplicação
│   ├── index.php             # Página inicial
│   ├── test_connection.php   # Ferramenta para testar a conexão com o DB
│   ├── php.ini               # Configuração personalizada do PHP
│   ├── cadastros/            # Formulários e operações CRUD
│   │   ├── pessoa.php
│   │   ├── grupo.php
│   │   ├── produto.php
│   │   ├── fabricante.php
│   │   ├── grupo_pessoa.php
│   │   ├── lugar.php
│   │   ├── movimento.php
│   │   └── list_*.php        # Listagens de cadastros
│   ├── config/               # Configurações da aplicação
│   │   ├── db.php            # Conexão com o banco de dados
│   │   ├── sql.sh            # Scripts SQL auxiliares
│   │   ├── cadastros/        # Configurações específicas para cadastros
│   │   └── relatorios/       # Configurações para relatórios
│   └── relatorios/           # Geração de relatórios
│       ├── relatorio_estoque.php
│       └── relatorio_movimentos.php
├── scripts/                  # Scripts de inicialização
│   └── init-db.sh            # Script para inicialização do banco de dados
├── terraform/                # Arquivos para infraestrutura como código
│   ├── main.tf
│   ├── outputs.tf
│   └── variables.tf
├── docker-compose.yml        # Configuração dos containers Docker
├── run.sh                    # Script de execução rápida
├── README.md                 # Este arquivo
├── CHANGELOG.md              # Histórico de alterações
├── ADR.md                    # Registro de decisões arquiteturais
└── SECURITY.md               # Políticas de segurança
```

## Instalação e Execução

### Com Docker (Recomendado)

1. Certifique-se de ter o Docker e o Docker Compose instalados:
   ```bash
   docker --version
   docker-compose --version
   ```

2. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/estoque.git
   cd estoque
   ```

3. Execute o script de inicialização:
   ```bash
   ./run.sh
   ```
   Ou inicie os contêineres manualmente:
   ```bash
   docker-compose up -d
   ```

4. Acesse o sistema:
   - Aplicação Web: http://localhost:8080
   - PgAdmin (gerenciador PostgreSQL): http://localhost:5050
     - Email: admin@admin.com
     - Senha: admin

5. Para testar a conexão com o banco de dados:
   - Acesse http://localhost:8080/test_connection.php

### Instalação Manual

1. Configure um servidor web com PHP 8.4
2. Configure um servidor PostgreSQL 15
3. Execute o script `scripts/init-db.sh` para criar o banco de dados
4. Configure os parâmetros de conexão em `src/config/db.php`
5. Acesse a aplicação pelo seu servidor web

## Solução de Problemas de Conexão

Se encontrar problemas de conexão com o PostgreSQL:

1. Verifique se o serviço PostgreSQL está em execução:
   ```bash
   docker-compose ps
   ```

2. Acesse a página de teste de conexão:
   ```
   http://localhost:8080/test_connection.php
   ```

3. Verifique os logs do container PostgreSQL:
   ```bash
   docker-compose logs db
   ```

4. Certifique-se de que as credenciais de banco de dados estão corretas em `src/config/db.php`

5. Aguarde a inicialização completa do PostgreSQL, que pode levar alguns segundos

## Considerações Técnicas

### Validações e Segurança:
- Prepared statements para prevenção de SQL Injection
- Validação de dados nos formulários
- Estrutura que permite implementação futura de autenticação de usuários
- Configuração segura de contêineres Docker

### Modularização:
- Organização em diretórios funcionais
- Separação clara entre lógica de dados e apresentação
- Fácil manutenção e extensão do código

### Interface e Usabilidade:
- Interface HTML simples e funcional
- Possibilidade de integração com frameworks CSS no futuro

## Contribuição

1. Faça um fork do repositório
2. Crie um branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Faça commit das suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Envie para o branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a licença MIT - veja o arquivo LICENSE para detalhes.

## Última Atualização

12 de abril de 2025