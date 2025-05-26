<?php
require("../includes/verifica_login.php");
require("../includes/config.php");

$id_usuario = $_SESSION["usuario_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remover"])) {
    $id_item = $_POST["remover"];
    $stmt = $pdo->prepare("DELETE FROM carrinho WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$id_item, $id_usuario]);
}

$stmt = $pdo->prepare("
    SELECT c.id AS carrinho_id, p.*, c.quantidade 
    FROM carrinho c
    JOIN produtos p ON c.produto_id = p.id
    WHERE c.usuario_id = ?
");
$stmt->execute([$id_usuario]);
$itens = $stmt->fetchAll();

$total = 0;
foreach ($itens as $item) {
    $total += $item["preco"] * $item["quantidade"];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-4">
    <h2 class="text-center mb-4">🛒 Carrinho de Compras</h2>

    <?php if (!$itens): ?>
        <p class="text-center">Seu carrinho está vazio.</p>
    <?php endif; ?>

    <?php if ($itens): ?>
        <form method="post">
        <table class="table table-dark table-hover mt-4">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Preço</th>
                    <th>Qtd</th>
                    <th>Subtotal</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= $item["titulo"] ?></td>
                    <td>R$ <?= number_format($item["preco"], 2, ",", ".") ?></td>
                    <td><?= $item["quantidade"] ?></td>
                    <td>R$ <?= number_format($item["preco"] * $item["quantidade"], 2, ",", ".") ?></td>
                    <td>
                        <button type="submit" name="remover" value="<?= $item["carrinho_id"] ?>" class="btn btn-sm btn-outline-danger">🗑️</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr class="table-active">
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td><strong>R$ <?= number_format($total, 2, ",", ".") ?></strong></td>
                </tr>
            </tbody>
        </table>
        </form>

        <form method="post" action="finalizar_compra.php" class="text-end">
            <button class="btn btn-success">Finalizar Compra</button>
        </form>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="../public/index.php" class="btn btn-outline-light">⬅️ Voltar para a Loja</a>
    </div>
</div>
</body>
</html>
