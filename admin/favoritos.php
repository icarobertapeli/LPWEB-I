<?php
require("../includes/verifica_login.php");
require("../includes/config.php");

$id_usuario = $_SESSION["usuario_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["excluir"])) {
    $id = $_POST["excluir"];
    $stmt = $pdo->prepare("DELETE FROM favoritos WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$id, $id_usuario]);
}

$stmt = $pdo->prepare("SELECT * FROM favoritos WHERE usuario_id = ?");
$stmt->execute([$id_usuario]);
$favoritos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus Favoritos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Estilo -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="../public/index.php">🎬 TMDB CRUD</a>
    <div class="ms-auto">
        <a class="btn btn-outline-light btn-sm me-2" href="../public/index.php">Início</a>
        <a class="btn btn-danger btn-sm" href="../auth/logout.php">Logout</a>
    </div>
</nav>

<div class="container py-4">
    <h2 class="text-center mb-4">🎖️ Meus Favoritos</h2>

    <?php if (count($favoritos) === 0): ?>
        <p class="text-center text-white-50">Você ainda não favoritou nenhum título.</p>
    <?php else: ?>
        <div class="row justify-content-center g-4">
            <?php foreach ($favoritos as $fav): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 d-flex">
                    <div class="card bg-dark text-light w-100 h-100 border-light">
                        <img src="https://image.tmdb.org/t/p/w300<?= $fav['poster'] ?>" class="card-img-top" alt="<?= $fav['titulo'] ?>">
                        <div class="card-body p-2 text-center d-flex flex-column justify-content-between">
                            <h6 class="card-title"><?= $fav['titulo'] ?></h6>
                            <small class="text-info"><?= ucfirst($fav['tipo']) ?> • <?= $fav['categoria'] ?></small>
                            <form method="post" class="mt-2">
                                <input type="hidden" name="excluir" value="<?= $fav['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">🗑️ Remover</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
