<?php
// Definindo informações de conexão
require_once __DIR__ . '/config/db.php';

echo "<h1>Teste de Conexão ao PostgreSQL</h1>";

try {
    // Usando a conexão $pdo já estabelecida em db.php
    echo "<p style='color: green; font-weight: bold;'>Conexão estabelecida com sucesso!</p>";

    // Verificar se as tabelas existem
    $tables = ['pessoas', 'grupos', 'produtos', 'lugares', 'movimentos'];
    $foundTables = 0;

    echo "<h2>Verificando tabelas:</h2>";
    echo "<ul>";

    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT to_regclass('public.$table')");
        $stmt->execute();
        $result = $stmt->fetchColumn();

        if ($result) {
            echo "<li style='color: green;'>Tabela '$table' encontrada ✓</li>";
            $foundTables++;
        } else {
            echo "<li style='color: red;'>Tabela '$table' não encontrada ✗</li>";
        }
    }

    echo "</ul>";

    if ($foundTables == count($tables)) {
        echo "<p style='color: green;'>Todas as tabelas estão criadas corretamente!</p>";
    } else {
        echo "<p style='color: orange;'>Algumas tabelas estão faltando. Execute o script SQL para criar as tabelas.</p>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>Erro na conexão: " . $e->getMessage() . "</p>";

    // Verificando se o erro é relacionado à existência do banco de dados
    if (strpos($e->getMessage(), "database \"$dbname\" does not exist") !== false) {
        echo "<p>O banco de dados '$dbname' não existe. Você precisa criá-lo primeiro:</p>";
        echo "<pre>CREATE DATABASE $dbname;</pre>";
    }
}
?>

<p><a href="index.php">Voltar para a página inicial</a></p>
