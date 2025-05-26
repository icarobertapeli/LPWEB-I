<?php
require("../includes/verifica_login.php");
require("../includes/config.php");

$id_usuario = $_SESSION["usuario_id"];

$stmt = $pdo->prepare("
    SELECT c.*, p.preco 
    FROM carrinho c
    JOIN produtos p ON c.produto_id = p.id
    WHERE c.usuario_id = ?
");
$stmt->execute([$id_usuario]);
$itens = $stmt->fetchAll();

if (!$itens) {
    die("Carrinho vazio.");
}

$total = 0;
foreach ($itens as $item) {
    $total += $item["preco"] * $item["quantidade"];
}

$stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)");
$stmt->execute([$id_usuario, $total]);
$pedido_id = $pdo->lastInsertId();

foreach ($itens as $item) {
    $stmt = $pdo->prepare("INSERT INTO pedidos_itens (pedido_id, produto_id, quantidade, preco_unitario)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $pedido_id,
        $item["produto_id"],
        $item["quantidade"],
        $item["preco"]
    ]);
}

$pdo->prepare("DELETE FROM carrinho WHERE usuario_id = ?")->execute([$id_usuario]);

header("Location: pedido_confirmado.php?id=$pedido_id");
exit;
?>