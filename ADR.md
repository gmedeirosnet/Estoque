# ADR-001: Escolha do Stack Tecnológico para o Sistema de Estoque

**Data:** 2025-04-08
**Status:** Aceito

## Contexto

O projeto consiste em desenvolver um sistema de estoque com as seguintes funcionalidades:

- **Cadastro de Pessoas:** Registro de usuários e colaboradores.
- **Cadastro de Produtos e Grupos de Produtos:** Organização dos produtos em categorias para facilitar a gestão.
- **Cadastro de Lugares de Estoque:** Definição dos locais onde os produtos serão armazenados.
- **Registro de Movimentações:** Controle de entradas e saídas dos produtos com rastreamento de quem realizou as operações.
- **Geração de Relatórios:** Consolidação das informações para acompanhamento das movimentações, com destaque para as pessoas responsáveis.

Os requisitos iniciais apontam para a utilização de PHP como linguagem de programação e PostgreSQL como sistema gerenciador de banco de dados. A escolha desses componentes é fundamental para garantir um desenvolvimento ágil, manutenção facilitada e uma boa performance, principalmente em operações críticas de integridade dos dados.

## Decisão

A decisão tomada é a seguinte:

### Linguagem de Programação: PHP

**Motivação:** PHP é amplamente utilizado para o desenvolvimento web, possui uma comunidade ativa e ferramentas maduras para acesso a banco de dados (por exemplo, a extensão PDO) que permitem a utilização de boas práticas de segurança (como o uso de prepared statements).

### Banco de Dados: PostgreSQL

**Motivação:** PostgreSQL oferece robustez, alta confiabilidade e suporte a operações complexas com integridade referencial, fundamentais para um sistema de estoque que envolve transações e movimentações de dados.

### Estruturação do Projeto:

- **Modularidade:** Organização do código em pastas separadas (configuração, cadastros e relatórios), o que facilita a manutenção e futuras expansões.
- **Arquitetura Camada:** Separação das responsabilidades, com isolamento da lógica de acesso a dados, validação de entrada e apresentação.
- **Segurança:** Implementação de práticas como o uso de prepared statements para prevenir ataques de SQL Injection, além de validações adequadas dos dados do lado do cliente e servidor.

### Abordagem Inicial vs. Futuras Expansões:

- **Abordagem Inicial:** A implementação será feita usando PHP "puro", mantendo o sistema simples e direto para que ele atenda aos requisitos básicos.
- **Possibilidade de Uso de Frameworks:** Embora frameworks modernos como Laravel ou Symfony tenham sido considerados, a decisão foi iniciar com PHP puro para manter a clareza e simplicidade. Em iterações futuras, a migração ou refatoração para um framework pode ser considerada para facilitar a escalabilidade e a manutenção.

## Alternativas Consideradas

### Uso de MySQL em vez de PostgreSQL:
Apesar de MySQL ser popular, foi descartado em favor do PostgreSQL, que oferece melhor suporte a operações transacionais e integridade referencial, pontos críticos para o sistema de estoque.

### Implementação com Frameworks PHP (Laravel, Symfony):
O uso de um framework pode acelerar o desenvolvimento e introduzir padrões avançados de organização de código. No entanto, para a versão inicial do projeto e para manter o exemplo simples, a decisão foi seguir com PHP puro. A utilização de um framework pode ser reavaliada conforme o projeto evolua.

### Arquitetura Monolítica versus Modular:
Embora uma aplicação monolítica simples fosse suficiente para o MVP, a decisão foi adotar desde o início uma estrutura modular para facilitar a manutenção e a escalabilidade, caso novas funcionalidades precisem ser incorporadas.

## Consequências

### Simplicidade e Rapidez de Desenvolvimento:
A escolha por PHP puro e uma estrutura modular permite um desenvolvimento rápido e facilita a compreensão do código para desenvolvedores que possam ingressar no projeto posteriormente.

### Segurança e Integridade dos Dados:
O uso de PostgreSQL e prepared statements aumenta a segurança e assegura que a integridade dos dados seja mantida, o que é vital para o controle de estoque.

### Escalabilidade:
Embora a solução inicial seja simples, a separação em módulos e a adoção de práticas arquiteturais facilitam futuras expansões, como a implementação de autenticação, autorização de usuários e integração com outros sistemas.

### Curva de Aprendizado e Manutenção:
Desenvolvedores com conhecimento em PHP e PostgreSQL poderão rapidamente compreender e dar manutenção ao sistema, reduzindo o custo de treinamentos e documentações extensas.

## Conclusão

A escolha do stack tecnológico – PHP para o desenvolvimento e PostgreSQL para o gerenciamento de dados – foi fundamentada na robustez, segurança e facilidade de manutenção, atendendo aos requisitos iniciais do sistema de estoque. A estrutura modular adotada viabiliza a escalabilidade e futuras integrações, ao mesmo tempo em que mantém o desenvolvimento inicial simples e ágil.

> **Revisão:** Este ADR deverá ser revisado sempre que houver alterações significativas no escopo do projeto ou no stack tecnológico adotado.