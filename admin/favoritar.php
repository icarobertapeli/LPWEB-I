<?php
session_start();
require("../includes/config.php");

$dados = $_POST;
$id_usuario = $_SESSION["usuario_id"];

$stmt = $pdo->prepare("INSERT INTO favoritos (usuario_id, tipo, tmdb_id, titulo, categoria, poster) 
                       VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    $id_usuario,
    $dados["tipo"],
    $dados["tmdb_id"],
    $dados["titulo"],
    $dados["categoria"],
    $dados["poster"]
]);

echo "Adicionado aos favoritos!";
