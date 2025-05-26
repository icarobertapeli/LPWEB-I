<?php
require("../includes/verifica_login.php");
require("../includes/config.php");

$id_usuario = $_SESSION["usuario_id"];

$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY data DESC");
$stmt->execute([$id_usuario]);
$pedidos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container py-5">
    <h2 class="text-center mb-4">📜 Histórico de Pedidos</h2>

    <?php if (!$pedidos): ?>
        <p class="text-center">Você ainda não realizou nenhuma compra.</p>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
            <div class="card bg-black text-light border-secondary mb-4">
                <div class="card-header d-flex justify-content-between">
                    <span><strong>Pedido #<?= $pedido["id"] ?></strong></span>
                    <span><?= date("d/m/Y H:i", strtotime($pedido["data"])) ?></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $stmtItens = $pdo->prepare("
                            SELECT p.titulo, p.poster, pi.quantidade, pi.preco_unitario
                            FROM pedidos_itens pi
                            JOIN produtos p ON pi.produto_id = p.id
                            WHERE pi.pedido_id = ?
                        ");
                        $stmtItens->execute([$pedido["id"]]);
                        $itens = $stmtItens->fetchAll();

                        foreach ($itens as $item): ?>
                            <div class="col-md-4 col-lg-3 mb-3">
                                <div class="card bg-dark text-light h-100">
                                    <img src="https://image.tmdb.org/t/p/w300<?= $item["poster"] ?>" 
                                    class="card-img-top img-fluid rounded mx-auto d-block" 
                                    style="height: 200px; width: 100%; object-fit: contain;" 
                                    alt="<?= $item["titulo"] ?>">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1"><?= $item["titulo"] ?></h6>
                                        <small><?= $item["quantidade"] ?> × R$ <?= number_format($item["preco_unitario"], 2, ',', '.') ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-end mt-3">
                        <strong>Total do Pedido: R$ <?= number_format($pedido["total"], 2, ',', '.') ?></strong>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="../public/index.php" class="btn btn-outline-light">⬅️ Voltar para a Loja</a>
    </div>
</div>
</body>
</html>
