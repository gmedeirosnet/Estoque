<?php
// config/db.php

$host = 'localhost';
$dbname = 'estoque';
$user = 'admin';  // Alterado: novo usuário do banco
$pass = 'password'; // Alterado: nova senha do banco

$dsn = "pgsql:host=$host;dbname=$dbname";
try {
    $pdo = new PDO($dsn, $user, $pass);
    // Definindo o modo de erro para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    echo "Falha na conexão: " . $e->getMessage();
    exit;
}
?>
