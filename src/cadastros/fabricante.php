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
    $cnpj = isset($_POST['cnpj']) ? preg_replace('/[^0-9]/', '', $_POST['cnpj']) : '';
    $nome = $_POST['nome'];
    $observacao = $_POST['observacao'];
    $endereco = $_POST['endereco'];
    $email = $_POST['email'];

    // Validations
    $errors = [];

    if (empty($cnpj)) {
        $errors[] = "O CNPJ é obrigatório.";
    } elseif (strlen($cnpj) != 14) {
        $errors[] = "O CNPJ deve ter 14 números.";
    }

    if (empty($nome)) {
        $errors[] = "O nome do fabricante é obrigatório.";
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "O formato do email não é válido.";
    }

    // Check if CNPJ already exists (except for editing current record)
    if (!empty($cnpj)) {
        $query = "SELECT COUNT(*) FROM fabricantes WHERE cnpj = :cnpj";
        $params = ['cnpj' => $cnpj];

        if ($editing) {
            $query .= " AND id != :id";
            $params['id'] = $_GET['id'];
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Este CNPJ já está cadastrado.";
        }
    }

    if (empty($errors)) {
        if ($editing) {
            $sql = "UPDATE fabricantes
                    SET cnpj = :cnpj, nome = :nome, observacao = :observacao,
                        endereco = :endereco, email = :email
                    WHERE id = :id";
            $params = [
                'cnpj' => $cnpj,
                'nome' => $nome,
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
            $sql = "INSERT INTO fabricantes (cnpj, nome, observacao, endereco, email)
                    VALUES (:cnpj, :nome, :observacao, :endereco, :email)";
            $params = [
                'cnpj' => $cnpj,
                'nome' => $nome,
                'observacao' => $observacao,
                'endereco' => $endereco,
                'email' => $email
            ];

            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                $message = "Fabricante cadastrado com sucesso!";
                $messageType = "success";

                // Clear form
                $cnpj = $nome = $observacao = $endereco = $email = '';
            } else {
                $message = "Erro ao cadastrar fabricante.";
                $messageType = "error";
            }
        }
    } else {
        $message = implode("<br>", $errors);
        $messageType = "error";
    }
}

// Format CNPJ for display
function formatCNPJ($cnpj) {
    if (strlen($cnpj) != 14) return $cnpj;
    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $cnpj);
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
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // CNPJ formatting
        function formatCNPJ(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 14) value = value.slice(0, 14);

            if (value.length > 12) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, "$1.$2.$3/$4-$5");
            } else if (value.length > 8) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{0,4}).*/, "$1.$2.$3/$4");
            } else if (value.length > 5) {
                value = value.replace(/^(\d{2})(\d{3})(\d{0,3}).*/, "$1.$2.$3");
            } else if (value.length > 2) {
                value = value.replace(/^(\d{2})(\d{0,3}).*/, "$1.$2");
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
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="cnpj">CNPJ: <span class="required-indicator">*</span></label>
                <input type="text" name="cnpj" id="cnpj"
                       value="<?= isset($cnpj) ? formatCNPJ($cnpj) : ($editing ? formatCNPJ($fabricante['cnpj']) : '') ?>"
                       onkeyup="formatCNPJ(this)" maxlength="18" required>
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

        <p>
            <a href="list_fabricantes.php">Ver todos os Fabricantes</a> |
            <a href="../index.php">Voltar para a Página Inicial</a>
        </p>
    </div>
</body>
</html>