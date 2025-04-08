# Configuração do PostgreSQL para Aceitar Conexões Remotas

Para permitir que o PostgreSQL aceite conexões de qualquer endereço IP, siga estas etapas:

## 1. Editar postgresql.conf

Localize o arquivo `postgresql.conf` (geralmente em `/etc/postgresql/[versão]/main/` no Linux ou em `[pasta de instalação]/data/` no Windows):

```
# Altere a linha listen_addresses
listen_addresses = '*'    # o asterisco permite escutar em todos os endereços IP
```

## 2. Editar pg_hba.conf

Localize o arquivo `pg_hba.conf` (no mesmo diretório que postgresql.conf) e adicione a seguinte linha para permitir conexões de qualquer IP:

```
# Conexões IPv4 de qualquer endereço
host    estoque         admin           0.0.0.0/0               md5
# Conexões IPv6 de qualquer endereço
host    estoque         admin           ::/0                    md5
```

## 3. Reiniciar o PostgreSQL

Linux:
```
sudo systemctl restart postgresql
```

Windows:
```
Reinicie o serviço PostgreSQL pelo Painel de Controle > Ferramentas Administrativas > Serviços
```

macOS:
```
brew services restart postgresql
```

## 4. Verificar se a porta 5432 está aberta no firewall

Certifique-se de que sua rede e firewall permitem conexões na porta 5432 (porta padrão do PostgreSQL).

## 5. Testar a conexão remota

Use uma ferramenta como pgAdmin ou psql de um computador remoto para testar a conexão:

```
psql -h [endereço_ip_do_servidor] -U admin -d estoque
```
