<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Controle de Estoque</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        .menu-section {
            margin-bottom: 20px;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }
        .menu-item {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }
        .menu-item:hover {
            background-color: #0056b3;
        }
        footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistema de Controle de Estoque</h1>
            <p>Gerencie produtos, pessoas, movimentações e gere relatórios</p>
        </header>

        <div class="setup-section">
            <h2>Configuração do Sistema</h2>
            <div class="menu-grid">
                <div class="menu-item">
                    <a href="test_connection.php">Testar Conexão</a>
                </div>
                <div class="menu-item">
                    <a href="setup_database.php">Configurar Banco de Dados</a>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2>Cadastros</h2>
            <div class="menu-grid">
                <div class="menu-item">
                    <a href="cadastros/pessoa.php">Cadastro de Pessoas</a>
                </div>
                <div class="menu-item">
                    <a href="cadastros/grupo.php">Cadastro de Grupos</a>
                </div>
                <div class="menu-item">
                    <a href="cadastros/produto.php">Cadastro de Produtos</a>
                </div>
                <div class="menu-item">
                    <a href="cadastros/lugar.php">Cadastro de Lugares</a>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2>Movimentação</h2>
            <div class="menu-grid">
                <div class="menu-item">
                    <a href="cadastros/movimento.php">Registrar Movimentação</a>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2>Relatórios</h2>
            <div class="menu-grid">
                <div class="menu-item">
                    <a href="relatorios/relatorio_movimentos.php">Relatório de Movimentações</a>
                </div>
                <div class="menu-item">
                    <a href="relatorios/relatorio_estoque.php">Relatório de Estoque</a>
                </div>
            </div>
        </div>

        <footer>
            <p>Sistema de Controle de Estoque &copy; <?= date('Y') ?></p>
        </footer>
    </div>
</body>
</html>