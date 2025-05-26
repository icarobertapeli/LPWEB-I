<?php
session_start();
require("../includes/config.php");

if (!isset($_SESSION["usuario_id"])) exit("Você precisa estar logado.");

$dados = $_POST;
$id_usuario = $_SESSION["usuario_id"];
$tmdb_id = $dados["tmdb_id"];

$stmt = $pdo->prepare("SELECT id FROM produtos WHERE tmdb_id = ?");
$stmt->execute([$tmdb_id]);
$produto = $stmt->fetch();

if (!$produto) {
    $preco = rand(1000, 5500) / 100;
    $stmt = $pdo->prepare("INSERT INTO produtos (tmdb_id, tipo, titulo, categoria, poster, preco) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $tmdb_id,
        $dados["tipo"],
        $dados["titulo"],
        $dados["categoria"],
        $dados["poster"],
        $preco
    ]);
    $produto_id = $pdo->lastInsertId();
} else {
    $produto_id = $produto["id"];
}

$stmt = $pdo->prepare("SELECT id FROM carrinho WHERE usuario_id = ? AND produto_id = ?");
$stmt->execute([$id_usuario, $produto_id]);

if (!$stmt->fetch()) {
    $stmt = $pdo->prepare("INSERT INTO carrinho (usuario_id, produto_id) VALUES (?, ?)");
    $stmt->execute([$id_usuario, $produto_id]);
    echo "Adicionado ao carrinho!";
} else {
    echo "Esse título já está no carrinho.";
}
?>
