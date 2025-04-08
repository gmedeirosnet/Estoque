<?php
// Incluir o arquivo de conexão
require_once __DIR__ . '/config/db.php';

echo "<h1>Configuração do Banco de Dados</h1>";

// Obter o script SQL do arquivo
$sqlScript = file_get_contents(__DIR__ . '/config/sql.sh');

// Remover o comentário de filepath que está na primeira linha
$sqlScript = preg_replace('/\/\/ filepath:.+/', '', $sqlScript);

// Dividir o script em comandos separados
$commands = explode(';', $sqlScript);

try {
    // Já temos $pdo do arquivo db.php
    echo "<p>Conexão estabelecida com o banco de dados.</p>";
    echo "<h2>Executando script SQL:</h2>";

    // Executar cada comando SQL
    foreach ($commands as $command) {
        $command = trim($command);
        if (!empty($command)) {
            try {
                $pdo->exec($command);
                echo "<pre style='color: green;'>" . htmlspecialchars($command) . ";<br>✓ Executado com sucesso</pre>";
            } catch (PDOException $e) {
                echo "<pre style='color: red;'>" . htmlspecialchars($command) . ";<br>✗ Erro: " . $e->getMessage() . "</pre>";
            }
        }
    }

    echo "<p style='color: green; font-weight: bold;'>Script SQL executado! Verifique acima se houve algum erro.</p>";

} catch (PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>Erro na conexão: " . $e->getMessage() . "</p>";
}
?>

<p><a href="test_connection.php">Testar conexão e verificar tabelas</a></p>
<p><a href="index.php">Voltar para a página inicial</a></p>
