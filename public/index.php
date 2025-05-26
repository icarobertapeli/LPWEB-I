<?php
session_start();
$logado = isset($_SESSION["usuario_id"]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>TMDB CRUD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="#">🎬 TMDB CRUD</a>
    <div class="ms-auto">
        <?php if ($logado): ?>
            <span class="me-3 text-light">✅ Logado</span>
            <a class="btn btn-outline-light btn-sm me-2" href="../admin/carrinho.php">Carrinho</a>
            <a class="btn btn-danger btn-sm" href="../auth/logout.php">Logout</a>
        <?php else: ?>
            <a class="btn btn-primary btn-sm me-2" href="../auth/login.php">Login</a>
            <a class="btn btn-success btn-sm" href="../auth/cadastro.php">Cadastro</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container py-4">
    <h2 class="text-center mb-4">Títulos Populares</h2>

    <div class="d-flex justify-content-center gap-4 mb-4">
        <button class="btn btn-outline-info tab-btn active" data-tipo="movie">🎥 Filmes</button>
        <button class="btn btn-outline-info tab-btn" data-tipo="tv">📺 Séries</button>
    </div>

    <div id="resultado" class="row justify-content-center g-4"></div>
</div>

<script>
const logado = <?= $logado ? 'true' : 'false' ?>;
</script>
<script src="../assets/js/scripts.js"></script>

<div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="modalTitulo" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content bg-dark text-light border border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="modalTitulo">Título</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-md-4 text-center">
            <img id="modalImagem" src="" class="img-fluid rounded shadow-sm" alt="Poster">
          </div>
          <div class="col-md-8">
            <div id="modalInfo" class="small mb-3"></div>
            <p id="modalDescricao" class="small text-light-emphasis"></p>
          </div>
        </div>
        <hr class="text-secondary">
        <div id="modalElenco" class="mt-3">
          <!-- Elenco será inserido via JS -->
        </div>
      </div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmação de compra -->
<div class="modal fade" id="modalCompra" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-light">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Confirmar Compra</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <p><strong>Título:</strong> <span id="modalCompraTitulo"></span></p>
        <p><strong>Preço:</strong> R$ <span id="modalCompraPreco"></span></p>
        <input type="hidden" id="modalDadosCompra">
      </div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-success" id="btnConfirmarCompra">🛒 Comprar</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast de sucesso -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
  <div id="toastCompra" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        🎉 Título adicionado ao carrinho com sucesso!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
    </div>
  </div>
</div>

</body>
</html>
