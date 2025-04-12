# Security Policy

## Supported Versions

As seguintes versões do Sistema de Estoque recebem atualizações de segurança:

| Versão | Suportada          |
| ------ | ------------------ |
| 0.4.x  | :white_check_mark: |
| 0.3.x  | :white_check_mark: |
| < 0.3  | :x:                |

## Reportando Vulnerabilidades

Caso você encontre uma vulnerabilidade de segurança neste projeto, siga estas etapas para reportá-la:

1. **Não divulgue publicamente** a vulnerabilidade encontrada
2. Envie um e-mail para [security@exemplo.com.br](mailto:security@exemplo.com.br) com detalhes sobre:
   - O tipo de vulnerabilidade
   - Os passos para reproduzir o problema
   - Possível impacto da vulnerabilidade
   - Sugestões de mitigação (se houver)

## Práticas de Segurança Implementadas

### Prevenção contra SQL Injection
- Uso de prepared statements em todas as consultas SQL
- Validação de parâmetros de entrada

### Segurança de Dados
- Sanitização de todas as entradas de usuário
- Validação de dados em camadas (cliente e servidor)

### Segurança de Configuração
- Configurações sensíveis (como credenciais de banco de dados) armazenadas em variáveis de ambiente
- Configuração segura de contêineres Docker

## Boas Práticas para Desenvolvedores

Ao contribuir para este projeto, siga estas práticas de segurança:

1. **Nunca** armazene senhas ou chaves de API diretamente no código
2. Use **sempre** prepared statements para consultas SQL
3. Sanitize todas as entradas de usuários antes de processá-las
4. Valide todos os dados de formulário no servidor, independente da validação no cliente
5. Mantenha todas as dependências atualizadas
6. Utilize HTTPS para todas as comunicações em produção
7. Aplique o princípio do privilégio mínimo ao configurar permissões de banco de dados

## Auditoria de Segurança

A versão atual do sistema não possui um sistema formal de auditoria de segurança. Para ambientes de produção, recomenda-se:

1. Implementação de logs de atividades
2. Monitoramento regular de acessos
3. Revisão periódica de permissões de banco de dados
4. Análise estática de código para identificar vulnerabilidades
