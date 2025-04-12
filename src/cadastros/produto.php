<?php
// cadastros/produto.php
require_once __DIR__ . '/../config/db.php';

// Busca os grupos para popular o select
$stmtGrupos = $pdo->query("SELECT id, nome FROM grupos ORDER BY nome");
$grupos = $stmtGrupos->fetchAll(PDO::FETCH_ASSOC);

// Check if editing existing record
$editing = false;
$produto = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($produto) {
        $editing = true;
    }
}

// Tipos de produto predefinidos
$tipos_produto = ['Sólido', 'Líquido', 'Gasoso'];

// Unidades de medida comuns
$unidades_medida = ['Kg', 'g', 'mg', 'L', 'ml', 'cm³', 'cm', 'm', 'm²', 'm³', 'unidade'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $id_grupo = isset($_POST['id_grupo']) ? trim($_POST['id_grupo']) : '';
    $fabricante = $_POST['fabricante'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $volume = $_POST['volume'] ?? '';
    $unidade_medida = $_POST['unidade_medida'] ?? '';
    $preco = $_POST['preco'];

    // Validate required fields
    $errors = [];
    if (empty($nome)) {
        $errors[] = "O nome do produto é obrigatório.";
    }
    if (empty($id_grupo)) {
        $errors[] = "O grupo do produto é obrigatório.";
    }

    // If no validation errors, proceed with database operation
    if (empty($errors)) {
        if ($editing) {
            // Update existing product
            $sql = "UPDATE produtos SET nome = :nome, id_grupo = :id_grupo, fabricante = :fabricante,
                    tipo = :tipo, volume = :volume, unidade_medida = :unidade_medida, preco = :preco
                    WHERE id = :id";
            $params = [
                'nome' => $nome,
                'id_grupo' => $id_grupo,
                'fabricante' => $fabricante,
                'tipo' => $tipo,
                'volume' => $volume,
                'unidade_medida' => $unidade_medida,
                'preco' => $preco,
                'id' => $_GET['id']
            ];

            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                $message = "Produto atualizado com sucesso!";
                $messageType = "success";
            } else {
                $message = "Erro ao atualizar produto.";
                $messageType = "error";
            }
        } else {
            // Insert new product
            $sql = "INSERT INTO produtos (nome, id_grupo, fabricante, tipo, volume, unidade_medida, preco)
                    VALUES (:nome, :id_grupo, :fabricante, :tipo, :volume, :unidade_medida, :preco)";
            $params = [
                'nome' => $nome,
                'id_grupo' => $id_grupo,
                'fabricante' => $fabricante,
                'tipo' => $tipo,
                'volume' => $volume,
                'unidade_medida' => $unidade_medida,
                'preco' => $preco
            ];

            $stmt = $pdo->prepare($sql);
            if ($stmt->execute($params)) {
                $message = "Produto cadastrado com sucesso!";
                $messageType = "success";
            } else {
                $message = "Erro ao cadastrar produto.";
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
    <title><?= $editing ? 'Editar' : 'Cadastro de' ?> Produto</title>
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
        input, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-row {
            display: flex;
            gap: 10px;
        }
        .form-row .form-group {
            flex: 1;
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
        // Form validation script
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('product-form');
            form.addEventListener('submit', function(event) {
                const grupoField = document.getElementById('id_grupo');
                if (!grupoField.value) {
                    event.preventDefault();
                    alert('O campo Grupo é obrigatório');
                    grupoField.focus();
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1><?= $editing ? 'Editar' : 'Cadastro de' ?> Produto</h1>

        <?php if (isset($message)): ?>
            <div class="message <?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="post" id="product-form">
            <div class="form-group">
                <label for="nome">Nome do Produto: <span class="required-indicator">*</span></label>
                <input type="text" name="nome" id="nome" required
                       value="<?= $editing ? htmlspecialchars($produto['nome']) : (isset($nome) ? htmlspecialchars($nome) : '') ?>">
            </div>

            <div class="form-group">
                <label for="fabricante">Fabricante:</label>
                <input type="text" name="fabricante" id="fabricante"
                       value="<?= $editing ? htmlspecialchars($produto['fabricante'] ?? '') : (isset($fabricante) ? htmlspecialchars($fabricante) : '') ?>">
            </div>

            <div class="form-group">
                <label for="id_grupo">Grupo: <span class="required-indicator">*</span></label>
                <select name="id_grupo" id="id_grupo" required>
                    <option value="">Selecione</option>
                    <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= $grupo['id'] ?>" <?= ($editing && $produto['id_grupo'] == $grupo['id']) || (isset($id_grupo) && $id_grupo == $grupo['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($grupo['nome']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo">
                    <option value="">Selecione</option>
                    <?php foreach ($tipos_produto as $tipo_option): ?>
                    <option value="<?= $tipo_option ?>" <?= ($editing && ($produto['tipo'] ?? '') == $tipo_option) || (isset($tipo) && $tipo == $tipo_option) ? 'selected' : '' ?>>
                        <?= $tipo_option ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="volume">Volume/Quantidade:</label>
                    <input type="text" name="volume" id="volume"
                           value="<?= $editing ? htmlspecialchars($produto['volume'] ?? '') : (isset($volume) ? htmlspecialchars($volume) : '') ?>">
                </div>

                <div class="form-group">
                    <label for="unidade_medida">Unidade:</label>
                    <select name="unidade_medida" id="unidade_medida">
                        <option value="">Selecione</option>
                        <?php foreach ($unidades_medida as $unidade): ?>
                        <option value="<?= $unidade ?>" <?= ($editing && ($produto['unidade_medida'] ?? '') == $unidade) || (isset($unidade_medida) && $unidade_medida == $unidade) ? 'selected' : '' ?>>
                            <?= $unidade ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="text" name="preco" id="preco"
                       value="<?= $editing ? htmlspecialchars($produto['preco'] ?? '') : (isset($preco) ? htmlspecialchars($preco) : '') ?>">
            </div>

            <input type="submit" value="<?= $editing ? 'Atualizar' : 'Cadastrar' ?>">
        </form>

        <p><a href="../index.php">Voltar para a Página Inicial</a></p>
    </div>
</body>
</html>
