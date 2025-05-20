<?php
session_start();
require("../includes/config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        if ($stmt->execute([$nome, $email, $senha])) {
            $_SESSION["usuario_id"] = $pdo->lastInsertId();
            $_SESSION["usuario_email"] = $email;
            header("Location: ../public/index.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - TMDB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + estilo -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-dark text-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card bg-black border-light p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-3">📝 Cadastro</h3>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger text-center"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control bg-dark text-light border-secondary" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="text" name="email" id="email" class="form-control bg-dark text-light border-secondary" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" name="senha" id="senha" class="form-control bg-dark text-light border-secondary" required>
            </div>
            <div class="d-grid">
                <button class="btn btn-success">Cadastrar</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="login.php" class="text-info">Já tenho conta</a>
        </div>
    </div>
</div>

</body>
</html>
