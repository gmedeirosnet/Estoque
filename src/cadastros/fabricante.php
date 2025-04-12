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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $cnpj = isset($_POST['cnpj']) ? preg_replace('/[^0-9]/', '', $_POST['cnpj']) : '';
    $nome = trim($_POST['nome']);
    $observacao = trim($_POST['observacao']);
    $endereco = trim($_POST['endereco']);
    $email = trim($_POST['email']);

    // Basic validation
    $errors = [];

    if (empty($nome)) {
        $errors[] = "O nome do fabricante é obrigatório.";
    }

    if (empty($cnpj)) {
        $errors[] = "O CNPJ é obrigatório.";
    } elseif (strlen($cnpj) !== 14) {
        $errors[] = "O CNPJ deve conter 14 dígitos.";
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "O email informado não é válido.";
    }

    // Format CNPJ for display (XX.XXX.XXX/XXXX-XX)
    $formatted_cnpj = '';
    if (strlen($cnpj) === 14) {
        $formatted_cnpj = substr($cnpj, 0, 2) . '.' .
                          substr($cnpj, 2, 3) . '.' .
                          substr($cnpj, 5, 3) . '/' .
                          substr($cnpj, 8, 4) . '-' .
                          substr($cnpj, 12, 2);
    }

    // Check if CNPJ already exists (for new records or changed CNPJ)
    if (empty($errors)) {
        if ($editing) {
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM fabricantes WHERE cnpj = :cnpj AND id != :id");
            $stmt_check->execute(['cnpj' => $formatted_cnpj, 'id' => $_GET['id']]);
        } else {
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM fabricantes WHERE cnpj = :cnpj");
            $stmt_check->execute(['cnpj' => $formatted_cnpj]);
        }

        if ($stmt_check->fetchColumn() > 0) {
            $errors[] = "Este CNPJ já está cadastrado para outro fabricante.";
        }
    }

    if (empty($errors)) {
        try {
            if ($editing) {
                // Update existing record
                $sql = "UPDATE fabricantes SET cnpj = :cnpj, nome = :nome, observacao = :observacao, endereco = :endereco, email = :email WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    'cnpj' => $formatted_cnpj,
                    'nome' => $nome,
                    'observacao' => $observacao,
                    'endereco' => $endereco,
                    'email' => $email,
                    'id' => $_GET['id']
                ]);

                if ($result) {
                    $message = "Fabricante atualizado com sucesso!";
                    $messageType = "success";
                } else {
                    $errors[] = "Erro ao atualizar fabricante.";
                }
            } else {
                // Insert new record
                $sql = "INSERT INTO fabricantes (cnpj, nome, observacao, endereco, email) VALUES (:cnpj, :nome, :observacao, :endereco, :email)";
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    'cnpj' => $formatted_cnpj,
                    'nome' => $nome,
                    'observacao' => $observacao,
                    'endereco' => $endereco,
                    'email' => $email
                ]);

                if ($result) {
                    $message = "Fabricante cadastrado com sucesso!";
                    $messageType = "success";
                    // Clear form after successful submission
                    $cnpj = $nome = $observacao = $endereco = $email = '';
                } else {
                    $errors[] = "Erro ao cadastrar fabricante.";
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Erro no banco de dados: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $error_message = implode("<br>", $errors);
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
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
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
        .required-indicator {
            color: red;
            margin-left: 3px;
        }
    </style>
    <script>
        function formatCNPJ(input) {
            // Remove any non-digit character
            var value = input.value.replace(/\D/g, '');

            if (value.length > 14) {
                value = value.substr(0, 14);
            }

            if (value.length > 0) {
                value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            }

            input.value = value;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1><?= $editing ? 'Editar' : 'Cadastro de' ?> Fabricante</h1>

        <?php if (isset($message)): ?>
            <div class="message <?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="message error">
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="cnpj">CNPJ: <span class="required-indicator">*</span></label>
                <input type="text" name="cnpj" id="cnpj" required maxlength="18"
                       value="<?= isset($cnpj) ? htmlspecialchars($cnpj) : ($editing ? htmlspecialchars($fabricante['cnpj'] ?? '') : '') ?>"
                       onkeyup="formatCNPJ(this)" placeholder="XX.XXX.XXX/XXXX-XX">
            </div>

            <div class="form-group">
                <label for="nome">Nome do Fabricante: <span class="required-indicator">*</span></label>
                <input type="text" name="nome" id="nome" required
                       value="<?= isset($nome) ? htmlspecialchars($nome) : ($editing ? htmlspecialchars($fabricante['nome']) : '') ?>">
            </div>

            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" name="endereco" id="endereco"
                       value="<?= isset($endereco) ? htmlspecialchars($endereco) : ($editing ? htmlspecialchars($fabricante['endereco'] ?? '') : '') ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email"
                       value="<?= isset($email) ? htmlspecialchars($email) : ($editing ? htmlspecialchars($fabricante['email'] ?? '') : '') ?>">
            </div>

            <div class="form-group">
                <label for="observacao">Observação:</label>
                <textarea name="observacao" id="observacao"><?= isset($observacao) ? htmlspecialchars($observacao) : ($editing ? htmlspecialchars($fabricante['observacao'] ?? '') : '') ?></textarea>
            </div>

            <input type="submit" value="<?= $editing ? 'Atualizar' : 'Cadastrar' ?>">
        </form>

        <p><a href="../index.php">Voltar para a Página Inicial</a></p>
        <p><a href="list_fabricantes.php" class="btn">Ver Todos os Fabricantes</a></p>
    </div>
</body>
</html>