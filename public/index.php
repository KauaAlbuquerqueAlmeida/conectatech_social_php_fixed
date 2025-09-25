<?php
require_once dirname(__DIR__) . '/src/PostController.php';
require_once dirname(__DIR__) . '/src/AuthController.php';
if (session_status() == PHP_SESSION_NONE) session_start();
$posts = fetch_feed();
$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ConectaTech - Feed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:linear-gradient(90deg,#0b69ff,#3b82f6)">
  <div class="container">
    <a class="navbar-brand" href="/">ConectaTech</a>
    <div>
    <?php if (isset($_SESSION['user_id'])): ?>
      <span class="text-white me-3">Olá, <?=htmlspecialchars($_SESSION['user_name'])?></span>
      <a class="btn btn-sm btn-light" href="./logout.php">Sair</a>
    <?php else: ?>
      <a class="btn btn-sm btn-light me-2" href="./login.php">Entrar</a>
      <a class="btn btn-sm btn-outline-light" href="./register.php">Registrar</a>
    <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <?php if ($flash_success): ?><div class="flash flash-success"><?=htmlspecialchars($flash_success)?></div><?php endif; ?>
  <?php if ($flash_error): ?><div class="flash flash-error"><?=htmlspecialchars($flash_error)?></div><?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Feed</h3>
    <?php if (isset($_SESSION['user_id'])): ?>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postModal">Novo Post</button>
    <?php else: ?>
      <small class="text-muted">Faça login para criar posts</small>
    <?php endif; ?>
  </div>

  <?php foreach ($posts as $p): ?>
    <div class="card mb-3">
      <div class="card-body">
        <div class="meta mb-2">
          <strong><?=htmlspecialchars($p['name'] ?? 'Usuário')?></strong>
          <span class="ms-2">| <?=htmlspecialchars($p['created_at'])?></span>
        </div>
        <p><?=nl2br(htmlspecialchars($p['content']))?></p>
        <?php if ($p['image']): ?>
          <div class="mt-2">
            <img src="/uploads/<?=htmlspecialchars($p['image'])?>" alt="imagem" class="post-img">
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Modal criar post -->
<div class="modal fade" id="postModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" action="./create_post.php" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Criar Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <textarea name="content" class="form-control" placeholder="No que você está pensando?" required></textarea>
        <label class="form-label mt-2">Imagem (opcional)</label>
        <input type="file" name="image" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        <button class="btn btn-primary" type="submit">Postar</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
