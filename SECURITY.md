# Security Policy

## Supported Versions

As seguintes versões do Sistema de Estoque recebem atualizações de segurança:

| Versão | Suportada          |
| ------ | ------------------ |
| 0.5.x  | :white_check_mark: |
| 0.4.x  | :white_check_mark: |
| 0.3.x  | :x:                |
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
- Validação e sanitização de parâmetros de entrada
- Uso de tipos parametrizados para evitar conversões implícitas inseguras

### Prevenção contra XSS (Cross-Site Scripting)
- Sanitização de saídas em HTML utilizando funções de escape apropriadas
- Validação rigorosa de dados de entrada
- Implementação de cabeçalhos de segurança apropriados

### Segurança de Dados
- Sanitização de todas as entradas de usuário
- Validação de dados em camadas (cliente e servidor)
- Implementação de logs de auditoria para operações críticas

### Segurança de Configuração
- Configurações sensíveis (como credenciais de banco de dados) armazenadas em variáveis de ambiente
- Configuração segura de contêineres Docker
- Permissões mínimas necessárias para funcionamento do sistema

### Segurança em Infraestrutura (Terraform)
- Controle de acesso baseado em privilégio mínimo
- Criptografia de dados sensíveis em trânsito e em repouso
- Definições de segurança como código nos arquivos Terraform

## Boas Práticas para Desenvolvedores

Ao contribuir para este projeto, siga estas práticas de segurança:

1. **Nunca** armazene senhas ou chaves de API diretamente no código
2. Use **sempre** prepared statements para consultas SQL
3. Sanitize todas as entradas de usuários antes de processá-las
4. Valide todos os dados de formulário no servidor, independente da validação no cliente
5. Mantenha todas as dependências atualizadas
6. Utilize HTTPS para todas as comunicações em produção
7. Aplique o princípio do privilégio mínimo ao configurar permissões de banco de dados
8. Implemente validação adequada de tipos em todas as entradas
9. Faça revisão de segurança do código regularmente

## Auditoria de Segurança

O sistema implementa as seguintes práticas de auditoria:

1. **Logs de atividades críticas:**
   - Movimentações de estoque
   - Alterações em dados sensíveis
   - Tentativas de autenticação (em versões futuras)

2. **Monitoramento e Alertas:**
   - Configuração para detecção de comportamento anômalo
   - Alertas para tentativas de acesso não autorizado

3. **Revisão Regular:**
   - Análise periódica de logs e permissões
   - Revisão de código focada em segurança a cada release

## Planos para Futuras Melhorias de Segurança

1. Implementação de autenticação e autorização de usuários
2. Auditoria completa de todas as operações do sistema
3. Implementação de proteções adicionais contra ataques de força bruta
4. Integração com sistemas de análise de vulnerabilidades

## Atualização e Patch Management

1. Patches de segurança críticos são lançados imediatamente após validação
2. Atualizações regulares de segurança são incluídas em cada release
3. O status de vulnerabilidades conhecidas é mantido atualizado

Última atualização: 12 de abril de 2025
