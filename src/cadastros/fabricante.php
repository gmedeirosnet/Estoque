<?php
// cadastros/fabricante.php
require_once __DIR__ . '/../config/db.php';

// Check if editing existing record
$editing = false;
$fabricante = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM fabricantes WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $fabricante = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($fabricante) {
        $editing = true;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $cnpj = trim($_POST['cnpj']);
    $observacao = trim($_POST['observacao'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validate required fields
    $errors = [];
    if (empty($nome)) {
        $errors[] = "O nome do fabricante é obrigatório.";
    }
    if (empty($cnpj)) {
        $errors[] = "O CNPJ é obrigatório.";
    } elseif ($editing && isset($_GET['id'])) {
        // Check if CNPJ is already used by another record
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM fabricantes WHERE cnpj = :cnpj AND id != :id");
        $stmt->execute(['cnpj' => $cnpj, 'id' => $_GET['id']]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Este CNPJ já está em uso por outro fabricante.";
        }
    } else {
        // Check if CNPJ already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM fabricantes WHERE cnpj = :cnpj");
        $stmt->execute(['cnpj' => $cnpj]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Este CNPJ já está cadastrado.";
        }
    }

    // If no validation errors, proceed with database operation
    if (empty($errors)) {
        if ($editing) {
            // Update existing record
            $sql = "UPDATE fabricantes SET nome = :nome, cnpj = :cnpj, observacao = :observacao, endereco = :endereco, email = :email WHERE id = :id";
            $params = [
                'nome' => $nome,
                'cnpj' => $cnpj,
                'observacao' => $observacao,
                'endereco' => $endereco,
                'email' => $email,
                'id' => $_GET['id']
            ];

            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                $message = "Fabricante atualizado com sucesso!";
                $messageType = "success";
            } else {
                $message = "Erro ao atualizar fabricante.";
                $messageType = "error";
            }
        } else {
            // Insert new record
            $sql = "INSERT INTO fabricantes (nome, cnpj, observacao, endereco, email) VALUES (:nome, :cnpj, :observacao, :endereco, :email)";
            $params = [
                'nome' => $nome,
                'cnpj' => $cnpj,
                'observacao' => $observacao,
                'endereco' => $endereco,
                'email' => $email
            ];

            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                $message = "Fabricante cadastrado com sucesso!";
                $messageType = "success";

                // Clear form fields after successful insert
                $nome = $cnpj = $observacao = $endereco = $email = '';
            } else {
                $message = "Erro ao cadastrar fabricante.";
                $messageType = "error";
            }
        }
    } else {
        // Display validation errors
        $message = implode("<br>", $errors);
        $messageType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $editing ? 'Editar' : 'Cadastro de' ?> Fabricante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .required-indicator {
            color: red;
            margin-left: 3px;
        }
    </style>
    <script>
        // CNPJ mask function
        function formatCNPJ(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 14) {
                value = value.substring(0, 14);
            }

            if (value.length > 12) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
            } else if (value.length > 8) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d*).*/, '$1.$2.$3/$4');
            } else if (value.length > 5) {
                value = value.replace(/^(\d{2})(\d{3})(\d*).*/, '$1.$2.$3');
            } else if (value.length > 2) {
                value = value.replace(/^(\d{2})(\d*).*/, '$1.$2');
            }

            input.value = value;
        }

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('fabricante-form');
            form.addEventListener('submit', function(event) {
                const nomeField = document.getElementById('nome');
                const cnpjField = document.getElementById('cnpj');

                let isValid = true;

                if (!nomeField.value.trim()) {
                    isValid = false;
                    alert('O nome do fabricante é obrigatório');
                    nomeField.focus();
                }

                if (!cnpjField.value.trim()) {
                    isValid = false;
                    alert('O CNPJ é obrigatório');
                    cnpjField.focus();
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1><?= $editing ? 'Editar' : 'Cadastro de' ?> Fabricante</h1>

        <?php if (isset($message)): ?>
            <div class="message <?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="post" id="fabricante-form">
            <div class="form-group">
                <label for="nome">Nome do Fabricante: <span class="required-indicator">*</span></label>
                <input type="text" name="nome" id="nome" required
                       value="<?= $editing ? htmlspecialchars($fabricante['nome']) : (isset($nome) ? htmlspecialchars($nome) : '') ?>">
            </div>

            <div class="form-group">
                <label for="cnpj">CNPJ: <span class="required-indicator">*</span></label>
                <input type="text" name="cnpj" id="cnpj" required oninput="formatCNPJ(this)"
                       value="<?= $editing ? htmlspecialchars($fabricante['cnpj']) : (isset($cnpj) ? htmlspecialchars($cnpj) : '') ?>">
                <small>Formato: XX.XXX.XXX/XXXX-XX</small>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email"
                       value="<?= $editing ? htmlspecialchars($fabricante['email'] ?? '') : (isset($email) ? htmlspecialchars($email) : '') ?>">
            </div>

            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" id="endereco"
                       value="<?= $editing ? htmlspecialchars($fabricante['endereco'] ?? '') : (isset($endereco) ? htmlspecialchars($endereco) : '') ?>">
            </div>

            <div class="form-group">
                <label for="observacao">Observação:</label>
                <textarea name="observacao" id="observacao"><?= $editing ? htmlspecialchars($fabricante['observacao'] ?? '') : (isset($observacao) ? htmlspecialchars($observacao) : '') ?></textarea>
            </div>

            <input type="submit" value="<?= $editing ? 'Atualizar' : 'Cadastrar' ?>">
        </form>

        <p>
            <a href="list_fabricantes.php">Ver Lista de Fabricantes</a> |
            <a href="../index.php">Voltar para a Página Inicial</a>
        </p>
    </div>
</body>
</html>