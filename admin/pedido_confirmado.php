<?php
require("../includes/verifica_login.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5 text-center">
    <h1>✅ Pedido Finalizado!</h1>
    <p class="lead">Seu pedido <strong>#<?= htmlspecialchars($_GET['id']) ?></strong> foi concluído com sucesso.</p>
    <a href="../public/index.php" class="btn btn-outline-light mt-4">Voltar para a loja</a>
</div>
</body>
</html>