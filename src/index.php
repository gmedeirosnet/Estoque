<?php
// index.php
require_once __DIR__ . '/config/db.php';

// Fetch limited number of records for each entity
function fetchLimit($pdo, $table, $limit = 5, $orderBy = 'id DESC') {
    $stmt = $pdo->query("SELECT * FROM $table ORDER BY $orderBy LIMIT $limit");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get recent data
$pessoas = fetchLimit($pdo, 'pessoas');
$grupos = fetchLimit($pdo, 'grupos');
$produtos = fetchLimit($pdo, 'produtos');
$lugares = fetchLimit($pdo, 'lugares');
$grupos_pessoas = fetchLimit($pdo, 'grupos_pessoas');

// For movimentos we need to join with related tables
$stmt = $pdo->query("
    SELECT m.id, p.nome AS produto, pe.nome AS pessoa, l.nome AS lugar,
           m.tipo, m.quantidade, m.data_movimento
    FROM movimentos m
    JOIN produtos p ON m.id_produto = p.id
    JOIN pessoas pe ON m.id_pessoa = pe.id
    JOIN lugares l ON m.id_lugar = l.id
    ORDER BY m.data_movimento DESC
    LIMIT 5
");
$movimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
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
            max-width: 1200px;
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
        }
        .menu-item a {
            color: #fff;
            text-decoration: none;
            display: block;
            width: 100%;
            height: 100%;
        }
        .menu-item:hover {
            background-color: #0056b3;
        }
        footer {
            text-align: center;
            margin-top: 20px;
        }
        .records-section {
            margin-top: 40px;
        }
        .records-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .record-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .record-card h3 {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .see-all {
            display: block;
            text-align: right;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .see-all:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistema de Controle de Estoque</h1>
            <p>Gerencie produtos, pessoas, movimentações e gere relatórios</p>
        </header>

        <div class="menu-section">
            <h2>Cadastros</h2>
            <div class="menu-grid">
                <div class="menu-item">
                    <a href="cadastros/pessoa.php">Cadastro de Pessoas</a>
                </div>
                <div class="menu-item">
                    <a href="cadastros/grupo_pessoa.php">Cadastro de Grupos de Pessoas</a>
                </div>
                <div class="menu-item">
                    <a href="cadastros/grupo.php">Cadastro de Grupos de Produtos</a>
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

        <div class="records-section">
            <h2>Últimos Registros</h2>

            <div class="records-grid">
                <!-- Pessoas -->
                <div class="record-card">
                    <h3>Pessoas</h3>
                    <?php if (!empty($pessoas)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pessoas as $pessoa): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pessoa['nome']) ?></td>
                                    <td><?= htmlspecialchars($pessoa['email'] ?? '-') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhuma pessoa cadastrada</p>
                    <?php endif; ?>
                    <a href="cadastros/list_pessoas.php" class="see-all">Ver todos</a>
                </div>

                <!-- Grupos de Pessoas -->
                <div class="record-card">
                    <h3>Grupos de Pessoas</h3>
                    <?php if (!empty($grupos_pessoas)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grupos_pessoas as $grupo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($grupo['nome']) ?></td>
                                    <td><?= htmlspecialchars(substr($grupo['descricao'] ?? '', 0, 30)) . (strlen($grupo['descricao'] ?? '') > 30 ? '...' : '') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhum grupo de pessoas cadastrado</p>
                    <?php endif; ?>
                    <a href="cadastros/list_grupos_pessoas.php" class="see-all">Ver todos</a>
                </div>

                <!-- Grupos de Produtos -->
                <div class="record-card">
                    <h3>Grupos de Produtos</h3>
                    <?php if (!empty($grupos)): ?>
                        <<table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grupos as $grupo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($grupo['nome']) ?></td>
                                    <td><?= htmlspecialchars(substr($grupo['descricao'] ?? '', 0, 30)) . (strlen($grupo['descricao'] ?? '') > 30 ? '...' : '') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhum grupo de produtos cadastrado</p>
                    <?php endif; ?>
                    <a href="#" class="see-all" onclick="alert('Funcionalidade a ser implementada')">Ver todos</a>
                </div>

                <!-- Produtos -->
                <div class="record-card">
                    <h3>Produtos</h3>
                    <?php if (!empty($produtos)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Preço</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                                    <td>R$ <?= number_format($produto['preco'] ?? 0, 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhum produto cadastrado</p>
                    <?php endif; ?>
                    <a href="#" class="see-all" onclick="alert('Funcionalidade a ser implementada')">Ver todos</a>
                </div>

                <!-- Lugares -->
                <div class="record-card">
                    <h3>Lugares</h3>
                    <?php if (!empty($lugares)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lugares as $lugar): ?>
                                <tr>
                                    <td><?= htmlspecialchars($lugar['nome']) ?></td>
                                    <td><?= htmlspecialchars(substr($lugar['descricao'] ?? '', 0, 30)) . (strlen($lugar['descricao'] ?? '') > 30 ? '...' : '') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhum lugar cadastrado</p>
                    <?php endif; ?>
                    <a href="#" class="see-all" onclick="alert('Funcionalidade a ser implementada')">Ver todos</a>
                </div>

                <!-- Movimentos -->
                <div class="record-card" style="grid-column: 1 / -1;">
                    <h3>Últimas Movimentações</h3>
                    <?php if (!empty($movimentos)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Tipo</th>
                                    <th>Qtd</th>
                                    <th>Pessoa</th>
                                    <th>Lugar</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movimentos as $movimento): ?>
                                <tr>
                                    <td><?= htmlspecialchars($movimento['produto']) ?></td>
                                    <td><?= $movimento['tipo'] == 'entrada' ? '<span style="color:green">Entrada</span>' : '<span style="color:red">Saída</span>' ?></td>
                                    <td><?= htmlspecialchars($movimento['quantidade']) ?></td>
                                    <td><?= htmlspecialchars($movimento['pessoa']) ?></td>
                                    <td><?= htmlspecialchars($movimento['lugar']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($movimento['data_movimento'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhuma movimentação registrada</p>
                    <?php endif; ?>
                    <a href="relatorios/relatorio_movimentos.php" class="see-all">Ver relatório completo</a>
                </div>
            </div>
        </div>

        <footer>
            <p>Sistema de Controle de Estoque &copy; <?= date('Y') ?></p>
        </footer>
    </div>
</body>
</html>